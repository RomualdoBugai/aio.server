<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\UserPartner as UserPartner;
use App\Models\UserInviteRequest as UserInviteRequest;
use App\Models\ConfirmEmail as ConfirmEmail;

class UserPartnerService extends Api
{
    protected static $controller = 'user-partner';

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
                    'user_partner_id'   => ['required', 'numeric'],
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

                $another = User::find($input['user_partner_id']);

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

                $data = UserPartner::where('user_id', $input['user_id'])
                ->where('partner_user_id', $input['user_partner_id'])
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

                $userPartner = new UserPartner;
                $userPartner = $userPartner::create(
                    [
                        'user_id'           => $user->id,
                        'partner_user_id'   => $another->id
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
                    $data = UserPartner::where('partner_user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                } else {
                    $data = UserPartner::where('user_id', $user->id)
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
                
                $data = UserPartner::where('is_active', (bool) true)
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
                    'partner_user_id'   => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);


                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $partner = User::find($input['partner_user_id']);

                if ($partner == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $userPartner = UserPartner::where('partner_user_id', $partner->id)
                ->where('is_active', (bool) true)
                ->first();

                if ( $userPartner != null ) {
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
                    $data = UserPartner::where('partner_user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->get();

                } else {
                    $data = UserPartner::where('user_id', $user->id)
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

    public function update(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 14/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'user_partner_id'   => ['required', 'numeric'],
                    'rate'              => ['required', 'numeric']
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

                $another = User::find($input['user_partner_id']);

                if ($another == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $userPartner = UserPartner::where('user_id', $input['user_id'])
                ->where('partner_user_id', $input['user_partner_id'])
                ->where('is_active',(bool) true)
                ->first();

                if ($userPartner == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                $userPartner->update(
                    [
                        'rate' => $input['rate']
                    ]
                );

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'updated'),
                ]);

            break;

        }
    }

}
