<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise       as Enterprise;
use App\Models\User             as User;
use App\Models\Log\EnterpriseLog    as EnterpriseLog;

class EnterpriseLogService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 2017-02-19
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'enterprise_id' => ['required'],
                    'message'       => ['required', 'max:224'],
                    'table'         => ['required', 'max:52'],
                    'table_id'      => ['required', 'numeric'],
                    'app_id'        => ['required', 'numeric'],
                    'user_id'       => ['required', 'numeric'],
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

                $app = $input['app'];

                $enterpriseLog = EnterpriseLog::create(
                    [
                        'enterprise_id' => (int) $enterprise->id,
                        'message'       => (string) $input['message'],
                        'table'         => (string) strtolower($input['table']),
                        'table_id'      => (int) $input['message'],
                        'user_id'       => (int) $user->id,
                        'app_id'        => (int) $app->id,
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('log', 'created'),
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

                $enterpriseLog = EnterpriseLog::where('enterprise_id', $input['enterprise_id'])->get();

                if ($enterpriseLog->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise-log', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'    => true,
                        'message'   => message('log', 'total-found', ['total' => $enterpriseLog->count()]),
                        'data'      => [
                            'enterprise_log' => $enterpriseLog->toArray()
                        ]
                    ]
                );

            break;
        }
    }

}
