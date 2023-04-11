<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Schedule\Scheduling as Scheduling;
use App\Models\Schedule\SchedulingEnterprise as SchedulingEnterprise;
use App\Models\Schedule\SchedulingUser as SchedulingUser;
use App\Models\Enterprise;

class SchedulingEnterpriseService extends Api {

    public function create(Request $request) {
        switch ($request->version) {
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
                    'scheduling_id' => ['required', 'exists:scheduling,id'],
                    'enterprise_id'     => ['required', 'exists:enterprise,id'],
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

                $check = SchedulingEnterprise::where('scheduling_id', $input['scheduling_id'])
                ->where('enterprise_id', $input['enterprise_id'])->first();

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

                $schedulingEnterprise = SchedulingEnterprise::create(
                    [
                        'scheduling_id' => (int) $input['scheduling_id'],
                        'enterprise_id'     => (int) $input['enterprise_id']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'created'),
                        'data'    => $schedulingEnterprise->getAttributes()
                    ]
                );

            break;
        }
    }

    public function disable(Request $request) {
        switch ($request->version) {
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
                    'enterprise_id' => ['required', 'exists:enterprise,id'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $schedulingEnterprise = SchedulingEnterprise::where('scheduling_id', $input['scheduling_id'])
                ->where('enterprise_id', $input['enterprise_id'])->first();

                if ($schedulingEnterprise == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($schedulingEnterprise->is_active == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'no-disable')
                        ]
                    );
                }

                $schedulingEnterprise->update(
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

    public function enable(Request $request) {
        switch ($request->version) {
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
                    'enterprise_id' => ['required', 'exists:enterprise,id'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $schedulingEnterprise = SchedulingEnterprise::where('scheduling_id', $input['scheduling_id'])
                ->where('enterprise_id', $input['enterprise_id'])->first();

                if ($schedulingEnterprise == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($schedulingEnterprise->is_active == 1) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'no-enable')
                        ]
                    );
                }

                $schedulingEnterprise->update(
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

    public function get(Request $request) {
        switch ($request->version) {
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
                    'enterprise_id'     => ['required'],
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
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                $schedulingEnterprise = SchedulingEnterprise::with('scheduling')
                ->where('enterprise_id', $input['enterprise_id'])
                ->get();

                if ($schedulingEnterprise->count() == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                $result = [];

                foreach($schedulingEnterprise->toArray() as $scheduling) {

                    $schedulingUser       = SchedulingUser::with('user')->where('scheduling_id', $scheduling['scheduling']['id'])->get();

                    $schedulingUser->makeHidden(['created_at', 'updated_at', 'user_id', 'scheduling_id', 'is_active']);

                    $result[]   = [
                        'scheduling' => [
                            'created_at'    => $scheduling['scheduling']['created_at'],
                            'updated_at'    => $scheduling['scheduling']['updated_at'],
                            'title'         => $scheduling['scheduling']['title'],
                            'description'   => $scheduling['scheduling']['description'],
                            'coordinates'   => $scheduling['scheduling']['coordinates'],
                            'is_public'     => $scheduling['scheduling']['is_public']
                        ],
                        'users'             => $schedulingUser
                    ];
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'total-found', ['total' => $schedulingEnterprise->count() ]),
                        'data'    => [
                            'enterprise_scheduling' => $result
                        ]
                    ]
                );

            break;
        }
    }


}
