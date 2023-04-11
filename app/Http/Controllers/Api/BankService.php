<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Bank as Bank;

class BankService extends Api
{

    public function get(Request $request)
    {
        switch ($request->version)
        {
            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2016-12-03
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $bank = new Bank;
                $bank = $bank->get();

                if ($bank->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('bank', 'total-found', ['total' => $bank->count()]),
                        'data'    => ['bank' => $bank->toArray()]
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
             * retrive all bank rows
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
                    'id'       => ['required', 'exists:bank,id'],
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

                $bank = Bank::find($input['id']);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('bank', 'found'),
                        'data'    => $bank->getAttributes()
                    ]
                );

            break;
        }
    }

}
