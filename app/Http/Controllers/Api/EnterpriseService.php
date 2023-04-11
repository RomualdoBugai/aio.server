<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise as Enterprise;
use App\Models\EnterprisePhone as EnterprisePhone;
use App\Models\EnterpriseEmail as EnterpriseEmail;
use App\Models\Certificate as Certificate;

class EnterpriseService extends Api
{

    protected static $controller = 'enterprise';

    public function create(Request $request) {
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
                    'name'          => ['required', 'string', 'max:96'],
                    'fantasy_name'  => ['required', 'string', 'max:96'],
                    'national_code' => ['required', 'string', 'max:16'],
                    'legal_nature'  => ['required', 'string', 'max:224'],
                    'is_matrix'     => ['required'],
                    'open_at'       => ['required', 'date'],
                    'last_update'   => ['required', 'date'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $data = Enterprise::where('national_code',$input['national_code'])
                ->where('status',(bool) true)
                ->first();

                if ($data != null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'already-exist')
                        ]
                    );
                }

                $enterprise = Enterprise::create(
                    [
                        'name'          => (string) trim(strtolower($input['name'])),
                        'fantasy_name'  => (string) trim(strtolower($input['fantasy_name'])),
                        'national_code' => (string) trim(strtolower($input['national_code'])),
                        'legal_nature'  => (string) trim(strtolower($input['legal_nature'])),
                        'is_matrix'     => (bool)   $input['is_matrix'],
                        'open_at'       => (string) $input['open_at'],
                        'last_update'   => (string) $input['last_update'],
                        'is_active'     => (bool)   true,
                        'status'        => (bool)   true,
                        'country_id'    => (int)    1
                    ]
                );

                $enterprise = Enterprise::find($enterprise->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created'),
                        'data'    => $enterprise->getAttributes()
                    ]
                );

            break;

            /**
             * @author William Novak
             * @return void
             * @version 1.1 2017-01-09
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'name'          => ['required', 'string', 'max:96'],
                    'fantasy_name'  => ['string', 'max:96'],
                    'national_code' => ['max:16', 'unique:enterprise,national_code'],
                    'country_id'    => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $create = [
                    'name'          => (string) trim(strtolower($input['name'])),
                    'fantasy_name'  => (string) ( isset($input['fantasy_name'])     ? trim(strtolower($input['fantasy_name']))  : null ),
                    'national_code' => (string) ( isset($input['national_code'])    ? trim($input['national_code'])             : null ),
                    'is_matrix'     => (bool)   ( isset($input['is_matrix'])        ? $input['is_matrix']                       : 1    ),
                    'is_active'     => (bool)   true,
                    'status'        => (bool)   true,
                    'country_id'    => (int)    $input['country_id'],
                    'legal_nature'  => '',
                    'open_at'       => '',
                    'last_update'   => ''
                ];

                if (!isset($input['national_code']) || $input['national_code'] == null)
                {
                    unset($create['national_code']);
                }

                $enterprise = Enterprise::create($create);

                $enterprise = Enterprise::find($enterprise->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'created'),
                        'data'    => [
                            'enterprise' => $enterprise->getAttributes()
                        ]
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
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'name'          => ['required', 'string', 'max:96'],
                    'fantasy_name'  => ['required', 'string', 'max:96'],
                    'national_code' => ['required', 'max:16', 'exists:enterprise,national_code'],
                    'legal_nature'  => ['string',   'max:224'],
                    'is_matrix'     => ['required'],
                    'open_at'       => ['required', 'date'],
                    'last_update'   => ['required', 'date'],
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
                    'name'          => (string) trim(strtolower($input['name'])),
                    'fantasy_name'  => (string) trim(strtolower($input['fantasy_name'])),
                    'legal_nature'  => (string) trim(strtolower($input['legal_nature'])),
                    'is_matrix'     => (bool)   $input['is_matrix'],
                    'open_at'       => (string) $input['open_at'],
                    'last_update'   => (string) $input['last_update'],
                    'is_active'     => (bool)   true,
                    'status'        => (bool)   true
                ];

                Enterprise::where('national_code', $input['national_code'])
                ->update($update);

                $enterprise = Enterprise::where('national_code', $input['national_code'])->first();

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, "updated"),
                        'data'    => $enterprise->getAttributes()
                    ]
                );

            break;
            /**
             * @author William Novak
             * @date 06/01/2017
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required'],
                    'name'          => ['required', 'string', 'max:96'],
                    'fantasy_name'  => ['string', 'max:96'],
                    'national_code' => ['max:16'],
                    'open_at'       => ['string', 'max:10'],
                    'legal_nature'  => ['string', 'max:224'],
                    'is_matrix'     => ['boolean'],
                    'open_at'       => ['date'],
                    'last_update'   => ['date'],
                ];

                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $enterprise = Enterprise::find($input['id']);
                if ($enterprise == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found'),
                        ]
                    );
                }

                $update = [
                    'name'          => (string) trim(strtolower($input['name'])),
                    'fantasy_name'  => (string) ( isset($input['fantasy_name'])     ? trim(strtolower($input['fantasy_name']))  : null ),
                    'national_code' => (string) ( isset($input['national_code'])    ? trim($input['national_code'])             : null ),
                    'is_matrix'     => (bool)   ( isset($input['is_matrix'])        ? $input['is_matrix']                       : 1    ),
                    'is_active'     => (bool)   true,
                    'status'        => (bool)   true,
                    'country_id'    => (int)    $input['country_id'],
                    'legal_nature'  => '',
                    'open_at'       => '',
                    'last_update'   => ''
                ];

                if (!isset($input['national_code']) || $input['national_code'] == null) {
                    unset($update['national_code']);
                }

                $enterprise = Enterprise::find($input['id']);
                $enterprise->update($update);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'updated'),
                        'data'    => [
                            'enterprise'  => $enterprise->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

    public function check(Request $request) {
        switch ($request->version) {
            /**
             * @author  Romualdo Bugai
             * @date    2017-04-18
             * @return  void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required', 'exists:enterprise,id'],
                    'national_code' => ['required', 'max:16', 'exists:enterprise,national_code'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $enterprise = Enterprise::find($input['id']);

                if ($enterprise == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message("enterprise", 'not-found')
                        ]
                    );
                }

                $data = Enterprise::where('id', $input['id'])
                ->where('national_code',$input['national_code'])
                ->where('status',(bool) true)
                ->first();

                if ($data == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'deleted')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'found')
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
             * @date 24/04/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required', 'exists:enterprise,id'],
                ];

                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

               $enterprise = Enterprise::where('id', $input['id'])
               ->where('status', (bool)   true)
               ->first();

                if ($enterprise == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "not-exist")
                        ]
                    );
                }

                $update = [
                    'status'        => (bool)   false
                ];

                Enterprise::where('id', $input['id'])
                ->update($update);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, "deleted"),
                        'data' => [
                            'enterprise'  => $enterprise->getAttributes(),
                        ]
                    ]
                );

            break;
            
        }
    }

    public function toActivate(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 24/04/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required', 'exists:enterprise,id'],
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
                    'status'        => (bool)   true
                ];

                Enterprise::where('id', $input['id'])
                ->update($update);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, "activated"),
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
                    'id' => ['required', 'exists:enterprise,id'],
                ];

                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                Enterprise::where('id', $input['id'])
                ->update(
                    [
                        'is_active'     => (bool) false
                    ]
                );

                $enterprise = Enterprise::find($input['id']);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'disabled'),
                        'data'    => ['enterprise' => $enterprise->getAttributes()]
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
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id' => ['required', 'exists:enterprise,id'],
                ];

                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                Enterprise::where('id', $input['id'])
                ->update(
                    [
                        'is_active'     => (bool) true
                    ]
                );

                $enterprise = Enterprise::find($input['id']);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'enabled'),
                        'data'    => ['enterprise' => $enterprise->getAttributes()]
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
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'national_code' => ['required', 'exists:enterprise,national_code'],
                    'addresses'     => ['required', 'boolean'],
                    'emails'        => ['required', 'boolean'],
                    'phones'        => ['required', 'boolean'],
                    'additional'    => ['required', 'boolean'],
                    'certificates'  => ['required', 'boolean'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $enterprise = Enterprise::where('national_code', $input['national_code'])
                ->where('status', (bool)   true)
                ->first();

                if ($enterprise == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found'),
                        ]
                    );
                }

                $data['enterprise'] = $enterprise->getAttributes();
                $data['enterprise']['country'] = $enterprise->countrie;

                if ($input['addresses']) {
                    $data['addresses'] = $enterprise->addresses;
                }

                if ($input['phones']) {
                    $data['phones'] = $enterprise->phones;
                }

                if ($input['emails']) {
                    $data['emails'] = $enterprise->emails;
                }

                if ($input['additional']) {
                    $data['additional'] = $enterprise->additional;
                }

                if ($input['certificates']) {
                    $data['certificates'] = $enterprise->certificates;
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'found'),
                        'data'    => $data
                    ]
                );

            break;

            /**
             * @author William Novak
             * @date 06/01/2017
             * @return void
             */
            case '1.1':
                $input = $request->input();

                # define rules
                $rules = [
                    'id'            => ['required'],
                    'addresses'     => ['required', 'boolean'],
                    'emails'        => ['required', 'boolean'],
                    'phones'        => ['required', 'boolean'],
                    'additional'    => ['required', 'boolean'],
                    'certificates'  => ['required', 'boolean'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $enterprise = Enterprise::find($input['id']);

                if ($enterprise == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found'),
                        ]
                    );
                }

                $data['enterprise']     = $enterprise->getAttributes();
                $data['enterprise']['country'] = $enterprise->countrie;

                if ($input['addresses']) {
                    $data['addresses']  = $enterprise->addresses;
                }

                if ($input['phones']) {
                    $data['phones']     = $enterprise->phones;
                }

                if ($input['emails']) {
                    $data['emails']     = $enterprise->emails;
                }

                if ($input['additional']) {
                    $data['additional'] = $enterprise->additional;
                }

                if ($input['certificates']) {
                    $data['certificates'] = $enterprise->certificates;
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'found'),
                        'data'    => $data
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
             * @return void
             * @version 1.0 2017-01-07
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'is_active'     => ['required', 'boolean'],
                    'count'         => ['required', 'boolean']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                if ($input['count'] == true) {
                    $enterprises = Enterprise::where('enterprise.is_active', (bool) $input['is_active'])
                    ->count();

                    return response()
                    ->json(
                        [
                            'status'  => ($enterprises > 0 ? true : false),
                            'message' => message('enterprise', 'total-found', ['total' => $enterprises]),
                            'data'    => [
                                'enterprise' => $enterprises
                            ]
                        ]
                    );
                }

                $enterprises = Enterprise::
                select(
                    'enterprise.*',
                    'country.code',
                    'country.name as country_name'
                )
                ->where('enterprise.is_active', (bool) $input['is_active'])
                ->join('country', 'country.id', '=', 'enterprise.country_id')
                ->get();


                return response()
                ->json(
                    [
                        'status'  => ($enterprises->count() > 0 ? true : false),
                        'message' => message('enterprise', 'total-found', ['total' => $enterprises->count()]),
                        'data'    => [
                            'enterprise' => $enterprises->toArray()
                        ]
                    ]
                );

            break;
        }
    }

}
