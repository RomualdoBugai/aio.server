<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise           as Enterprise;
use App\Models\EnterpriseAdditional as EnterpriseAdditional;

class EnterpriseAdditionalService extends Api
{

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
                    'enterprise_id'             => ['required', 'exists:enterprise,id'],
                    'encouraging_cultural'      => ['max:112'],
                    'municipal_registration'    => ['max:112'],
                    'estadual_registration'     => ['max:112'],
                    'tax_regime'                => ['max:112'],
                    'national_simple'           => ['max:112'],
                    'lot'                       => ['max:112'],
                    'note'                      => ['max:112'],
                    'operation_nature'          => ['max:112'],
                    'activity'                  => ['max:112'],
                    'logo'                      => ['string'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $check = EnterpriseAdditional::where('enterprise_id', $input['enterprise_id'])->first();

                if ($check != null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'exists')
                        ]
                    );
                }

                $data = $input;
                unset($data['app'], $data['token']);

                foreach($data as $field => $value) {
                    if (!array_key_exists($field, $rules)) {
                        unset($data[$field]);
                    } else {
                        $data[$field] = $input[$field];
                    }
                }

                if (count($data) == 0) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'no-parameter')
                        ]
                    );
                }

                $additional = EnterpriseAdditional::create($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'created')
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
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_id'             => ['required', 'exists:enterprise_additional,enterprise_id'],
                    'encouraging_cultural'      => ['max:112'],
                    'municipal_registration'    => ['max:112'],
                    'estadual_registration'     => ['max:112'],
                    'tax_regime'                => ['max:112'],
                    'national_simple'           => ['max:112'],
                    'lot'                       => ['max:112'],
                    'note'                      => ['max:112'],
                    'operation_nature'          => ['max:112'],
                    'activity'                  => ['max:112'],
                    'logo'                      => ['string'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $enterpriseAdditional = EnterpriseAdditional::where('enterprise_id', $input['enterprise_id'])->first();

                if ($enterpriseAdditional == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                $data = $input;
                unset($data['app'], $data['token']);

                foreach($data as $field => $value) {
                    if (!array_key_exists($field, $rules))
                    {
                        unset($data[$field]);
                    } else {
                        $data[$field] = $input[$field];
                    }
                }

                if (count($data) == 0) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'no-parameter')
                        ]
                    );
                }

                $enterpriseAdditional->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'updated')
                    ]
                );

            break;
        }
    }

}
