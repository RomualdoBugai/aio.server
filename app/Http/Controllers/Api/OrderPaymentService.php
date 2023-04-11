<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;
use App\Models\Payment\Order as Order;
use App\Models\Payment\OrderPayment as OrderPayment;

class OrderPaymentService extends Api
{
    protected static $controller = 'order-payment';

    public function create(Request $request)
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
                    'amount_received'   => ['required', 'numeric'],
                    'order_id'          => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $order = Order::find($input['order_id']);

                if ($order == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('order', 'not-found'),
                    ]);
                }

                $orderPayment = new OrderPayment;
                $orderPayment = $orderPayment::create(
                    [
                        'amount_received'   => $input['amount_received'],
                        'order_id'          => $order->id                      
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
             * @date 23/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'order_id'     => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $orderPayment = OrderPayment::where('order_id', $input['order_id'])
                    ->get();

                if ( $orderPayment->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $orderPayment->toArray()
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
             * @date 23/06/2017
             * @return void
             */
            case '1.1':

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

                $orderPaymentReturn = [];

                $userOrder = Order::where('user_id', $user->id)
                    ->get();

                if ( $userOrder->count() > 0 ) {                    

                    foreach ($userOrder as $value) {
                        
                        $orderPayment = OrderPayment::where('order_id', $value['id'])
                            ->get();

                        if ( $orderPayment->count() > 0 ) {
                            $orderPaymentReturn[] = $orderPayment;

                        }
                    }
                }else{
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('order', 'not-found')
                        ]
                    );
                }

                if ( $orderPayment->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $orderPayment->toArray()
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
             * @date 23/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'order_id'           => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $orderPayment = OrderPayment::where('order_id', $input['order_id'])
                ->first();

                if ($orderPayment == null) {
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
                        'data'    => $orderPayment->getAttributes()
                    ]
                );

            break;
        }
    }
}
