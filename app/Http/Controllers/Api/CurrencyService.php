<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Currency as Currency;

class CurrencyService extends Api
{

    public function get(Request $request) {
        switch ($request->version) {
            /**
             * retrive all currency rows
             * 
             * @author  William Novak
             * @date    2016-12-03
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                
                $currency = new Currency;
                $currency = $currency->get();

                if ($currency->count() == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('currency', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('currency', 'total-found', ['total' => $currency->count()]),
                        'data'    => ['currency' => $currency->toArray()]
                    ]
                );

            break;
        }
    }

    public function one(Request $request) {
        switch ($request->version) {
            /**
             * retrive all currency rows
             * 
             * @author  William Novak
             * @date    03/12/2016
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                
                $input = $request->input();

                # define rules
                $rules = [
                    'id'       => ['required', 'exists:currency,id'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $currency = Currency::find($input['id']);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('currency', 'found'),
                        'data'    => ['currency' => $currency->getAttributes()]
                    ]
                );

            break;
        }
    }

}
