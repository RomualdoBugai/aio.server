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
use App\Models\PaymentMethod as PaymentMethod;

class OrderService extends Api
{
    protected static $controller = 'order';

    public function notify(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 19/06/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'          => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app            = $request->input('app');

                $order = Order::where('id', $input['id'])
                    ->first();

                if ($order == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $user = User::find($order->user_id);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $orderItem = OrderItem::where('order_id', $order->id)
                    ->get();


                if ( $orderItem->count() == 0 ) {
                    return response()->json(
                        [
                            'status'  => true,
                            'message' => message('order-item', 'not-found')
                        ]
                    );
                }

                $paymentMethod = PaymentMethod::where('id', $order->payment_method_id)
                    ->first();


                if ($paymentMethod == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('payment-method', 'not-found'),
                        ]
                    );
                }
                
                $title  = message('common', 'mail.order-created.title', ['name'  => ownName($user->name)]);
                $resume = json_decode($app->resume, true);

                $data = [
                    'user'          => $user,
                    'order'         => $order,
                    'orderItem'     => $orderItem,
                    'paymentMethod' => $paymentMethod,
                    'app'           => $app->name,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.order-create', $data, function($message) use (&$user, &$title){
                    $message->to($user->email, $user->name)->subject($title);
                });

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'email-send'),
                    ]
                );


            break;

        }
    }

    public function complete(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 19/06/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'          => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app            = $request->input('app');

                $order = Order::where('id', $input['id'])
                    ->first();

                if ($order == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $user = User::find($order->user_id);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $orderItem = OrderItem::where('order_id', $order->id)
                    ->get();


                if ( $orderItem->count() == 0 ) {
                    return response()->json(
                        [
                            'status'  => true,
                            'message' => message('order-item', 'not-found')
                        ]
                    );
                }

                $paymentMethod = PaymentMethod::where('id', $order->payment_method_id)
                    ->first();


                if ($paymentMethod == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('payment-method', 'not-found'),
                        ]
                    );
                }
                
                $title  = message('common', 'mail.order-complete.title', ['name'  => ownName($user->name)]);
                $resume = json_decode($app->resume, true);

                $data = [
                    'user'          => $user,
                    'order'         => $order,
                    'orderItem'     => $orderItem,
                    'paymentMethod' => $paymentMethod,
                    'app'           => $app->name,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.order-complete', $data, function($message) use (&$user, &$title){
                    $message->to($user->email, $user->name)->subject($title);
                });

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'email-send'),
                    ]
                );


            break;

        }
    }

    public function failed(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 23/06/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'          => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app            = $request->input('app');

                $order = Order::where('id', $input['id'])
                    ->first();

                if ($order == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $user = User::find($order->user_id);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $orderItem = OrderItem::where('order_id', $order->id)
                    ->get();


                if ( $orderItem->count() == 0 ) {
                    return response()->json(
                        [
                            'status'  => true,
                            'message' => message('order-item', 'not-found')
                        ]
                    );
                }

                $paymentMethod = PaymentMethod::where('id', $order->payment_method_id)
                    ->first();


                if ($paymentMethod == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('payment-method', 'not-found'),
                        ]
                    );
                }
                
                $title  = message('common', 'mail.order-failed.title', ['name'  => ownName($user->name)]);
                $resume = json_decode($app->resume, true);

                $data = [
                    'user'          => $user,
                    'order'         => $order,
                    'orderItem'     => $orderItem,
                    'paymentMethod' => $paymentMethod,
                    'app'           => $app->name,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.order-failed', $data, function($message) use (&$user, &$title){
                    $message->to($user->email, $user->name)->subject($title);
                });

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'email-send'),
                    ]
                );

            break;

        }
    }

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 13/06/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'code'              => ['required', 'max:48'],
                    'amount_total'      => ['required', 'numeric'],
                    'quantity_total'    => ['required', 'numeric'],
                    'json'              => ['required', 'json'],
                    'user_id'           => ['required', 'numeric'],
                    'payment_method_id' => ['required', 'numeric'],
                    'order_status_id'   => ['required', 'numeric'],
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

                $paymentMethod = PaymentMethod::find($input['payment_method_id']);

                if ($paymentMethod == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('payment-method', 'not-found'),
                    ]);
                }

                $orderStatus = OrderStatus::find($input['order_status_id']);

                if ($orderStatus == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('order-status', 'not-found'),
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

                $order = new Order;
                $order = $order::create(
                    [
                        'code'              => $input['code'],
                        'amount_total'      => $input['amount_total'],
                        'quantity_total'    => $input['quantity_total'],
                        'json'              => $input['json'],
                        'user_id'           => $user->id,
                        'payment_method_id' => $paymentMethod->id,                        
                        'order_status_id'   => $orderStatus->id,
                        'app_id'            => $app->id,
                        'plan_id'           => $plan->id                         
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created'),
                        'data'    => [
                            'order_id' => $order->id
                        ]
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
             * @date 13/06/2017
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

                if ($user == null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found'),
                        ]
                    );
                }

                $app = $input['app'];

                $userOrder = Order::
                    select(
                        'order.created_at',
                        'payment_method.name AS payment_method_name',
                        'order_status.name AS order_status_name',
                        'plan.name AS plan_name',
                        'plan.user_limit',
                        'plan.enterprise_limit',
                        'plan.upload_limit',
                        'plan.price'
                    )
                    ->where('order.user_id', 3)
                    ->where('order.app_id', 2)
                    ->join('payment_method', 'payment_method.id', '=', 'order.payment_method_id')
                    ->join('order_status', 'order_status.id', '=', 'order.order_status_id')
                    ->join('plan', 'plan.id', '=', 'order.plan_id')
                    ->get();

                if ( $userOrder->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $userOrder->toArray()
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
             * @date 13/06/2017
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

                $order = Order::where('id', $input['id'])
                ->first();

                if ($order == null) {
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
                        'data'    => $order->getAttributes()
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
             * @date 19/07/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'                => ['required', 'numeric'],
                    'order_status_id'   => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $order = Order::where('id', $input['id'])
                    ->where('order_status_id', (int) 2)
                    ->first();

                if ($order == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $userId = $order->user_id;

                $data = array(
                    'order_status_id'      => (int) $input['order_status_id']
                );

                $order = Order::where('id', $order->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated'),
                        'data'    => [
                            'user_id' => $userId
                        ]
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
                    'id'                => ['required', 'numeric'],
                    'json'              => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $order = Order::where('id', $input['id'])
                    ->where('order_status_id', (int) 2)
                    ->first();

                if ($order == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $userId = $order->user_id;

                $data = array(
                    'json'      => $input['json']
                );

                $order = Order::where('id', $order->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated'),
                        'data'    => [
                            'user_id' => $userId
                        ]
                    ]
                );

            break;

        }
    }
}
