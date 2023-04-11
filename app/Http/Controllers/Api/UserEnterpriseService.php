<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User             as User;
use App\Models\Enterprise        as Enterprise;
use App\Models\UserEnterprise    as UserEnterprise;
use App\Models\EnterprisePhone   as EnterprisePhone;
use App\Models\EnterpriseEmail   as EnterpriseEmail;
use App\Models\EnterpriseAddress as EnterpriseAddress;
use App\Models\EnterpriseAdditional as EnterpriseAdditional;
use App\Models\Certificate       as Certificate;

class UserEnterpriseService extends Api
{
    protected static $controller = 'user-enterprise';

    public function create(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'national_code' => ['required', 'max:16', 'exists:enterprise,national_code'],
                    'user_id'       => ['required', 'exists:user,id'],
                ];
                # define messages
                $messages = [
                    'exists'      => "The the user does not exist",
                    'required'    => "The ':attribute' field is required",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $enterprise = Enterprise::where('national_code', $input['national_code'])->first();

                if ($enterprise == null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                $check = UserEnterprise::where('user_id', $input['user_id'])
                ->where('enterprise_id', $enterprise->id)
                ->where('is_active', (bool) true)
                ->first();

                if ($check != null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'relation-already-found')
                        ]
                    );
                }

                $userEnterprise = UserEnterprise::create(
                    [
                        'user_id'       => (int) $input['user_id'],
                        'enterprise_id' => (int) $enterprise->id
                    ]
                );

                $userEnterprise = UserEnterprise::find($userEnterprise->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created'),
                        'data'    => [
                            'enterprise' => $enterprise->getAttributes(),
                            'relation'   => [
                                'id' => $userEnterprise->id
                            ]
                        ]
                    ]
                );

            break;

            /**
             * @author William Novak
             * @version 1.1 2016-01-06
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_id' => ['required'],
                    'user_id'       => ['required'],
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

                $enterprise = Enterprise::find($input['enterprise_id']);

                if ($enterprise == null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                $user = User::find($input['user_id']);

                if ($user == null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message("user", 'not-found')
                        ]
                    );
                }

                $check = UserEnterprise::where('user_id', $user->id)
                ->where('enterprise_id', $enterprise->id)
                ->first();

                if ($check != null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'relation-already-found'),
                        ]
                    );
                }

                $userEnterprise = UserEnterprise::create(
                    [
                        'user_id'       => (int) $user->id,
                        'enterprise_id' => (int) $enterprise->id
                    ]
                );

                $userEnterprise = UserEnterprise::find($userEnterprise->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created'),
                        'data'    => [
                            'enterprise' => $enterprise->getAttributes(),
                            'relation'   => [
                                'id' => $userEnterprise->id
                            ]
                        ]
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
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'       => ['required', 'exists:user,id'],
                    'addresses'     => ['required', 'boolean'],
                    'emails'        => ['required', 'boolean'],
                    'phones'        => ['required', 'boolean'],
                    'additional'    => ['required', 'boolean'],
                    'certificates'  => ['required', 'boolean'],
                ];
                # define messages
                $messages = [
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $data = UserEnterprise::
                select(
                    'user_enterprise.*',
                    'enterprise.id',
                    'enterprise.name',
                    'enterprise.fantasy_name',
                    'enterprise.national_code',
                    'enterprise.legal_nature',
                    'enterprise.is_matrix',
                    'enterprise.open_at',
                    'enterprise.last_update',
                    'enterprise.status'
                )
                ->where('user_id', $input['user_id'])
                ->where('status', (bool)   true)
                ->join('enterprise', 'user_enterprise.enterprise_id', '=', 'enterprise.id')
                ->get();

                if ($data->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                //$data->makeHidden(['updated_at','created_at']);

                foreach ($data as $key => $enterprise)
                {
                    if ($input['addresses'])
                    {
                        $addresses = EnterpriseAddress::where('enterprise_id', $enterprise->id)->get();
                        $addresses->makeHidden(['enterprise_id','updated_at','created_at']);
                        $data[$key]['addresses'] = $addresses;
                    }

                    if ($input['phones'])
                    {
                        $phones = EnterprisePhone::where('enterprise_id', $enterprise->id)->get();
                        $phones->makeHidden(['enterprise_id','updated_at','created_at']);
                        $data[$key]['phones'] = $phones;
                    }

                    if ($input['emails'])
                    {
                        $emails = EnterpriseEmail::where('enterprise_id', $enterprise->id)->get();
                        $emails->makeHidden(['enterprise_id','updated_at','created_at']);
                        $data[$key]['emails'] = $emails;
                    }                    

                    if ($input['certificates'])
                    {
                        $certificates = Certificate::where('enterprise_id', $enterprise->id)
                        ->orderBy('valid_to', 'desc')->get();
                        $certificates->makeHidden(['enterprise_id','updated_at','created_at']);
                        $data[$key]['certificates'] = $certificates;
                    }

                    if ($input['additional'])
                    {
                        $additional = EnterpriseAdditional::where('enterprise_id', $enterprise->id)->first();
                        $data[$key]['additional'] = $additional;
                    }

                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'total-found', ['total' => $data->count()]),
                        'data'    => $data->toArray()
                    ]
                );

            break;
        }
    }

    public function disable(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_id' => ['required', 'exists:enterprise,id'],
                    'user_id'       => ['required', 'exists:user,id'],
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

                UserEnterprise::where('enterprise_id', $input['enterprise_id'])
                ->where('user_id', $input['user_id'])
                ->update(
                    [
                        'is_active'     => (bool) false
                    ]
                );
                $enterprise = Enterprise::find($input['enterprise_id']);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'deactivated'),
                        'data'    => $enterprise->getAttributes()
                    ]
                );

            break;
        }
    }

    public function enable(Request $request) {
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
                    'enterprise_id' => ['required', 'exists:enterprise,id'],
                    'user_id'       => ['required', 'exists:user,id'],
                ];

                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                UserEnterprise::where('enterprise_id', $input['enterprise_id'])
                ->where('user_id', $input['user_id'])
                ->update(
                    [
                        'is_active'     => (bool) true
                    ]
                );

                $enterprise = Enterprise::find($input['enterprise_id']);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'activated'),
                        'data'    => $enterprise->getAttributes()
                    ]
                );

            break;
        }
    }

    public function check(Request $request) {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 2017-03-02
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'       => ['required', 'numeric'],
                    'enterprise_id' => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $enterprise = Enterprise::find($input['enterprise_id']);

                if ($enterprise == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message("user", 'not-found')
                        ]
                    );
                }

                $data = UserEnterprise::where('user_id', $input['user_id'])
                ->where('enterprise_id', $input['enterprise_id'])
                ->first();

                if ($data == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-linked')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found')
                    ]
                );

            break;
            /**
             * @author  Romualdo Bugai
             * @date    2017-04-18
             * @return  void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_id' => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $enterprise = Enterprise::find($input['enterprise_id']);

                if ($enterprise == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                $data = UserEnterprise::where('enterprise_id', $input['enterprise_id'])
                ->where('is_active', true)
                ->first();

                if ($data == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-active')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found')
                    ]
                );

            break;
        }
    }

}
