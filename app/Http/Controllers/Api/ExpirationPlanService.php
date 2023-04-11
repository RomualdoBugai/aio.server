<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;
use App\Models\App as App;
use App\Models\Plan as Plan;
use App\Models\Payment\Order as Order;
use App\Models\Payment\OrderItem as OrderItem;
use App\Models\Payment\OrderStatus as OrderStatus;
use App\Models\ExpirationPlan as ExpirationPlan;
use App\Models\PaymentMethod as PaymentMethod;

use Carbon\Carbon;


class ExpirationPlanService extends Api
{
    protected static $controller = 'expiration-plan';

    # days end
    public function dayEnd($dateStart, $dateEndFinal){

        $dateStart    = new Carbon($dateStart);
        $dateEndFinal      = new Carbon($dateEndFinal);      

        $days = $dateStart->diffInDays($dateEndFinal); 
        
        return $days;

    }

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 28/06/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'start_date'        => ['required', 'date:Y-m-d'],
                    'end_date'          => ['required', 'date:Y-m-d']
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

                $check = ExpirationPlan::where('user_id', $input['user_id'])
                    ->where('is_active',(bool) true)
                    ->first();

                if ($check != null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'already-exists'),
                    ]);
                }

                $expirationPlan = new ExpirationPlan;
                $expirationPlan = $expirationPlan::create(
                    [
                        'user_id'           => $user->id,
                        'start_date'        => $input['start_date'],
                        'end_date'          => $input['end_date']                      
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 19/07/2017
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'start_date'        => ['required', 'date:Y-m-d'],
                    'end_date'          => ['required', 'date:Y-m-d']
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

                $app    = $input['app'];

                $check = ExpirationPlan::where('user_id', $input['user_id'])
                    ->where('app_id', $app->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($check != null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'already-exists'),
                    ]);
                }

                $expirationPlan = new ExpirationPlan;
                $expirationPlan = $expirationPlan::create(
                    [
                        'user_id'           => $user->id,
                        'app_id'            => $app->id,
                        'start_date'        => $input['start_date'],
                        'end_date'          => $input['end_date']                      
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

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 28/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'     => ['required', 'numeric'],
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $expirationPlan = ExpirationPlan::where('user_id', $user->id)
                    ->get();

                if ( $expirationPlan->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $expirationPlan->toArray()
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
             * @date 28/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'           => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $expirationPlan = ExpirationPlan::where('id', $input['id'])
                ->first();

                if ($expirationPlan == null) {
                    return response()
                    ->json(
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
                        'data'    => $expirationPlan->getAttributes()
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 02/07/2017
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $app    = $input['app'];

                $expirationPlan = ExpirationPlan::where('user_id', $user->id)
                    ->where('app_id', $app->id)
                    ->first();

                if ($expirationPlan == null) {
                    return response()
                    ->json(
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
                        'data'    => $expirationPlan->getAttributes()
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
             * @date 29/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'month'             => ['required', 'numeric']
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $expirationPlan = ExpirationPlan::where('user_id', $user->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($expirationPlan == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    'end_date'      => date('Y-m-d', strtotime("+".($input['month']*30)." days", strtotime($expirationPlan->end_date)))
                );

                $expirationPlan = ExpirationPlan::where('id', $expirationPlan->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 19/07/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'month'             => ['required', 'numeric']
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $app    = $input['app'];

                $expirationPlan = ExpirationPlan::where('user_id', $user->id)
                    ->where('app_id', $app->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($expirationPlan == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    'end_date'      => date('Y-m-d', strtotime("+".($input['month']*30)." days", strtotime($expirationPlan->end_date)))
                );

                $expirationPlan = ExpirationPlan::where('id', $expirationPlan->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 09/08/2017
             * @return void
             */
            case '1.2':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'day'               => ['required', 'numeric']
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $app    = $input['app'];

                $expirationPlan = ExpirationPlan::where('user_id', $user->id)
                    ->where('app_id', $app->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($expirationPlan == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    'end_date'      => date('Y-m-d', strtotime("+".($input['day'])." days", strtotime($expirationPlan->end_date)))
                );

                $expirationPlan = ExpirationPlan::where('id', $expirationPlan->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;
        }
    }

    public function check(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 28/06/2017
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }
 
                $check = ExpirationPlan::where('user_id', $user->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($check == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                if(strtotime(date('Y-m-d')) <= strtotime($check->end_date)) {
                    return response()->json([
                        'status'  => true,
                        'message' => message(self::$controller, 'authorized'),
                    ]);
                }

                return response()->json([
                    'status'  => false,
                    'message' => message(self::$controller, 'not-authorized'),
                ]);

            break;
            /**
             * @author Romualdo Bugai
             * @date 28/06/2017
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $check = ExpirationPlan::where('user_id', $user->id)
                ->where('is_active',(bool) true)
                ->first();

                if ($check == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                if(strtotime(date('Y-m-d')) <= strtotime($check->end_date)) {
                    return response()->json([
                        'status'  => true,
                        'message' => message(self::$controller, 'authorized'),
                        'days'    => $this->dayEnd(date('Y-m-d'), $check->end_date)
                    ]);
                }

                return response()->json([
                    'status'  => false,
                    'message' => message(self::$controller, 'not-authorized'),
                    'days'    => $this->dayEnd(date('Y-m-d'), $check->end_date)
                ]);

            break;
            /**
             * @author Romualdo Bugai
             * @date 28/06/2017
             * @return void
             */
            case '1.2':

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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $app    = $input['app'];

                $check = ExpirationPlan::where('user_id', $user->id)
                    ->where('app_id', $app->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($check == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                if(strtotime(date('Y-m-d')) <= strtotime($check->end_date)) {
                    return response()->json([
                        'status'  => true,
                        'message' => message(self::$controller, 'authorized'),
                        'days'    => $this->dayEnd(date('Y-m-d'), $check->end_date)
                    ]);
                }

                return response()->json([
                    'status'  => false,
                    'message' => message(self::$controller, 'not-authorized'),
                    'days'    => $this->dayEnd(date('Y-m-d'), $check->end_date)
                ]);

            break;
            /**
             * @author Romualdo Bugai
             * @date 28/06/2017
             * @return void
             */
            case '1.3':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'name_app'          => ['required']
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $app = App::where('name', $input['name_app'])
                    ->first();

                $check = ExpirationPlan::where('user_id', $user->id)
                    ->where('app_id', $app->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($check == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                if(strtotime(date('Y-m-d')) <= strtotime($check->end_date)) {
                    return response()->json([
                        'status'  => true,
                        'message' => message(self::$controller, 'authorized'),
                        'days'    => $this->dayEnd(date('Y-m-d'), $check->end_date)
                    ]);
                }

                return response()->json([
                    'status'  => false,
                    'message' => message(self::$controller, 'not-authorized'),
                    'days'    => $this->dayEnd(date('Y-m-d'), $check->end_date)
                ]);

            break;
        }
    }

    public function close(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 29/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'           => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $expirationPlan = ExpirationPlan::where('id', $input['id'])
                    ->where('is_active',(bool) true)
                    ->first();

                if ($expirationPlan == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    'is_active'      => (bool) false
                );

                $expirationPlan = ExpirationPlan::where('id', $expirationPlan->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 29/06/2017
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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $expirationPlan = ExpirationPlan::where('user_id', $user->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($expirationPlan == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    'is_active'      => (bool) false
                );

                $expirationPlan = ExpirationPlan::where('id', $expirationPlan->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 19/07/2017
             * @return void
             */
            case '1.2':

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
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $app    = $input['app'];

                $expirationPlan = ExpirationPlan::where('user_id', $user->id)
                    ->where('app_id', $app->id)
                    ->where('is_active',(bool) true)
                    ->first();

                if ($expirationPlan == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    'is_active'      => (bool) false
                );

                $expirationPlan = ExpirationPlan::where('id', $expirationPlan->id)->update($data);

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
