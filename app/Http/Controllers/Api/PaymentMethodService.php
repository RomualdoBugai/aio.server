<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\PaymentMethod as PaymentMethod;

class PaymentMethodService extends Api
{

    protected static $controller = 'payment-method';

    public function get(Request $request)
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
                    'is_active'     => ['required', ]
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $paymentMethod = PaymentMethod::whereIn('is_active', $input['is_active'])
                    ->get();

                if ( $paymentMethod->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $paymentMethod->toArray()
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]
                );

            break;
        }
    }

}
