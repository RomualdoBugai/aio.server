<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\UserAdministrator as UserAdministrator;

class UserAdministratorService extends Api
{
    protected static $controller = 'user-administrator';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 07/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'user_insert_id'    => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $another = User::find($input['user_insert_id']);

                if ($another == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $check = UserAdministrator::where('user_id', $input['user_id'])
                ->where('is_active',(bool) true)
                ->first();

                if ($check != null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'already-exists'),
                    ]);
                }

                $userAdministrator = UserAdministrator::create(
                    [
                        'user_id'           => $user->id,
                        'insert_user_id'    => $another->id
                    ]
                );

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'created'),
                    'data'    => [
                        'request'       => $userAdministrator->id,
                        'created_at'    => $userAdministrator->created_at
                    ]
                ]);

            break;

        }
    }

    public function one(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author Romualdo Bugai
             * @date 07/06/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'        => ['required', 'numeric']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $administrator = UserAdministrator::where('user_id', $input['user_id'])
                ->where('is_active',(bool) true)
                ->first();

                if ( $administrator != null ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $administrator->toArray()
                        ]
                    );
                }

                if ($administrator == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

            break;

        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 07/06/2017
             * @return void
             */
            case '1.0':

                $data = UserAdministrator::where('is_active', (bool) true)
                    ->get();

                if ( $data->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $data->toArray()
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]
                );

            break;
        }
    }

    public function check(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author Romualdo Bugai
             * @date 07/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'   => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $userAdministrator = UserAdministrator::where('user_id', $user->id)
                ->where('is_active', (bool) true)
                ->first();

                if ( $userAdministrator != null ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]
                );

            break;
            
        }
    }
}
