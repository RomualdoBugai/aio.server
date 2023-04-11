<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise       as Enterprise;
use App\Models\EnterprisePerson as EnterprisePerson;

class EnterprisePersonService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 2017-02-13
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_id' => ['required'],
                    'name'          => ['required', 'max:112'],
                    'description'   => ['max:112']
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
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                $enterprisePerson = EnterprisePerson::create(
                    [
                        'enterprise_id' => (int) $enterprise->id,
                        'name'          => (string) $input['name'],
                        'description'   => (string) ( isset($input['description']) ? strtolower(trim($input['description'])) : '' )
                    ]
                );

                $enterprisePerson = EnterprisePerson::find($enterprisePerson->id);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise-person', 'created'),
                        'data'    => [
                            'enterprise_person' => $enterprisePerson->getAttributes()
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
             * @date 2017-02-13
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_person_id' => ['required'],
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

                $enterprisePerson = EnterprisePerson::find($input['enterprise_person_id']);

                if ($enterprisePerson == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise-person', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'    => true,
                        'message'   => message('enterprise', 'exists'),
                        'data'      => [
                            'enterprise_person' => $enterprisePerson->getAttributes()
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
             * @date 2017-02-13
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_id' => ['required'],
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
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                $enterprisePerson = EnterprisePerson::where('enterprise_id', $input['enterprise_id'])->get();

                if ($enterprisePerson->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise-person', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'    => true,
                        'message'   => message('enterprise-person', 'total-found', ['total' => $enterprisePerson->count()]),
                        'data'      => [
                            'enterprise_person' => $enterprisePerson->toArray()
                        ]
                    ]
                );

            break;
        }
    }

}
