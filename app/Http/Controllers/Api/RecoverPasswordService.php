<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\RecoverPassword as RecoverPassword;
use App\Models\User as User;

class RecoverPasswordService extends Api
{


    protected static $controller = 'recover-password';

    public function start(Request $request)
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
                    'email' => ['required', 'email', 'exists:user,email'],
                    'url'   => ['required', 'url'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);
                $email    = $input['email'];
                $app      = $input['app'];

                if ($validate->status == false) {
                    return response()->json($validate);
                }

                $user = \App\Models\User::where('email', $email)->first();

                $check = RecoverPassword::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

                if ($check->count() > 0) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'pending-request')
                        ]
                    );
                }

                # create registry in database
                $recoverPassword            = new RecoverPassword;
                $recoverPassword->user_id   = $user->id;
                $recoverPassword->app_id    = $app->id;
                $recoverPassword->is_active = true;
                $recoverPassword->verify    = md5(date('Ymdhis'));
                $recoverPassword->save();

                # security
                $key    = $recoverPassword->id;
                $verify = $recoverPassword->verify;

                # build query
                $raw = [
                    'i' => $key,
                    'v' => $verify,
                    'e' => $email
                ];

                $url = $input['url'] . "?" . http_build_query($raw);

                $title  = message('common', 'mail.user-recover-password.title');
                $resume = json_decode($app->resume, true);

                $data = [
                    'user'  => $user,
                    'app'   => $app,
                    'url'   => $url,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.recover-password', $data, function($message) use (&$user, &$title) {
                    $message->to($user->email, $user->name)->subject($title);
                });

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'recover-password-email-send'),
                        'data'    => [
                            'user' => [
                                'id'    => $user->id,
                                'name'  => $user->name,
                                'email' => $user->email
                            ],
                            'url' => $url
                        ]
                    ]
                );

            break;
        }
    }

    public function finish(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'key'      => ['required', 'exists:recover_password,id'],
                    'verify'   => ['required', 'exists:recover_password,verify'],
                    'password' => ['required', 'min:32', 'max:32'],
                ];
                # define messages
                $messages = [
                    'min'            => message(self::$controller, 'invalid-encryption'),
                    'max'            => message(self::$controller, 'invalid-encryption'),
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status == false) {
                    return response()->json($validate);
                }

                $key    = $input['key'];
                $verify = $input['verify'];

                $recoverPassword = RecoverPassword::where('id', $key)
                ->where('verify', $verify)
                ->where('is_active', true)
                ->first();

                if ($recoverPassword == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'request-expired')
                        ]
                    );
                }

                $user = User::find($recoverPassword->user_id);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'user-not-found')
                        ]
                    );
                }

                User::where("id", $user->id)
                ->update(
                    [
                        'password' => trim($input['password'])
                    ]
                );

                RecoverPassword::where('id', $key)
                ->where('verify', $verify)
                ->update(
                    ['is_active' => false]
                );

                $app = $input['app'];

                $title  = message('common', 'mail.user-password-changed.title');
                $resume = json_decode($app->resume, true);

                $data   = [
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
                        'message' => message(self::$controller, 'password-recovery-successful')
                    ]
                );

            break;
        }
    }

}
