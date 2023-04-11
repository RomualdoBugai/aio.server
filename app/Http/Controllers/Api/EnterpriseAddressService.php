<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise as Enterprise;
use App\Models\EnterpriseAddress as EnterpriseAddress;

class EnterpriseAddressService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 24/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'national_code' => ['required', 'exists:enterprise,national_code'],
                    'street'        => ['required'],
                    'number'        => ['required'],
                    'district'      => ['required', 'max:96'],
                    'city'          => ['required', 'max:112'],
                    'state'         => ['required', 'max:2'],
                    'postal_code'   => ['required', 'alpha_num', 'max:8'],
                    'complement'    => ['required', 'max:48'],
                    'country_id'    => ['required'],
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

                $enterprise = Enterprise::where("national_code", $input['national_code'])->first();

                if ($enterprise == null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                $enterpriseAddress = EnterpriseAddress::create(
                    [
                        'enterprise_id' => (int)    $enterprise->id,
                        'street'        => (string) trim(strtolower($input['street'])),
                        'number'        => (string) trim($input['number']),
                        'district'      => (string) trim(strtolower($input['district'])),
                        'city'          => (string) trim(strtolower($input['city'])),
                        'state'         => (string) trim(strtolower($input['state'])),
                        'postal_code'   => (string) trim($input['postal_code']),
                        'complement'    => (string) trim($input['complement']),
                        'country_id'    => (int)    $input['country_id'],
                        'is_active'     => (bool)   true,
                        'default'       => (bool)   true,
                    ]
                );

                $enterpriseAddress = EnterpriseAddress::find($enterpriseAddress->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message("address", "created"),
                        'data'    => [
                            'enterprise_address' => $enterpriseAddress->getAttributes()
                        ]
                    ]
                );

            break;
            /**
             * @author William Novak
             * @date 24/10/2016
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_id' => ['required'],
                    'street'        => ['required'],
                    'number'        => ['required'],
                    'district'      => ['required', 'max:96'],
                    'city'          => ['required', 'max:112'],
                    'state'         => ['required', 'max:2'],
                    'postal_code'   => ['required', 'alpha_num', 'max:8'],
                    'complement'    => ['required', 'max:48'],
                    'country_id'    => ['required'],
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
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                $enterpriseAddress = EnterpriseAddress::create(
                    [
                        'enterprise_id' => (int)    $enterprise->id,
                        'street'        => (string) trim(strtolower($input['street'])),
                        'number'        => (string) trim($input['number']),
                        'district'      => (string) trim(strtolower($input['district'])),
                        'city'          => (string) trim(strtolower($input['city'])),
                        'state'         => (string) trim(strtolower($input['state'])),
                        'postal_code'   => (string) trim($input['postal_code']),
                        'complement'    => (string) trim($input['complement']),
                        'country_id'    => (int)    $input['country_id'],
                        'is_active'     => (bool)   true,
                        'default'       => (bool)   true,
                    ]
                );

                $enterpriseAddress = EnterpriseAddress::find($enterpriseAddress->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message("address", "created"),
                        'data'    => [
                            'enterprise_address' => $enterpriseAddress->getAttributes()
                        ]
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
             * @date 24/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'            => ['required', 'exists:enterprise_address,id'],
                    'street'        => ['required'],
                    'number'        => ['required'],
                    'district'      => ['required', 'max:96'],
                    'city'          => ['required', 'max:112'],
                    'state'         => ['required', 'max:2'],
                    'postal_code'   => ['required', 'alpha_num', 'max:8'],
                    'complement'    => ['required', 'max:48'],
                    'country_id'    => ['required'],
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

                EnterpriseAddress::where('id', $input['id'])->update(
                    [
                        'street'        => (string) trim(strtolower($input['street'])),
                        'number'        => (string) trim($input['number']),
                        'district'      => (string) trim(strtolower($input['district'])),
                        'city'          => (string) trim(strtolower($input['city'])),
                        'state'         => (string) trim(strtolower($input['state'])),
                        'postal_code'   => (string) trim($input['postal_code']),
                        'complement'    => (string) trim($input['complement']),
                        'country_id'    => (int)    $input['country_id'],
                        'is_active'     => (bool)   true,
                        'default'       => (bool)   true,
                    ]
                );

                $enterpriseAddress = EnterpriseAddress::find($input['id']);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message("address", "updated"),
                        'data'    => [
                            'enterprise_address' => $enterpriseAddress->getAttributes()
                        ]
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
             * @return void
             * @version 1.0 2017-01-07
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'enterprise_address_id'     => ['required'],
                ];

                # define messages
                $messages = [];

                # define nice names from atributes
                $niceNames = [
                    'enterprise_address_id'         => 'enterprise address'
                ];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages, $niceNames);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $data = EnterpriseAddress::find($input['enterprise_address_id']);

                if ($data == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('address', 'found'),
                        'data'    => [
                            'enterprise_address' => $data->getAttributes()
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
             * @return void
             * @version 1.0 2017-01-07
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'enterprise_id'     => ['required'],
                ];

                # define messages
                $messages = [];

                # define nice names from atributes
                $niceNames = [
                    'enterprise_id'         => 'enterprise'
                ];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages, $niceNames);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $enterprise = Enterprise::find($input['enterprise_id']);

                if ($enterprise == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                $data   = EnterpriseAddress::where('enterprise_id', $enterprise->id)->get();

                if ($data == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('address', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('address', 'total-found', ['total' => $data->count()]),
                        'data'    => [
                            'enterprise_address' => $data->toArray()
                        ]
                    ]
                );

            break;
        }
    }

}
