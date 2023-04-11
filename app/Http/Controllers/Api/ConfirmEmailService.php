<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User as User;
use App\Models\ConfirmEmail as ConfirmEmail;

class ConfirmEmailService extends Api
{

    public function confirm(Request $request)
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
                    'key'      => ['required', 'exists:confirm_email,id'],
                    'verify'   => ['required', 'min:32', 'max:32', 'exists:confirm_email,verify'],
                    'email'    => ['required', 'email', 'exists:confirm_email,email'],
                ];
                # define messages
                $messages = [
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status == false) {
                    return response()->json($validate);
                }

                $key    = $input['key'];
                $verify = $input['verify'];

                $confirmEmail = ConfirmEmail::where('id', $key)
                ->where('verify', $verify)
                ->where('is_confirmed', false)
                ->first();

                if ($confirmEmail == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('auth', 'email-has-confirmed', ['email' => $input['email']])
                        ]
                    );
                }

                $user = User::find($confirmEmail->user_id);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                ConfirmEmail::where("id", $confirmEmail->id)
                ->update(
                    [
                        'is_confirmed' => true
                    ]
                );

                $app    = $input['app'];

                # send welcome email

                $title  = message('common', 'mail.user-email-confirmed.title', ['name'  => ownName(firstName($user->name))]);
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

                Mail::send('account.email-confirmed', $data, function($message) use (&$user, &$title) {
                    $message->to($user->email, $user->name)->subject($title);
                });

                # send welcome email

                $title  = message('common', 'mail.user-welcome.title', ['name'  => ownName(firstName($user->name)), 'app'   => $app->name]);
                $resume = json_decode($app->resume, true);

                $data = [
                    'user' => $user,
                    'app'  => $app,
                    'url'  => $app->url,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.welcome', $data, function($message) use (&$user, &$title){
                    $message->to($user->email, $user->name)->subject($title);
                });

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('auth', 'email-confirmed', ['email' => $input['email']])
                    ]
                );

            break;
        }
    }

}
