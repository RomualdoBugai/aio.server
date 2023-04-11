<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;
use App\Models\App as App;
use App\Models\UserApp as UserApp;

class UserAppService extends Api
{
    protected static $controller = 'user-app';

    public function create(Request $request)
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
                    'user_id'          => ['required']
                ];
                # define messages
                $messages = [
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                # app
                $app        = $request->input('app');

                # user
                $user       = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $check = UserApp::where('user_id', $user->id)->where('app_id', $app->id)->first();

                if ($check != null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists')
                        ]
                    );
                }

                $userApp    = UserApp::create(
                    [
                        'user_id' => $user->id,
                        'app_id'  => $app->id
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created')
                    ]
                );

            break;

        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 01/06/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'          => ['required']
                ];
                # define messages
                $messages = [
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                # app
                $app        = $request->input('app');

                # user
                $user       = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $app = UserApp::
                select(
                    'app.name',
                    'user_app.created_at'
                )
                ->where('user_app.user_id', $user->id)
                ->join('app', 'app.id', '=', 'user_app.app_id')
                ->get();

                if ($app->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'not-found'),
                        'data'    => $app
                    ]
                );

            break;

        }
    }

}
