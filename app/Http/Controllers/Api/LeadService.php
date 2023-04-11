<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Lead           as Lead;

class LeadService extends Api
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
                    'name'          => ['required', 'max:96'],
                    'phone'         => ['required', 'max:96'],
                    'email'         => ['required', 'email', 'max:96'],
                    'description'   => ['required', 'max:255'],
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

                $lead = Lead::create(
                    [
                        'name'          => (string) trim(strtolower($input['name'])),
                        'phone'         => (string) preg_replace("/[^0-9]/", "", $input['phone']),
                        'email'         => (string) trim(strtolower($input['email'])),
                        'is_active'     => (bool)   true,
                        'description'   => (string) trim($input['description']),
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('lead', 'created'),
                        'data'    => $lead->getAttributes()
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

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $lead = Lead::find($input['id']);

                if ($lead == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('lead', 'not-found')
                        ]
                    );
                }

                if ($lead->is_active == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('lead', 'no-disable')
                        ]
                    );   
                }

                $lead->update(
                    [
                        'is_active' => 0
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('lead', 'deactivated')
                    ]
                );

            break;
        }
    }

    public function enable(Request $request)
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
                    'id'            => ['required'],
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

                $lead = Lead::find($input['id']);

                if ($lead == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('lead', 'not-found')
                        ]
                    );
                }

                if ($lead->is_active == 1)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('lead', 'no-enable')
                        ]
                    );   
                }

                $lead->update(
                    [
                        'is_active' => 1
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('lead', 'activated')
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

                if (!isset($input['id']))
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('lead', 'empty-id')
                        ]
                    );
                }

                # define rules
                $rules = [
                    'id'            => ['required'],
                    'name'          => ['required', 'max:96'],
                    'phone'         => ['required', 'max:96'],
                    'email'         => ['required', 'email', 'max:96'],
                    'description'   => ['required', 'max:255'],
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

                $lead = Lead::find($input['id']);

                if ($lead == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('lead', 'not-found')
                        ]
                    );
                }

                $lead = $lead->update(
                    [
                        'name'          => (string) trim(strtolower($input['name'])),
                        'phone'         => (string) preg_replace("/[^0-9]/", "", $input['phone']),
                        'email'         => (string) trim(strtolower($input['email'])),
                        'description'   => (string) trim($input['description']),
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('lead', 'updated')
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
                    'id'            => ['required'],
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

                $lead = Lead::find($input['id']);

                if ($lead == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('lead', 'not-found')
                        ]
                    );
                }

                $data['lead'] = $lead->getAttributes();

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('lead', 'found'),
                        'data'    => $data
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
                    'limit'     => ['int'],
                    'offset'    => ['int'],
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

                $lead = Lead::get();

                if ($lead->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('lead', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('lead', 'total-found', ['total' => $lead->count()]),
                        'data'    => $lead
                    ]
                );

            break;
        }
    }

}
