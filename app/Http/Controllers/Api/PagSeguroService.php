<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Payment\Order as Order;
use App\Models\Payment\PagSeguro as PagSeguro;

class PagSeguroService extends Api
{

    protected static $controller = 'pag-seguro';

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
                    'code'              => ['required', 'max:96'],
                    'fee_amount'        => ['required', 'max:17'],
                    'net_amount'        => ['required', 'max:17'],
                    'extra_amount'      => ['required', 'max:17'],
                    'json'              => ['required', 'json'],
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

                $pagSeguro = new PagSeguro;
                $pagSeguro = $pagSeguro::create(
                    [
                        'code'              => $input['code'],
                        'fee_amount'        => $input['fee_amount'],
                        'net_amount'        => $input['net_amount'],
                        'extra_amount'      => $input['extra_amount'],
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

                $pagSeguro = PagSeguro::where('order_id', $order->id)
                    ->get();

                if ( $pagSeguro->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $pagSeguro->toArray()
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

                $pagSeguro = PagSeguro::where('id', $input['id'])
                ->first();

                if ($pagSeguro == null) {
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
                        'data'    => $pagSeguro->getAttributes()
                    ]
                );

            break;
        }
    }
}
