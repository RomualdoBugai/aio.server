<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request as Request;

use App\Services\Useful\UserData;
use App\Services\Useful\Auth;
USE App\Models\UserSession as UserSession;
USE App\Models\User as User;

class LogOutService extends Api {

    public function check(Request $request) {
        switch ($request->version) {

            /**
             * @author William Novak
             * @date 26/10/2016
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'email'        => ['required', 'email'],
                    'app'          => ['required'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                # retrive data from input
                $email        = (string) trim(strtolower($input['email']));
                $app          = $input['app'];

                # initialize objects
                $auth = new Auth;
                # define app
                $auth->setApp($app->name);
                # fill user data entity
                $userData = new UserData($email);

                $user = User::where("email", $userData->email)->first();

                # response
                if($user == null) {
                    $user = new User;
                }

                # initialize logout service
                return response()->json($auth->logOutService($user));

            break;

            /**
             * @author William Novak
             * @date 2017-03-02
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'    => ['required'],
                    'app'   => ['required']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app          = $input['app'];

                $userSession = UserSession::with('user')->where('id', $input['id'])->first();

                if ($userSession == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user-session', 'not-found')
                        ]
                    );
                }

                $user = User::find($userSession->user->id);

                # initialize objects
                $auth = new Auth;
                # define app
                $auth->setApp($app->name);

                # response
                if($user == null) {
                    $user = new \App\Models\User;
                }

                # initialize logout service
                return response()->json($auth->logOutService($user));

            break;
        }

    }

}
