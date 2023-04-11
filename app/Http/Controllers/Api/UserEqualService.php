<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\UserEqual as UserEqual;
use App\Models\UserInviteRequest as UserInviteRequest;
use App\Models\ConfirmEmail as ConfirmEmail;

class UserEqualService extends Api
{
    protected static $controller = 'user-equal';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 2017-02-09
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'user_equal_id'     => ['required', 'numeric']
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

                $another = User::find($input['user_equal_id']);

                if ($another == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $data = UserEqual::where('user_id', $input['user_id'])
                ->where('equal_user_id', $input['user_equal_id'])
                ->where('is_active', (bool) true)
                ->first();
                
                if ($data != null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists'),
                            'data'    => $data
                        ]
                    );
                }

                $userEqual = new UserEqual;
                $userEqual = $userEqual::create(
                    [
                        'user_id'       => $user->id,
                        'equal_user_id' => $another->id,
                        'app_id'        => $app->id
                    ]
                );

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'created'),
                ]);

            break;

            /**
             * @author William Novak
             * @date 2017-02-09
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'user_equal_id'     => ['required', 'numeric'],
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

                $another = User::find($input['user_equal_id']);

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

                $data = UserEqual::where('user_id', $input['user_id'])
                ->where('equal_user_id', $input['user_equal_id'])
                ->where('is_active', (bool) true)
                ->first();
                
                if ($data != null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists'),
                            'data'    => $data
                        ]
                    );
                }

                $userEqual = new UserEqual;
                $userEqual = $userEqual::create(
                    [
                        'user_id'       => $user->id,
                        'equal_user_id' => $another->id,
                        'app_id'        => $app->id
                    ]
                );

                $token->update(
                    [
                        'is_active' => false
                    ]
                );

                $ConfirmEmail = new ConfirmEmail;
                $ConfirmEmail::create(
                    [
                        'user_id'           => (int) $another->id,
                        'email'             => (string) $another->email,
                        'is_confirmed'      => true,
                        'verify'            => (string) md5(date('Ymdhis'))
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created'),
                        'data'    => [
                            'request' => $token->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

    public function disable(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 11/05/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'equal_user_id'     => ['required', 'numeric']
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
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $another = User::find($input['equal_user_id']);

                if ($another == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $userEqual = UserEqual::where('user_id', $user->id)
                ->where('equal_user_id', $another->id)
                ->where('is_active', (bool) true)
                //->where('app_id', $app->id)
                ->first();

                if ($userEqual == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                $update = [
                    'is_active'       => (bool) false,
                ];

                UserEqual::where('id', $userEqual['id'])
                ->update($update);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, "disable"),
                        'data'    => $userEqual
                    ]
                );

            break;            
        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 29/09/2016
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
                    $userEqual = UserEqual::where('equal_user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                } else {
                    $userEqual = UserEqual::where('user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                }

                if ( $userEqual->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $userEqual->toArray()
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
             * @author William Novak
             * @date 29/09/2016
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'       => ['required', 'numeric'],
                    'equal_user_id' => ['required', 'numeric'],
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
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $another = User::find($input['equal_user_id']);

                if ($another == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $userEqual = UserEqual::where('equal_user_id', $another->id)
                ->where('user_id', $user->id)
                ->where('is_active', (bool) true)
                ->first();

                if ( $userEqual == null ) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 29/09/2016
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'       => ['required', 'numeric'],
                    'invite_token'  => ['required']
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
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
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

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
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
             * @date 07/07/2017
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
                    $userEqual = UserEqual::where('equal_user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                } else {
                    $userEqual = UserEqual::where('user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                }

                $countUserEqual = $userEqual->count();

                if ( $countUserEqual > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'user'    => $countUserEqual
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
