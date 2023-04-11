<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User                         as User;
use App\Models\Personal\UserNotification    as UserNotification;

class UserNotificationService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author  William Novak
             * @return  json
             * @version 1.0 2017-03-23
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'       => ['required'],
                    'notification'  => ['required', 'json']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $userNotification = UserNotification::where("user_id", $user->id)->first();

                if ($userNotification != null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user-notification', 'already-exists')
                        ]
                    );
                }

                $userNotification = UserNotification::create(
                    [
                        'user_id'       => (int)    $user->id,
                        'notification'  => (string) $input['notification']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('user-notification', 'created'),
                        'data'    => [
                            'user_notification' => $userNotification->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

    public function update(Request $request)
    {
        switch ($request->version) {
            /**
             * @author  William Novak
             * @return  json
             * @version 1.0 2017-03-23
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'       => ['required'],
                    'notification'  => ['required', 'json']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $userNotification = UserNotification::where("user_id", $user->id)->first();

                if ($userNotification == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user-notification', 'not-found')
                        ]
                    );
                }

                $userNotification->update(
                    [
                        'user_id'       => (int)    $user->id,
                        'notification'  => (string) $input['notification']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('user-notification', 'updated'),
                        'data'    => [
                            'user_notification' => $userNotification->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

    public function one(Request $request)
    {
        switch ($request->version) {
            /**
             * @author  William Novak
             * @return  json
             * @version 1.0 2017-03-23
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'       => ['required']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $userNotification = UserNotification::where("user_id", $user->id)->first();

                if ($userNotification == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user-notification', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('user-notification', 'found'),
                        'data'    => [
                            'user_notification' => $userNotification->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }


}
