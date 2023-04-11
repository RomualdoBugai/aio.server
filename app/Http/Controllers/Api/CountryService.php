<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Internationalization\Country as Country;

class CountryService extends Api
{

    public function get(Request $request)
    {
        switch ($request->version)
        {
            /**
             * retrive all country rows
             * 
             * @author  William Novak
             * @return  object json
             * @version 1.0 2016-01-07
             */
            case '1.0':
                
                $country = new Country;
                $country = $country->get();

                if ($country->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('country', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('country', 'total-found', ['total' => $country->count()]),
                        'data'    => ['country' => $country->toArray()]
                    ]
                );

            break;
        }
    }

    public function one(Request $request)
    {
        switch ($request->version)
        {
            /**
             * retrive one country row
             * 
             * @author  William Novak
             * @return  object json
             * @version 1.0 2016-01-07
             */
            case '1.0':
                
                $input = $request->input();

                # define rules
                $rules = [
                    'id'       => ['required', 'exists:country,id'],
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

                $country = Country::find($input['id']);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('country', 'found'),
                        'data'    => ['country' => $country->getAttributes()]
                    ]
                );

            break;
        }
    }

}
