<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;

class ChangePasswordService extends Api
{

    protected static $controller = 'change-password';

    public function update(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'email'    => ['required', 'email', 'exists:user,email'],
                    'password' => ['required', 'min:32', 'max:32'],
                ];
                # define messages
                $messages = [
                    'min'           => message(self::$controller, "invalid-encryption"),
                    'max'           => message(self::$controller, "invalid-encryption"),
                    'email'         => message("user", "email-unavailable", ['email' => $input['email']])
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                User::where("email", $input['email'])
                ->update(
                    [
                        'password' => trim($input['password'])
                    ]
                );

                $user   = User::where("email", $input['email'])->first();

                $app    = $input['app'];

                $title  = message('common', 'mail.user-password-changed.title');
                $resume = json_decode($app->resume, true);

                $data = [
                    'user'  => $user,
                    'app'   => $app,
                    'url'   => $app->url,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.password-changed', $data, function($message) use (&$user, &$title) {
                    $message->to($user->email, $user->name)->subject($title);
                });

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, "password-changed-successful")
                    ]
                );

            break;
        }
    }


}
