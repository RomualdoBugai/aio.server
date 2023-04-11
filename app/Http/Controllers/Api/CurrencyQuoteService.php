<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Currency as Currency;
use App\Models\CurrencyQuote as CurrencyQuote;

class CurrencyQuoteService extends Api {


    public function create(Request $request) {
        switch ($request->version) {
            /**
             * retrive all currency quote rows
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
                    'currency'  => ['required'],
                    'day'       => ['required', 'date:Y-m-d'],
                    'rate'      => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $currency       = Currency::where('code', strtoupper($input['currency']))->first();
                $currencyQuote  = CurrencyQuote::create(
                    [
                        'rate'          => $input['rate'],
                        'day'           => $input['day'],
                        'currency_id'   => $currency->id
                    ]
                );

                $currencyQuote = $currencyQuote->find($currencyQuote->id);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('currency-quote', 'created'),
                        'data'    => ['currency_quote' => $currencyQuote->getAttributes()]
                    ]
                );

            break;
        }
    }

    public function update(Request $request) {
        switch ($request->version) {
            /**
             * retrive all currency quote rows
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
                    'currency'  => ['required'],
                    'day'       => ['required', 'date:Y-m-d'],
                    'rate'      => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $currency       = Currency::where('code', strtoupper($input['currency']))->first();
                $currencyQuote  = CurrencyQuote::where('currency_id', $currency->id)
                ->where('day', $input['day'])
                ->first();

                if ($currencyQuote == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('currency-quote', 'not-found')
                        ]
                    );                    
                }

                $currencyQuote->update(
                    [
                        'rate'          => $input['rate']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('currency-quote', 'updated')
                    ]
                );

            break;
        }
    }

    public function get(Request $request) {
        switch ($request->version) {
            /**
             * retrive all currency quote rows
             * 
             * @author  William Novak
             * @return  object json
             * @version 1.0 2016-01-08
             */
            case '1.0':
                
                # input
                $input = $request->input();

                # define rules
                $rules = [
                    'currency'      => ['required'],
                    'start_at'      => ['required', 'date:Y-m-d'],
                    'end_at'        => ['required', 'date:Y-m-d'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                # check currency
                $currency = Currency::where('code', strtoupper($input['currency']))->first();

                if ($currency == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('currency', 'not-found')
                        ]
                    );
                }

                $currencyQuote = CurrencyQuote::whereBetween('day', [$input['start_at'], $input['end_at']])
                ->where('currency_id', $currency->id)
                ->get();

                if ($currencyQuote->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('currency-quote', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('currency-quote', 'total-found', ['total' => $currencyQuote->count()]),
                        'data'    => ['currency_quote' => $currencyQuote->toArray()]
                    ]
                );

            break;

            /**
             * last updates
             * 
             * @author  William Novak
             * @return  object json
             * @version 1.0 2016-02-28
             */
            case '1.1':
                
                # input
                $input = $request->input();

                # define rules
                $rules = [
                    'offset'    => ['required', 'numeric'],
                    'limit'     => ['required', 'numeric']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                # get currency
                $currencyQuote = CurrencyQuote::with('currency')
                ->orderBy('id', 'desc')
                ->offset($input['offset'])
                ->limit($input['limit'])
                ->get();

                if ($currencyQuote->count() == 0)
                {
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
                        'message' => message('currency-quote', 'total-found', ['total' => $currencyQuote->count()]),
                        'data'    => ['currency_quote' => $currencyQuote->toArray()]
                    ]
                );

            break;
        }
    }

    public function one(Request $request) {
        switch ($request->version) {
            /**
             * retrive all currency quote rows
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
                    'id'  => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $currencyQuote = CurrencyQuote::find($input['id']);

                if ($currencyQuote == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('currency-quote', 'not-found')
                        ]
                    );                    
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('currency-quote', 'found'),
                        'data'    => ['currency_quote' => $currencyQuote->getAttributes()]
                    ]
                );

            break;
        }
    }

    public function last(Request $request) {
        switch ($request->version) {
            /**
             * retrive all currency quote rows
             * 
             * @author  William Novak
             * @return  object json
             * @version 1.0 2016-01-08
             */
            case '1.0':
                
                # input
                $input = $request->input();

                # define rules
                $rules = [
                    'currency'   => ['required'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                # check currency
                $currency = Currency::where('code', strtoupper($input['currency']))->first();

                if ($currency == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('currency', 'not-found')
                        ]
                    );
                }

                $currencyQuote = CurrencyQuote::where("currency_id", $currency->id)
                ->orderBy('day', 'desc')
                ->first();

                if ($currencyQuote == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('currency-quote', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('currency-quote', 'found'),
                        'data'    => ['currency_quote' => $currencyQuote->getAttributes()]
                    ]
                );

            break;
        }
    }

}
