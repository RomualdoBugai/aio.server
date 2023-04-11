<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Follow\UserEnterpriseFollow as UserEnterpriseFollow;
use App\Models\Enterprise as Enterprise;
use App\Models\User as User;

class UserEnterpriseFollowService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version)
        {
            /**
             * create scheduling
             *
             * @author  William Novak
             * @date    2017-02-17
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'           => ['required'],
                    'enterprise_id'     => ['required'],
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

                if ($enterprise == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
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

                $check = UserEnterpriseFollow::check($input['enterprise_id'], $input['user_id']);

                if ($check == true)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('follow', 'exists')
                        ]
                    );
                }

                $userEnterpriseFollow = UserEnterpriseFollow::create(
                    [
                        'user_id'       => (int) $input['user_id'],
                        'enterprise_id' => (int) $input['enterprise_id']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('follow', 'created'),
                        'data'    => [
                            'user_enterprise_follow' => $userEnterpriseFollow->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

    public function check(Request $request)
    {
        switch ($request->version)
        {
            /**
             * create scheduling
             *
             * @author  William Novak
             * @date    2017-02-17
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'           => ['required'],
                    'enterprise_id'     => ['required'],
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

                if ($enterprise == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
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

                $check = UserEnterpriseFollow::check($input['enterprise_id'], $input['user_id']);

                if ($check == true)
                {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message('follow', 'exists')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => false,
                        'message' => message('follow', 'not-exists')
                    ]
                );

            break;
        }
    }

    public function delete(Request $request)
    {
        switch ($request->version)
        {
            /**
             * create scheduling
             *
             * @author  William Novak
             * @date    2017-02-17
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'           => ['required'],
                    'enterprise_id'     => ['required'],
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

                if ($enterprise == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
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

                $check = UserEnterpriseFollow::check($input['enterprise_id'], $input['user_id']);

                if ($check == false)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('follow', 'not-exists')
                        ]
                    );
                }

                $model = UserEnterpriseFollow::where('enterprise_id', $input['enterprise_id'])
                ->where('user_id', $input['user_id'])
                ->first();

                UserEnterpriseFollow::destroy($model->id);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('follow', 'deleted')
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
             * @date    2017-02-20
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'           => ['required']
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

                $result = UserEnterpriseFollow::with('enterprise')
                ->where('user_id', $input['user_id'])
                ->get();

                if ($result->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('follow', 'not-exists')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('follow', 'found'),
                        'data'    => [
                            'user_enterprise_follow' => $result->toArray()
                        ]
                    ]
                );

            break;
        }
    }

}
