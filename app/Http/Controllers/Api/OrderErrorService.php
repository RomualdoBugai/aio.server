<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\Payment\Order as Order;
use App\Models\Payment\OrderError as OrderError;

class OrderErrorService extends Api
{
    protected static $controller = 'order-error';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 01/08/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'json'              => ['required', 'json'],
                    'order_id'          => ['required', 'numeric'],
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

                $orderError = new OrderError;
                $orderError = $orderError::create(
                    [
                        'json'              => $input['json'],
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
}
