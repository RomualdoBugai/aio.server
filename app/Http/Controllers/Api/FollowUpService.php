<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\FollowUp\FollowUp as FollowUp;
use App\Models\FollowUp\EnterpriseFollowUp as EnterpriseFollowUp;

class FollowUpService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version)
        {
            /**
             * create follow up
             *
             * @author  William Novak
             * @date    2016-12-16
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'description'   => ['required'],
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

                $follow_up = FollowUp::create(
                    [
                        'description'   => (string) $input['description'],
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('follow-up', 'created'),
                        'data'    => ['follow_up' => $follow_up->getAttributes()]
                    ]
                );

            break;

            /**
             * create follow up
             *
             * @author  William Novak
             * @date    2017-02-15
             * @return  object json
             * @version 1.0
             */
            case '1.1':

                $input = $request->input();

                # define rules
                $rules = [
                    'description'   => ['required'],
                    'for'           => ['required', 'in:enterprise'],
                    'controller_id' => ['required', 'numeric'],
                    'user_id'       => ['required', 'numeric']
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

                if ($user == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                switch ($input['for']) {

                    # enterprise
                    case 'enterprise':

                        $model      = \App\Models\Enterprise::class;
                        $enterprise = $model::find($input['controller_id']);

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

                    break;
                }

                $followUp = FollowUp::create(
                    [
                        'description'   => (string) $input['description'],
                        'user_id'       => (int)    $input['user_id']
                    ]
                );

                switch ($input['for']) {

                    # enterprise
                    case 'enterprise':

                        $enterpriseFollowUp = EnterpriseFollowUp::create(
                            [
                                'enterprise_id' => $enterprise->id,
                                'follow_up_id'  => $followUp->id
                            ]
                        );

                        return response()
                        ->json(
                            [
                                'status'  => true,
                                'message' => message('follow-up', 'created'),
                                'data'    => ['follow_up' => $followUp->getAttributes()]
                            ]
                        );

                    break;
                }



            break;

        }
    }

    public function one(Request $request)
    {
        switch ($request->version)
        {
            /**
             * create follow_up
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

                $follow_up = FollowUp::find($input['id']);

                if ($follow_up == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('follow-up', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('follow-up', 'found'),
                        'data'    => ['follow_up' => $follow_up->getAttributes()]
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
             * update follow_up
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
                    'description'   => ['required'],
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

                $follow_up = FollowUp::find($input['id']);

                if ($follow_up == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('follow-up', 'not-found')
                        ]
                    );
                }

                $follow_up->update(
                    [
                        'description'   => (string) $input['description'],
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('follow-up', 'updated')
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
             * create follow up
             *
             * @author  William Novak
             * @date    2016-12-16
             * @return  object json
             * @version 1.1
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'for'           => ['required', 'in:enterprise'],
                    'id'            => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                switch ($input['for']) {

                    # enterprise
                    case 'enterprise':

                        $model      = \App\Models\Enterprise::class;
                        $enterprise = $model::find($input['id']);

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

                    break;
                }

                switch ($input['for']) {

                    # enterprise
                    case 'enterprise':

                        $enterpriseFollowUp = EnterpriseFollowUp::with('followUp')
                        ->where('enterprise_id', $enterprise->id)
                        ->get();

                        if ($enterpriseFollowUp->count() == 0)
                        {
                            return response()
                            ->json(
                                [
                                    'status'  => false,
                                    'message' => message('follow-up', 'not-found')
                                ]
                            );
                        }

                        $result = [];

                        foreach($enterpriseFollowUp->toArray() as $followUp)
                        {

                            $user       = User::find($followUp['follow_up']['user_id']);
                            $result[]   = [
                                'created_at'    => $followUp['follow_up']['created_at'],
                                'updated_at'    => $followUp['follow_up']['updated_at'],
                                'description'   => $followUp['follow_up']['description'],
                                'user'      => [
                                    'id'    => $user->id,
                                    'name'  => $user->name
                                ],
                            ];
                        }

                        return response()
                        ->json(
                            [
                                'status'  => true,
                                'message' => message('follow-up', 'found'),
                                'data'    => ['enterprise_follow_up' => $result]
                            ]
                        );

                    break;
                }

            break;

        }
    }

}
