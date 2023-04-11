<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\UserAddress as UserAddress;

class UserAddressService extends Api
{

    public function create(Request $request)
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
                    'user_id'     => ['required', 'exists:user,id'],
                    'street'      => ['required'],
                    'number'      => ['required'],
                    'district'    => ['required', 'max:96'],
                    'city'        => ['required', 'max:112'],
                    'state'       => ['required', 'max:2'],
                    'postal_code' => ['required', 'alpha_num', 'max:8'],
                    'complement'  => ['required', 'max:48'],
                    'country_id'  => ['required'],
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

                $userAddress = UserAddress::create(
                    [
                        'user_id'     => (int)    $input['user_id'],
                        'street'      => (string) trim(strtolower($input['street'])),
                        'number'      => (string) trim($input['number']),
                        'district'    => (string) trim(strtolower($input['district'])),
                        'city'        => (string) trim(strtolower($input['city'])),
                        'state'       => (string) trim(strtolower($input['state'])),
                        'postal_code' => (string) trim($input['postal_code']),
                        'complement'  => (string) trim($input['complement']),
                        'country_id'  => (int)    $input['country_id'],
                        'is_active'   => (bool)   true,
                        'default'     => (bool)   true,
                    ]
                );

                $userAddress = UserAddress::find($userAddress->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('address', 'created'),
                        'data'    => $userAddress->getAttributes()
                    ]
                );

            break;
        }
    }

    public function update(Request $request)
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
                    'id'            => ['required', 'exists:user_address,id'],
                    'street'        => ['required'],
                    'number'        => ['required'],
                    'district'      => ['required', 'max:96'],
                    'city'          => ['required', 'max:112'],
                    'state'         => ['required', 'max:2'],
                    'postal_code'   => ['required', 'alpha_num', 'max:8'],
                    'complement'    => ['required', 'max:48'],
                    'country_id'    => ['required', 'numeric'],
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

                $userAddress = UserAddress::find($input['id']);

                $userAddress->update(
                    [
                        'street'      => (string) trim(strtolower($input['street'])),
                        'number'      => (string) trim($input['number']),
                        'district'    => (string) trim(strtolower($input['district'])),
                        'city'        => (string) trim(strtolower($input['city'])),
                        'state'       => (string) trim(strtolower($input['state'])),
                        'postal_code' => (string) trim($input['postal_code']),
                        'complement'  => (string) trim($input['complement']),
                        'country_id'  => (int)    $input['country_id'],
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('address', 'updated')
                    ]
                );

            break;
        }
    }

    public function get(Request $request)
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
                    'user_id'       => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $userAddress = UserAddress::where('user_id', $input['user_id'])->get();

                if ($userAddress->count() == 0) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'not-found')
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'    => true,
                        'message'   => message('address', 'total-found', ['total' => $userAddress->count()]),
                        'data'      => ['user_address' => $userAddress->toArray()]
                    ]
                );

            break;
        }
    }

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
                    'id'    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $userAddress = UserAddress::find($input['id']);

                if ($userAddress == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'not-found')
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'    => true,
                        'message'   => message('address', 'found'),
                        'data'      => ['user_address' => $userAddress->getAttributes()]
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 20/07/2017
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

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

                $userAddress = UserAddress::where('user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->first();

                if ($userAddress == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'not-found')
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'    => true,
                        'message'   => message('address', 'found'),
                        'data'      => ['user_address' => $userAddress->getAttributes()]
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
             * @date 03/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'id'            => ['required'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $userAddress = UserAddress::find($input['id']);

                if ($userAddress == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'not-found')
                        ]
                    );
                }

                if ($userAddress->is_active == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'no-disable')
                        ]
                    );
                }

                $userAddress->update(
                    [
                        'is_active' => 0
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('address', 'deactivated')
                    ]
                );

            break;
        }
    }

    public function enable(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 03/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'id'            => ['required'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $userAddress = UserAddress::find($input['id']);

                if ($userAddress == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'not-found')
                        ]
                    );
                }

                if ($userAddress->is_active == 1) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'no-enable')
                        ]
                    );
                }

                $userAddress->update(
                    [
                        'is_active' => 1
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('address', 'activated')
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
             * @date 20/07/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

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

                $userAddress = UserAddress::where('user_id', $user->id)
                    ->where('is_active', (bool) true)
                    ->first();

                if ($userAddress == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'not-found')
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'    => true,
                        'message'   => message('address', 'found'),
                        'data'      => ['id' => $userAddress->id]
                    ]
                );

            break;
        }
    }

}
