<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Schedule\Scheduling as Scheduling;

class SchedulingService extends Api
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
                    'title'         => ['required', 'max:96'],
                    'description'   => ['required'],
                    'start_at'      => ['required', 'date:Y-m-d H:i'],
                    'end_at'        => ['required', 'date:Y-m-d H:i'],
                    'is_public'     => ['required', 'boolean'],
                    'coordinates'   => ['required']
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

                $scheduling = Scheduling::create(
                    [
                        'title'         => (string) $input['title'],
                        'description'   => (string) $input['description'],
                        'is_public'     => (bool)   $input['is_public'],
                        'start_at'      => (string) $input['start_at'],
                        'end_at'        => (string) $input['end_at'],
                        'coordinates'   => (string) $input['coordinates'],
                        'is_active'     => (bool)   true
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'created'),
                        'data'    => [
                            'scheduling' => $scheduling->getAttributes()
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
                    'id'         => ['required', 'numeric'],
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

                $scheduling = Scheduling::find($input['id']);

                if ($scheduling == null)
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
                        'message' => message('scheduling', 'found'),
                        'data'    => [
                            'scheduling' => $scheduling->getAttributes()
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
             * update scheduling
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
                    'id'            => ['required'],
                    'title'         => ['required', 'max:96'],
                    'description'   => ['required'],
                    'start_at'      => ['required', 'date:Y-m-d H:i'],
                    'end_at'        => ['required', 'date:Y-m-d H:i'],
                    'is_public'     => ['required', 'boolean'],
                    'coordinates'   => ['required']
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

                $scheduling = Scheduling::find($input['id']);

                if ($scheduling == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                $scheduling->update(
                    [
                        'title'         => (string) $input['title'],
                        'description'   => (string) $input['description'],
                        'is_public'     => (bool)   $input['is_public'],
                        'start_at'      => (string) $input['start_at'],
                        'end_at'        => (string) $input['end_at'],
                        'coordinates'   => (string) $input['coordinates']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('scheduling', 'updated')
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

                $scheduling = Scheduling::find($input['id']);

                if ($scheduling == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($scheduling->is_active == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'no-disable')
                        ]
                    );
                }

                $scheduling->update(
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

                $scheduling = Scheduling::find($input['id']);

                if ($scheduling == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'not-found')
                        ]
                    );
                }

                if ($scheduling->is_active == 1)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('scheduling', 'no-enable')
                        ]
                    );
                }

                $scheduling->update(
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
             * @date    2016-12-04
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'start_at'  => ['required', 'date:Y-m-d H:i'],
                    'end_at'    => ['required', 'date:Y-m-d H:i'],
                    'count'     => ['required', 'boolean'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $scheduling = Scheduling::whereBetween('start_at', [$input['start_at'], $input['end_at']]);

                if ($input['count'] == false) {

                    $scheduling = $scheduling->get();

                    if ($scheduling->count() == 0) {
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
                            'message' => message('scheduling', 'total-found', ['total' => $scheduling->count() ]),
                            'data'    => [
                                'scheduling' => $scheduling->toArray()
                            ]
                        ]
                    );
                }

                $scheduling = (int) $scheduling->count();

                return response()
                ->json(
                    [
                        'status'    => true,
                        'message'   => message('scheduling', 'total-found', ['total' => $scheduling ]),
                        'data'      => ['count' => $scheduling]
                    ]
                );

            break;
        }
    }


}
