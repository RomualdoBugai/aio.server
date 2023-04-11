<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\UserEqual as UserEqual;
use App\Models\UserInviteRequest as UserInviteRequest;
use App\Models\ConfirmEmail as ConfirmEmail;

class UserShareService extends Api
{

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
                    'message' => message('invite-user', 'shared-successful'),
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
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

                if ($token == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('invite-user', 'request-not-found'),
                    ]);
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
                        'message' => message('invite-user', 'shared-successful'),
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
             * @author William Novak
             * @date 2017-02-09
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'user_equal_id'     => ['required', 'numeric'],
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
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $another = User::find($input['user_equal_id']);

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
                ->where('app_id', $app->id)
                ->first();

                if ($userEqual == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user-share', 'relation-not-found')
                        ]
                    );
                }

                $userEqual->delete();

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('user-share', 'deleted')
                    ]
                );

            break;
        }
    }

    public function update(Request $request)
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
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

                if ($token == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('invite-user', 'request-not-found'),
                    ]);
                }

                $token->update(
                    [
                        'is_active' => false
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('invite-user', 'shared-successful'),
                        'data'    => [
                            'request' => $token->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

}
