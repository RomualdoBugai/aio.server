<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\Payment\Order as Order;
use App\Models\Payment\OrderItem as OrderItem;

class OrderItemService extends Api
{

    protected static $controller = 'order-item';

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
                    'quantity'           => ['required', 'numeric'],
                    'amount'             => ['required', 'numeric'],
                    'description'        => ['required', 'max:224'],
                    'product_code'       => ['required', 'max:5'],
                    'order_id'           => ['required', 'numeric']
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

                $orderItem = new OrderItem;
                $orderItem = $orderItem::create(
                    [
                        'quantity'          => $input['quantity'],
                        'amount'            => $input['amount'],
                        'description'       => $input['description'],
                        'product_code'      => $input['product_code'],
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
             * @date 13/06/2017
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

                $orderItem = OrderItem::where('id', $input['order_id'])
                    ->get();

                if ( $orderItem->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $orderItem->toArray()
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

                $orderItem = OrderItem::where('id', $input['id'])
                ->first();

                if ($orderItem == null) {
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
                        'data'    => $orderItem->getAttributes()
                    ]
                );

            break;
        }
    }
}
