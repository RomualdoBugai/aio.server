<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\BankAccount as BankAccount;
use App\Models\Bank as Bank;

class BankAccountService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2016-12-03
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'bank_id'               => ['required'],
                    'agency_number'         => ['required', 'max:5'],
                    'agency_number_digit'   => ['required', 'max:2'],
                    'account_number'        => ['required', 'max:8'],
                    'account_number_digit'  => ['required', 'max:2'],
                    'opening_balance'       => ['required'],
                    'opening_at'            => ['required', 'date:Y-m-d'],
                    'is_savings_account'    => ['required', 'boolean'],
                    'is_current_account'    => ['required', 'boolean'],
                    'name'                  => ['required', 'max:56'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $bank = Bank::find($input['bank_id']);

                if ($bank == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank', 'not-found')
                        ]
                    );
                }

                $bankAccount = BankAccount::create(
                    [
                        'bank_id'               => (int)    $input['bank_id'],
                        'agency_number'         => (string) $input['agency_number'],
                        'agency_number_digit'   => (string) $input['agency_number_digit'],
                        'account_number'        => (string) $input['account_number'],
                        'account_number_digit'  => (string) $input['account_number_digit'],
                        'opening_balance'       => (string) $input['opening_balance'],
                        'opening_at'            => (string) $input['opening_at'],
                        'is_savings_account'    => (bool)   $input['is_savings_account'],
                        'is_current_account'    => (bool)   $input['is_current_account'],
                        'is_active'             => true,
                        'name'                  => (string) $input['name'],
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('bank-account', 'created'),
                        'data'    => ['bank_account' => $bankAccount->getAttributes()]
                    ]
                );

            break;
        }
    }

    public function update(Request $request)
    {
        switch ($request->version) {
            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2016-12-03
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'                    => ['required'],
                    'bank_id'               => ['required'],
                    'agency_number'         => ['required', 'max:5'],
                    'agency_number_digit'   => ['required', 'max:2'],
                    'account_number'        => ['required', 'max:10'],
                    'account_number_digit'  => ['required', 'max:5'],
                    'opening_balance'       => ['required'],
                    'opening_at'            => ['required', 'date:Y-m-d'],
                    'is_savings_account'    => ['required', 'boolean'],
                    'is_current_account'    => ['required', 'boolean'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $bank = Bank::find($input['bank_id']);

                if ($bank == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank', 'not-found')
                        ]
                    );
                }

                $bankAccount = BankAccount::find($input['id']);

                if ($bankAccount == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank-account', 'not-found')
                        ]
                    );
                }

                $bankAccount->update(
                    [
                        'bank_id'               => (int)    $input['bank_id'],
                        'agency_number'         => (string) $input['agency_number'],
                        'agency_number_digit'   => (string) $input['agency_number_digit'],
                        'account_number'        => (string) $input['account_number'],
                        'account_number_digit'  => (string) $input['account_number_digit'],
                        'opening_balance'       => (string) $input['opening_balance'],
                        'opening_at'            => (string) $input['opening_at'],
                        'is_savings_account'    => (bool)   $input['is_savings_account'],
                        'is_current_account'    => (bool)   $input['is_current_account'],
                        'is_active'             => true,
                        'name'                  => (string) $input['name'],
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('bank-account', 'updated')
                    ]
                );

            break;
        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2016-10-09
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                $bankAccount = BankAccount::with('bank')->get();

                if ($bankAccount->count() == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank-account', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('bank-account', 'total-found', ['total' => $bankAccount->count()]),
                        'data'    => ['bank_account' => $bankAccount->toArray()]
                    ]
                );


            break;
        }
    }

    public function one(Request $request)
    {
        switch ($request->version) {
            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2016-12-04
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'id'    => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                if ( (int) $input['id'] == 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank-account', 'empty-id')
                        ]
                    );
                }

                $bankAccount = BankAccount::with('bank')->where('id', $input['id'])->first();

                if ($bankAccount == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank-account', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('bank-account', 'found'),
                        'data'    => ['bank_account' => $bankAccount->toArray()]
                    ]
                );

            break;
        }
    }

    public function gross(Request $request)
    {
        switch ($request->version) {
            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2017-03-05
             * @return  object json
             * @version 1.0
             */
            case '1.0':


                $bankAccount = BankAccount::count();

                if ($bankAccount == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank-account', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('bank-account', 'found'),
                        'data'    => [
                            'bank_account' => $bankAccount
                        ]
                    ]
                );

            break;
        }
    }

}
