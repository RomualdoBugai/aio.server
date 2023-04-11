<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Schedule\Scheduling as Scheduling;
use App\Models\Schedule\SchedulingUser as SchedulingUser;
use App\Models\User as User;

class SchedulingUserService extends Api
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
                    'scheduling_id' => ['required', 'exists:scheduling,id'],
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

                $check = SchedulingUser::where('scheduling_id', $input['scheduling_id'])
                ->where('user_id', $input['user_id'])->first();

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

                $schedulingUser = SchedulingUser::create(
                    [
                        'scheduling_id' => (int) $input['scheduling_id'],
                        'user_id'       => (int) $input['user_id']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'created'),
                        'data'    => $schedulingUser->getAttributes()
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

                $schedulingUser = SchedulingUser::where('scheduling_id', $input['scheduling_id'])
                ->where('user_id', $input['user_id'])->first();

                if ($schedulingUser == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($schedulingUser->is_active == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'no-disable')
                        ]
                    );
                }

                $schedulingUser->update(
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

                $schedulingUser = SchedulingUser::where('scheduling_id', $input['scheduling_id'])
                ->where('user_id', $input['user_id'])->first();

                if ($schedulingUser == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($schedulingUser->is_active == 1)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'no-enable')
                        ]
                    );
                }

                $schedulingUser->update(
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
        switch ($request->version) {
            /**
             * get scheduling
             *
             * @author  William Novak
             * @date    2016-12-06
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'start_at'      => ['required', 'date:Y-m-d H:i'],
                    'end_at'        => ['required', 'date:Y-m-d H:i'],
                    'user_id'       => ['required'],
                    'count'         => ['required', 'boolean'],
                    'splitByDay'    => ['required', 'boolean']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $scheduling = Scheduling::where('start_at', '>=', $input['start_at'])
                ->where('end_at', '<=', $input['end_at'])
                ->join( 'scheduling_user', 'scheduling_user.scheduling_id', '=', 'scheduling.id')
                ->where('scheduling_user.user_id', '=', $input['user_id'])
                ->get();

                if ($scheduling->count() == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($input['count'] == false && $input['splitByDay'] == false) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message('scheduling', 'total-found', ['total' => $scheduling->count() ]),
                            'data'    => $scheduling->toArray()
                        ]
                    );
                }

                if ($input['count'] == true && $input['splitByDay'] == false) {
                    return response()
                    ->json(
                        [
                            'status'    => true,
                            'message'   => message('scheduling', 'total-found', ['total' => (int) $scheduling->count() ]),
                            'data'      => ['count' => (int) $scheduling->count()]
                        ]
                    );
                }

                if ($input['count'] == true && $input['splitByDay'] == true) {

                    $data = [];
                    foreach($scheduling->toArray() as $day => $row) {
                        $day = Carbon::parse($row['start_at'])->format("Y-m-d");
                        $data[$day] = ( isset($data[$day]) ? $data[$day] + 1 : 1 );
                    }

                    return response()
                    ->json(
                        [
                            'status'    => true,
                            'message'   => message('scheduling', 'total-found', ['total' => (int) $scheduling->count() ]),
                            'data'      => $data
                        ]
                    );
                }

                if ($input['count'] == false && $input['splitByDay'] == true) {

                    $data = [];
                    foreach($scheduling->toArray() as $day => $row) {
                        $day = Carbon::parse($row['start_at'])->format("Y-m-d");
                        $data[$day][] = $row;
                    }

                    return response()
                    ->json(
                        [
                            'status'    => true,
                            'message'   => message('scheduling', 'total-found', ['total' => (int) $scheduling->count() ]),
                            'data'      => $data
                        ]
                    );
                }

            break;

            /**
             * get scheduling
             *
             * @author  William Novak
             * @date    2016-12-06
             * @return  object json
             * @version 1.1
             */
            case '1.1':

                $input = $request->input();

                # define rules
                $rules = [
                    'scheduling_id' => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $scheduling = Scheduling::find($input['scheduling_id']);

                if ($scheduling == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                $schedulingUser = SchedulingUser::with('user')
                ->where('scheduling_id', $input['scheduling_id'])
                ->join('scheduling', 'scheduling.id', '=', 'scheduling_user.scheduling_id')
                ->get();

                if ($schedulingUser->count() == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                $schedulingUser->makeHidden(['updated_at', 'coordinates', 'end_at', 'user_id']);

                return response()
                ->json(
                    [
                        'status'    => true,
                        'message'   => message('scheduling', 'found'),
                        'data'      => [
                            'scheduling_user' => $schedulingUser->toArray()
                        ]
                    ]
                );

            break;

            /**
             * get scheduling
             *
             * @author  William Novak
             * @date    2017-03-12
             * @return  object json
             * @version 1.0
             */
            case '1.2':

                $input = $request->input();

                # define rules
                $rules = [
                    'start_at'      => ['required', 'date:Y-m-d H:i'],
                    'end_at'        => ['required', 'date:Y-m-d H:i'],
                    'user_id'       => ['required'],
                    'count'         => ['required', 'boolean'],
                    'splitByDay'    => ['required', 'boolean'],
                    'person'        => ['required', 'boolean'],
                    'enterprise'    => ['required', 'boolean']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $scheduling = Scheduling::where('start_at', '>=', $input['start_at'])
                ->where('end_at', '<=', $input['end_at'])
                ->join( 'scheduling_user', 'scheduling_user.scheduling_id', '=', 'scheduling.id')
                ->where('scheduling_user.user_id', '=', $input['user_id'])
                ->get();

                if ($scheduling->count() == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($input['count'] == false && $input['splitByDay'] == false) {

                    $data = [];
                    foreach($scheduling as $key => $schedule) {

                        $data[$key] = $schedule->getAttributes();

                        if ($input['person'] == true) {
                            $person = \App\Models\Schedule\SchedulingPerson::where('scheduling_id', $schedule->id)
                                    ->with('person')
                                    ->get();
                            if ($person->count() > 0) {
                                $person->makeHidden(['id', 'created_at', 'updated_at', 'scheduling_id', 'person_id', 'is_active']);
                                $persons = [];
                                foreach($person as $p => $row) {
                                    $persons[] = $row['person'];
                                }
                                $data[$key]['person'] = $persons;
                            }
                        }

                        if ($input['enterprise'] == true) {
                            $enterprise = \App\Models\Schedule\SchedulingEnterprise::where('scheduling_id', $schedule->id)
                                    ->with('enterprise')
                                    ->get();
                            if ($enterprise->count() > 0) {
                                $enterprise->makeHidden(['id', 'created_at', 'updated_at', 'scheduling_id', 'enterprise_id', 'is_active']);
                                $enterprises = [];
                                foreach($enterprise as $e => $row) {
                                    $enterprises[] = $row['enterprise'];
                                }
                                $data[$key]['enterprise'] = $enterprises;
                            }
                        }

                    }

                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message('scheduling', 'total-found', ['total' => $scheduling->count() ]),
                            'data'    => $data
                        ]
                    );
                }

                if ($input['count'] == true && $input['splitByDay'] == false) {
                    return response()
                    ->json(
                        [
                            'status'    => true,
                            'message'   => message('scheduling', 'total-found', ['total' => (int) $scheduling->count() ]),
                            'data'      => ['count' => (int) $scheduling->count()]
                        ]
                    );
                }

                if ($input['count'] == true && $input['splitByDay'] == true) {

                    $data = [];
                    foreach($scheduling->toArray() as $day => $row) {
                        $day = Carbon::parse($row['start_at'])->format("Y-m-d");
                        $data[$day] = ( isset($data[$day]) ? $data[$day] + 1 : 1 );
                    }

                    return response()
                    ->json(
                        [
                            'status'    => true,
                            'message'   => message('scheduling', 'total-found', ['total' => (int) $scheduling->count() ]),
                            'data'      => $data
                        ]
                    );
                }

                if ($input['count'] == false && $input['splitByDay'] == true) {

                    $data = [];
                    foreach($scheduling->toArray() as $day => $row) {
                        $day = Carbon::parse($row['start_at'])->format("Y-m-d");
                        $data[$day][] = $row;
                    }

                    return response()
                    ->json(
                        [
                            'status'    => true,
                            'message'   => message('scheduling', 'total-found', ['total' => (int) $scheduling->count() ]),
                            'data'      => $data
                        ]
                    );
                }

            break;
        }
    }

    public function one(Request $request)
    {
        switch ($request->version)
        {
            /**
             * create scheduling
             *
             * @author  William Novak
             * @date    2016-12-04
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'               => ['required'],
                    'scheduling_id'         => ['required'],
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

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $scheduling = Scheduling::find($input['scheduling_id']);

                if ($scheduling == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                $check = SchedulingUser::check($scheduling->id, $user->id);

                if ($scheduling == false) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                $data = SchedulingUser::with('user')->where('scheduling_id', $scheduling->id)
                        ->where('user_id', $user->id)
                        ->first();

                return response()
                ->json(
                    [
                        'status'    => true,
                        'message'   => message('scheduling', 'found'),
                        'data'      => [
                            'scheduling_user' => $data->toArray()
                        ]
                    ]
                );

            break;
        }
    }


}
