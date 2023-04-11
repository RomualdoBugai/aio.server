<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\UserClient as UserClient;
use App\Models\UserInviteRequest as UserInviteRequest;
use App\Models\ConfirmEmail as ConfirmEmail;

class UserClientService extends Api
{
    protected static $controller = 'user-client';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 30/05/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'user_client_id'    => ['required', 'numeric'],
                    'invite_token'      => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

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

                $another = User::find($input['user_client_id']);

                if ($another == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $token = UserInviteRequest::where('token', $input['invite_token'])
                ->where('user_id', $input['user_id'])
                ->where('is_active',(bool) true)
                ->first();

                if ($token == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'token-not-found'),
                    ]);
                }

                $data = UserClient::where('user_id', $input['user_id'])
                ->where('client_user_id', $input['user_client_id'])
                ->where('is_active', (bool) true)
                ->first();
                
                if ($data != null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists')
                        ]
                    );
                }

                $userClient = new UserClient;
                $userClient = $userClient::create(
                    [
                        'user_id'          => $user->id,
                        'client_user_id'   => $another->id
                    ]
                );

                $token->update(
                    [
                        'is_active' => false
                    ]
                );

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'created'),
                ]);

            break;
            /**
             * @author Romualdo Bugai
             * @date 04/08/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'user_client_id'    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

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

                $another = User::find($input['user_client_id']);

                if ($another == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $data = UserClient::where('user_id', $input['user_id'])
                ->where('client_user_id', $input['user_client_id'])
                ->where('is_active', (bool) true)
                ->first();
                
                if ($data != null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists')
                        ]
                    );
                }

                $userClient = new UserClient;
                $userClient = $userClient::create(
                    [
                        'user_id'          => $user->id,
                        'client_user_id'   => $another->id
                    ]
                );

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'created'),
                ]);

            break;

        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 30/05/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'     => ['required', 'numeric'],
                    'condiction'  => ['required', 'in:father,child']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                if ($input['condiction'] == 'father') {
                    $data = UserClient::where('client_user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                } else {
                    $data = UserClient::where('user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                }

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
            /**
             * @author Romualdo Bugai
             * @date 06/06/2017
             * @return void
             */
            case '1.1':
                
                $data = UserClient::where('is_active', (bool) true)
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
             * @date 30/05/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'client_user_id'   => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);


                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $client = User::find($input['client_user_id']);

                if ($client == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $userClient = UserClient::where('client_user_id', $client->id)
                ->where('is_active', (bool) true)
                ->first();

                if ( $userClient != null ) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists'),
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'not-found'),
                    ]
                );

            break;
            
        }
    }

    public function count(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 30/05/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'     => ['required', 'numeric'],
                    'condiction'  => ['required', 'in:father,child']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                if ($input['condiction'] == 'father') {
                    $data = UserClient::where('client_user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                } else {
                    $data = UserClient::where('user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                }

                if ( $data->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'count'   => $data->count()
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
