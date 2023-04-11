<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Currency\Api as CurrencyApi;
use Carbon\Carbon;

class CurrencyQuoteController extends Controller {

    /**
      * update
      * @param   Request $request
      * @param   boolean $json
      * @param   boolean $execute
      * @method  post
      * @access  public
      * @return  string  json
      * @version 1.1 2017-02-28
      */
    public function auto(Request $request, $json = true, $execute = true) {

        $package        = [];

        $client         = new \App\Services\Client();
        $currencys      = $client->execute([], 'currencyServiceGet', '1.0');   
        $currencys      = $currencys['data']['currency'];

        foreach($currencys as $currency) {
            $client         = new \App\Services\Client();
            $lastResult     = $client->execute(['currency' => $currency['code']], 'currencyQuoteServiceLast', '1.0');
            
            if ($execute == true) {
                $insert         = true;
                $update         = false;
                $input          = CurrencyApi::execute($currency['code'], false);

                if ($lastResult['status'] == true) {
                    if ($input['status'] == true) {
                        if ($lastResult['data']['currency_quote']['day'] == $input['day']) {
                            $insert = false;
                            if ($lastResult['data']['currency_quote']['rate'] != $input['rate']) {
                                $update = true;
                            }
                        }
                    }
                }
            
                if ($insert == true) {
                    if ($input['status'] == true) {
                        $route          = 'currencyQuoteServiceCreate';
                        $version        = '1.0';
                        $client         = new \App\Services\Client();
                        $result         = $client->execute($input, $route, $version);
                    }
                }

                if ($update) {
                    if ($input['status'] == true) {
                        $route          = 'currencyQuoteServiceUpdate';
                        $version        = '1.0';
                        $client         = new \App\Services\Client();
                        $result         = $client->execute($input, $route, $version);
                    }
                }
            } else {
                if ($lastResult['status'] == true) {
                    $package[$currency['code']] = $lastResult['data']['currency_quote'];
                }
            }
        }

        if ($json) {
            return response()
            ->json(
                [
                    'status'    => true, 
                    'message'   => message("currency-quote", "updated")
                ]
            );
        }

        return $package;
    }

    /**
      * index
      * @param   Request    $request
      * @access  public
      * @return  string     View
      * @version 1.0 2016-01-07
      */
    public function index(Request $request) {
        # auto start automatic process
        $currencyQuotes = $this->auto($request, false, false);

        $input = [
            'limit'     => 20,
            'offset'    => 0
        ];

        $client         = new \App\Services\Client();
        $lastUpdates    = $client->execute($input, 'currencyQuoteServiceGet', '1.1');       
        
        if ($lastUpdates['status'] == true) {
            $lastUpdates = (array) $lastUpdates['data']['currency_quote'];
        } else {
            $lastUpdates = [];
        }


        return view('app.currency-quote.index', [
            'currencyQuotes'    => (array) $currencyQuotes,
            'lastUpdates'       => $lastUpdates
        ])->render();
    }

}
