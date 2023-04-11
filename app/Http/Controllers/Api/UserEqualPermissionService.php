<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\UserEqual as UserEqual;
use App\Models\UserEqualPermission as UserEqualPermission;

class UserEqualPermissionService extends Api
{
    protected static $controller = 'user-equal-permission';

    public function create(Request $request) 
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 04/05/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_equal_id'  => ['required', 'numeric'],
                    'enterprise_id'  => ['required', 'numeric'],
                    'actions'        => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $data = UserEqualPermission::where('user_equal_id',$input['user_equal_id'])
                ->where('enterprise_id',$input['enterprise_id'])
                ->where('is_active', (bool) true)
                ->first();

                if ($data != null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists')
                        ]
                    );
                }

                $userEqualPermission = UserEqualPermission::create(
                    [
                        'user_equal_id' => (int) $input['user_equal_id'],
                        'enterprise_id' => (int) $input['enterprise_id'],
                        'actions'       => (string) json_encode($input['actions']),
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created'),
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
             * @date 04/05/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required', 'numeric'],
                    'actions'       => ['required'],
                ];

                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $update = [
                    'actions'       => (string) json_encode($input['actions']),
                ];

                UserEqualPermission::where('id', $input['id'])
                ->update($update);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, "updated")
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 04/05/2017
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required', 'numeric'],
                    'tag'           => ['required'],
                    'chave'         => ['required'],
                    'status'        => ['required']
                ];

                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $data = UserEqualPermission::where('id', $input['id'])->first();

                # decodifica so o campo actions
                $actions = json_decode($data['actions'], true);

                #valores
                $tag = $input['tag'];
                $chave = $input['chave'];
                $status = $input['status'];

                # verifica se existe a tag
                if(isset($actions[$tag])){
                    # sim , existe

                    # verifica se existe a chave
                    if(isset($actions[$tag][$chave])){
                        # sim, existe. entao atualize
                        $actions[$tag][$chave] = $status;

                    }else{
                        # cria a chave
                        $actions[$tag] += array(
                            $chave                    => $status
                        );

                    }

                }else{
                    # cria a tag
                    $actions[$tag] = array(
                        $chave                    => $status
                    );
                }

                $update = [
                    'actions'       => (string) json_encode($actions),
                ];

                UserEqualPermission::where('id', $input['id'])
                ->update($update);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, "updated")
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
             * @date 04/05/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $userEqualPermission = UserEqualPermission::where('id', $input['id'])
                ->first();

                if ($userEqualPermission == null) {
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

                UserEqualPermission::where('id', $input['id'])
                ->where('is_active', (bool) true)
                ->update($update);

                //$userEqualPermission->delete();

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'deleted')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 09/05/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_equal_id'  => ['required', 'numeric'],
                    'enterprise_id'  => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $userEqualPermission = UserEqualPermission::where('user_equal_id', $input['user_equal_id'])
                ->where('enterprise_id', $input['enterprise_id'])
                ->where('is_active', (bool) true)
                ->first();

                if ($userEqualPermission == null) {
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

                UserEqualPermission::where('user_equal_id', $input['user_equal_id'])
                ->where('enterprise_id', $input['enterprise_id'])
                ->where('is_active', (bool) true)
                ->update($update);

                //$userEqualPermission->delete();

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'deleted')
                    ]
                );

            break;
        }
    }

    public function delete(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 04/05/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $userEqualPermission = UserEqualPermission::where('id', $input['id'])
                ->first();

                if ($userEqualPermission == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                $userEqualPermission->delete();

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'deleted')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 09/05/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_equal_id'  => ['required', 'numeric'],
                    'enterprise_id'  => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $userEqualPermission = UserEqualPermission::where('user_equal_id', $input['user_equal_id'])
                ->where('enterprise_id', $input['enterprise_id'])
                ->where('is_active', (bool) true)
                ->first();

                if ($userEqualPermission == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                $userEqualPermission->delete();

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'deleted')
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
             * @author Romualdo Bugai
             * @date 04/05/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'user_equal_id'  => ['required', 'numeric'],
                    'enterprise_id'  => ['required', 'numeric']
                ];
                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $data = UserEqualPermission::
                select(
                    'user_equal.user_id',
                    'user_equal.equal_user_id',
                    'user_equal_permission.*'
                )
                ->where('enterprise_id', $input['enterprise_id'])
                ->where('user_equal.id', $input['user_equal_id'])
                ->where('user_equal_permission.is_active', (bool) true)
                ->join('user_equal', 'user_equal.id', '=', 'user_equal_permission.user_equal_id')
                ->first();

                if ($data == null) {
                    $data = UserEqual::where('id', $input['user_equal_id'])->first();

                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                            'data'    => $data
                        ]
                    );
                }

                # decodifica so o campo actions
                $data['actions'] = json_decode($data['actions']);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $data
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 11/05/2017
             * @return void
             */
            case '1.1':
                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'       => ['required', 'numeric'],
                    'equal_user_id' => ['required', 'numeric'],
                    'enterprise_id' => ['required', 'numeric']
                ];
                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $data = UserEqualPermission::
                select(
                    'user_equal.user_id',
                    'user_equal.equal_user_id',
                    'user_equal_permission.*'
                )
                ->where('user_equal.user_id', $input['equal_user_id'])
                ->where('user_equal.equal_user_id', $input['user_id'])
                ->where('user_equal_permission.enterprise_id', $input['enterprise_id'])
                ->where('user_equal.is_active', (bool) true)
                ->where('user_equal_permission.is_active', (bool) true)
                ->join('user_equal', 'user_equal.id', '=', 'user_equal_permission.user_equal_id')
                ->first();

                if ($data == null) {
                    $data = UserEqual::where('user_id', $input['equal_user_id'])
                    ->where('equal_user_id', $input['user_id'])
                    ->first();

                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                            'data'    => $data
                        ]
                    );
                }

                # decodifica so o campo actions
                $data['actions'] = json_decode($data['actions']);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $data
                    ]
                );

            break;

        }
    }
}
