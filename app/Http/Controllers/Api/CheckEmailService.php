<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\UserApp;
use Illuminate\Http\Request;

class CheckEmailService extends Api
{

    public function check(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 29/09/2016
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'email'       => ['required', 'email'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);
                $email    = $input['email'];

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = \App\Models\User::where('email', $email)->first();

                if ($user == null) {
                    return response()->json([
                        'status'  => true,
                        'message' => message('user', 'email-available', ['email' => $email]),
                    ]);
                }

                return response()->json([
                    'status'  => false,
                    'message' => message('user', 'email-unavailable', ['email' => $email]),
                ]);

            break;

            /**
             * @author William Novak
             * @date   2017-03-31
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'email'       => ['required', 'email'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);
                $email    = $input['email'];

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = \App\Models\User::where('email', $email)->first();

                if ($user == null) {
                    return response()->json([
                        'status'  => true,
                        'message' => message('user', 'email-available', ['email' => $email]),
                        'data'    => [
                            'link' => false,
                            'rel'  => [],
                            'user' => []
                        ]
                    ]);
                }

                $userApp = \App\Models\UserApp::with('app')->where('user_id', $user->id)->get();

                if ($userApp->count() > 0) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'email-unavailable', ['email' => $email]),
                        'data'    => [
                            'link'  => true,
                            'rel'   => $userApp->makeHidden(['id', 'app_id', 'user_id', 'updated_at'])->toArray(),
                            'user'  => [
                                'id'    => $user->id,
                                'name'  => $user->name,
                                'email' => $user->email
                            ]
                        ]
                    ]);
                }

                return response()->json([
                    'status'  => true,
                    'message' => message('user', 'email-unavailable', ['email' => $email]),
                    'data'    => [
                        'link' => false,
                        'rel'  => [],
                        'user' => []
                    ]
                ]);

            break;

        }
    }



}
