<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\UserBirth as UserBirth;

class UserBirthService extends Api
{

    protected static $controller = 'user-birth';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 04/08/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'date_birth'        => ['required', 'date:Y-m-d']
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

                $userBirth = UserBirth::where('user_id', $input['user_id'])
                ->first();

                if ($userBirth != null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists'),
                        ]
                    );
                }

                $userBirth = new UserBirth;
                $userBirth = $userBirth::create(
                    [
                        'user_id'           => $user->id,
                        'date_birth'        => $input['date_birth']
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

    public function one(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 04/08/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric']
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

                $userBirth = UserBirth::where('user_id', $input['user_id'])
                ->first();

                if ($userBirth == null) {
                    return response()->json(
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
                        'data'    => $userBirth->getAttributes()
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
             * @date 21/07/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'date_birth'        => ['required', 'date:Y-m-d']
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

                $userBirth = UserBirth::where('user_id', $input['user_id'])
                    ->first();

                if ($userBirth == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    'date_birth'      => $input['date_birth']
                );

                $userBirth = UserBirth::where('id', $userBirth->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;

        }
    }
}
