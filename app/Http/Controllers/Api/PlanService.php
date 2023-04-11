<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\Plan as Plan;

class PlanService extends Api
{

    protected static $controller = 'plan';

    public function get(Request $request)
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
                    'is_active'     => ['required', 'array']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app = $input['app'];
                
                # obs: product code equal 0 is plan measure
                $plan = Plan::where('app_id', $app->id)
                    ->where('product_code', '!=', '0')
                    ->whereIn('is_active', $input['is_active'])
                    ->get();

                if ( $plan->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $plan->toArray()
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 04/09/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'is_active'     => ['required', 'array']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app = $input['app'];
                
                # obs: product code equal 0 is plan measure
                /*$plan = Plan::whereIn('is_active', $input['is_active'])
                    ->get();*/
                    
                $plan = Plan::
                    select(
                        'plan.*',
                        'app.name AS app_name'
                    )
                    ->whereIn('is_active', $input['is_active'])
                    ->join('app', 'app.id', '=', 'plan.app_id')
                    ->get();

                if ( $plan->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $plan->toArray()
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
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
             * @date 12/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'     => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $plan = Plan::where('id', $input['id'])
                ->first();

                if ($plan == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $plan->getAttributes()
                    ]
                );

            break;
        }
    }
}
