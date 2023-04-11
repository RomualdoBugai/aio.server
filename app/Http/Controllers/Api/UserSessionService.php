<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\UserSession as UserSession;

class UserSessionService extends Api
{

    public function one(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 03/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'id'    => ['required']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $userSession = UserSession::find($input['id']);

                if ($userSession == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user-session', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('user-session', 'found'),
                        'data'    => [
                            'session' => $userSession->getAttributes(),
                            'user'    => $userSession->user
                        ]
                    ]
                );

            break;
        }
    }


}
