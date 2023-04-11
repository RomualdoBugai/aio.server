<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\App as App;
use App\Models\Plan as Plan;
use App\Models\UserPlan as UserPlan;

class UserPlanService extends Api
{

    protected static $controller = 'user-plan';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 12/06/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'plan_id'           => ['required', 'numeric'],
                    'user_id'           => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $plan = Plan::find($input['plan_id']);

                if ($plan == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('plan', 'not-found'),
                    ]);
                }

                $app = $input['app'];

                $userPlan = new UserPlan;
                $userPlan = $userPlan::create(
                    [
                        'plan_id'           => $plan->id,
                        'user_id'           => $user->id,
                        'app_id'            => $app->id
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created')
                    ]
                );

            break;

        }
    }

    public function one(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 04/07/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $userPlan = UserPlan::where('user_id', $input['user_id'])
                ->first();

                if ($userPlan == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $userPlan->getAttributes()
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 04/07/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $app = $input['app'];

                $userPlan = UserPlan::
                select(
                    'user_plan.plan_id',
                    'user_plan.created_at',
                    'user_plan.updated_at',
                    'plan.name',
                    'plan.upload_limit',
                    'plan.user_limit',
                    'plan.enterprise_limit',
                    'plan.price'
                )
                ->where('user_plan.user_id', $input['user_id'])
                ->where('user_plan.app_id', $app->id)
                ->join('plan', 'plan.id', '=', 'user_plan.plan_id')
                ->first();

                if ($userPlan == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $userPlan->getAttributes()
                    ]
                );

            break;
        }
    }

    public function update(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 21/07/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'plan_id'           => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $app = $input['app'];

                $userPlan = UserPlan::where('id', $input['user_id'])
                    ->where('id', $input['user_id'])
                    ->first();

                if ($userPlan == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    'plan_id'      => (int) $input['plan_id']
                );

                $userPlan = UserPlan::where('id', $userPlan->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;

        }
    }
}
