<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Schedule\Scheduling as Scheduling;
use App\Models\Schedule\SchedulingPerson as SchedulingPerson;

class SchedulingPersonService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version)
        {
            /**
             * create scheduling
             *
             * @author  William Novak
             * @date    04/12/2016
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'scheduling_id'     => ['required', 'exists:scheduling,id'],
                    'person_id'         => ['required', 'exists:person,id'],
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

                $check = SchedulingPerson::where('scheduling_id', $input['scheduling_id'])
                ->where('person_id', $input['person_id'])->first();

                if ($check != null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'exists')
                        ]
                    );
                }

                $schedulingPerson = SchedulingPerson::create(
                    [
                        'scheduling_id' => (int) $input['scheduling_id'],
                        'person_id'     => (int) $input['person_id']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'created'),
                        'data'    => $schedulingPerson->getAttributes()
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
             * @date 04/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'scheduling_id' => ['required', 'exists:scheduling,id'],
                    'person_id'     => ['required', 'exists:person,id'],
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

                $schedulingPerson = SchedulingPerson::where('scheduling_id', $input['scheduling_id'])
                ->where('person_id', $input['person_id'])->first();

                if ($schedulingPerson == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($schedulingPerson->is_active == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'no-disable')
                        ]
                    );
                }

                $schedulingPerson->update(
                    [
                        'is_active' => 0
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'deactivated')
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
             * @date 04/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'scheduling_id' => ['required', 'exists:scheduling,id'],
                    'person_id'     => ['required', 'exists:person,id'],
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

                $schedulingPerson = SchedulingPerson::where('scheduling_id', $input['scheduling_id'])
                ->where('person_id', $input['person_id'])->first();

                if ($schedulingPerson == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($schedulingPerson->is_active == 1)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'no-enable')
                        ]
                    );
                }

                $schedulingPerson->update(
                    [
                        'is_active' => 1
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'activated')
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
             * create scheduling
             *
             * @author  William Novak
             * @date    04/12/2016
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'person_id'     => ['required', 'exists:person,id'],
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

                $schedulingPerson = SchedulingPerson::with('scheduling')
                ->where('person_id', $input['person_id'])->get();

                if ($schedulingPerson->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'total-found', ['total' => $schedulingPerson->count() ]),
                        'data'    => $schedulingPerson->toArray()
                    ]
                );

            break;
        }
    }


}
