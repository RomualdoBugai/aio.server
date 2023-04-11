<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request as Request;

use App\Services\Useful\Auth as Auth;
use App\Services\Useful\UserData as UserData;

class LogInService extends Api
{

    public function check(Request $request)
    {
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
                    'password'     => ['required'],
                    'localSession' => ['required'],
                    'startTrial'   => ['required'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app = $request->input('app');

                # retrive data from input
                $email        = (string) trim(strtolower($input['email']));
                $password     = (string) trim($input['password']);
                $localSession = (bool)   ($input['localSession'] == null ? false : true);
                $startTrial   = (bool)   ($input['startTrial']   == null ? false : true);
                $application  = (string) trim(strtolower($app->name));

                # initialize objects
                $auth = new Auth;
                # define app
                $auth->setApp($application);
                # define if start trial or not
                $auth->setStartTrial($startTrial);
                # fill user data entity
                $userData = new UserData($email, $password);
                # response
                return response()->json($auth->initilize($userData, true));

            break;

            /**
             * @author  William Novak
             * @date    2016-10-28
             * @return  void
             */
            case '1.1':

                $input = $request->input();

                # define rules
                $rules = [
                    'email'             => ['required', 'email'],
                    'password'          => ['required'],
                    'localSession'      => ['required'],
                    'startTrial'        => ['required'],
                    'confirmedEmail'    => ['required', 'boolean']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app = $request->input('app');

                # retrive data from input
                $email          = (string)  trim(strtolower($input['email']));
                $password       = (string)  trim($input['password']);
                $localSession   = (bool)    ($input['localSession'] == null ? false : true);
                $startTrial     = (bool)    ($input['startTrial']   == null ? false : true);
                $application    = (string)  trim(strtolower($app->name));
                $confirmedEmail = (bool)    $input['confirmedEmail'];

                # initialize objects
                $auth = new Auth($confirmedEmail);
                # define app
                $auth->setApp($application);
                # define if start trial or not
                $auth->setStartTrial($startTrial);
                #
                # fill user data entity
                $userData = new UserData($email, $password);
                # response
                return response()->json($auth->initilize($userData, true));

            break;

            /**
             * @author  William Novak
             * @date    2016-10-28
             * @return  void
             */
            case '1.2':

                $input = $request->input();

                # define rules
                $rules = [
                    'email'             => ['required', 'email'],
                    'password'          => ['required'],
                    'localSession'      => ['required'],
                    'startTrial'        => ['required'],
                    'confirmedEmail'    => ['required', 'boolean']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app = $request->input('app');

                # retrive data from input
                $email          = (string)  trim(strtolower($input['email']));
                $password       = (string)  trim($input['password']);
                $localSession   = (bool)    ($input['localSession'] == null ? false : true);
                $startTrial     = (bool)    ($input['startTrial']   == null ? false : true);
                $application    = (string)  trim(strtolower($app->name));
                $confirmedEmail = (bool)    $input['confirmedEmail'];

                # initialize objects
                $auth = new Auth($confirmedEmail);
                $auth->setIgnoreApp(true);
                # define app
                $auth->setApp($application);
                # define if start trial or not
                $auth->setStartTrial($startTrial);
                #
                # fill user data entity
                $userData = new UserData($email, $password);
                # response
                return response()->json($auth->initilize($userData, true));

            break;

        }

    }

}
