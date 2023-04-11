<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Payment\Order as Order;
use App\Models\Payment\PagSeguroTransaction as PagSeguroTransaction;

class PagSeguroTransactionService extends Api
{

    protected static $controller = 'pag-seguro-transaction';

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
                    'code'              => ['required', 'max:128'],
                    'status'            => ['required', 'max:1'],
                    'name'              => ['required', 'max:32'],
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

                $pagSeguroTransaction = new PagSeguroTransaction;
                $pagSeguroTransaction = $pagSeguroTransaction::create(
                    [
                        'code'              => $input['code'],
                        'status'            => $input['status'],
                        'name'              => $input['name'],
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

                $order = Order::find($input['order_id']);

                if ($order == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('order', 'not-found'),
                    ]);
                }

                $pagSeguroTransaction = PagSeguroTransaction::where('order_id', $order->id)
                    ->get();

                if ( $pagSeguroTransaction->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $pagSeguroTransaction->toArray()
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

                $pagSeguroTransaction = PagSeguroTransaction::where('id', $input['id'])
                ->first();

                if ($pagSeguroTransaction == null) {
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
                        'data'    => $pagSeguroTransaction->getAttributes()
                    ]
                );

            break;
        }
    }
}
