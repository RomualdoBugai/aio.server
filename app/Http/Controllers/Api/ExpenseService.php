<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Financial\Expense as Expense;
use App\Models\Bank as Bank;
use App\Models\BankAccount as BankAccount;
use App\Models\User as User;
use App\Models\Currency as Currency;

class ExpenseService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version)
        {
            /**
             * create a expense
             *
             * @author  William Novak
             * @date    2017-02-22
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'name'                  => ['required', 'max:224'],
                    'description'           => ['required'],
                    'due_date_at'           => ['required', 'date:Y-m-d'],
                    'amount'                => ['required', 'max:15'],
                    'bank_account_id'       => ['required', 'numeric'],
                    'currency_id'           => ['required', 'numeric'],
                    'user_id'               => ['required', 'numeric'],
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

                $bankAccount = BankAccount::find($input['bank_account_id']);

                if ($bankAccount == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank-account', 'not-found')
                        ]
                    );
                }

                $currency = Currency::find($input['currency_id']);

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

                $user = User::find($input['user_id']);

                if ($user == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $expense = Expense::create(
                    [
                        'bank_account_id'       => (int)    $bankAccount->id,
                        'user_id'               => (int)    $user->id,
                        'currency_id'           => (int)    $currency->id,
                        'name'                  => (string) $input['name'],
                        'description'           => (string) $input['description'],
                        'amount'                => (string) $input['amount'],
                        'is_active'             => (boolean)true,
                        'due_date_at'           => (string) $input['due_date_at'],
                        'is_closed'             => (bool)   false
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('expense', 'created'),
                        'data'    => ['expense' => $expense->getAttributes()]
                    ]
                );

            break;
        }
    }

    public function update(Request $request) {
        switch ($request->version) {
            /**
             * create a expense
             *
             * @author  William Novak
             * @date    2017-02-22
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'                    => ['required', 'numeric'],
                    'name'                  => ['required', 'max:224'],
                    'description'           => ['required'],
                    'due_date_at'           => ['required', 'date:Y-m-d'],
                    'amount'                => ['required', 'max:15'],
                    'bank_account_id'       => ['required', 'numeric'],
                    'currency_id'           => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $expense = Expense::find($input['id']);

                if ($expense == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $bankAccount = BankAccount::find($input['bank_account_id']);

                if ($bankAccount == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('bank-account', 'not-found')
                        ]
                    );
                }

                $currency = Currency::find($input['currency_id']);

                if ($currency == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('currency', 'not-found')
                        ]
                    );
                }

                $expense->update(
                    [
                        'bank_account_id'       => (int)    $bankAccount->id,
                        'currency_id'           => (int)    $currency->id,
                        'name'                  => (string) $input['name'],
                        'description'           => (string) $input['description'],
                        'amount'                => (string) $input['amount'],
                        'due_date_at'           => (string) $input['due_date_at']
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('expense', 'updated'),
                        'data'    => ['expense' => $expense->getAttributes()]
                    ]
                );

            break;
        }
    }

    public function disable(Request $request) {
        switch ($request->version) {
            /**
             * create a expense
             *
             * @author  William Novak
             * @date    2017-02-28
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'        => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $expense = Expense::find($input['id']);

                if ($expense == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $expense->update(
                    [
                        'is_active'             => false
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('expense', 'disabled'),
                        'data'    => ['expense' => $expense->getAttributes()]
                    ]
                );

            break;
        }
    }

    public function enable(Request $request) {
        switch ($request->version) {
            /**
             * create a expense
             *
             * @author  William Novak
             * @date    2017-02-28
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'        => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $expense = Expense::find($input['id']);

                if ($expense == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $expense->update(
                    [
                        'is_active'             => true
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('expense', 'enabled'),
                        'data'    => ['expense' => $expense->getAttributes()]
                    ]
                );

            break;
        }
    }

    public function close(Request $request) {
        switch ($request->version) {
            /**
             * create a expense
             *
             * @author  William Novak
             * @date    2017-02-28
             * @return  object json
             * @version 1.0
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'        => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $expense = Expense::find($input['id']);

                if ($expense == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $expense->update(
                    [
                        'is_closed'             => true
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('expense', 'closed'),
                        'data'    => ['expense' => $expense->getAttributes()]
                    ]
                );

            break;
        }
    }

    public function get(Request $request)
    {
        switch ($request->version)
        {
            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2017-02-22
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                $expense = Expense::with('bankAccount')
                ->with('user')
                ->with('currency')
                ->get();

                if ($expense->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('expense', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('expense', 'total-found', ['total' => $expense->count()]),
                        'data'    => ['expense' => $expense->toArray()]
                    ]
                );


            break;

            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2017-02-28
             * @return  object json
             * @version 1.0
             */
            case '1.1':

                $input = $request->input();

                # define rules
                $rules = [
                    'start_at'      => ['required', 'date:Y-m-d'],
                    'end_at'        => ['required', 'date:Y-m-d'],
                    'is_closed'     => ['required', 'boolean'],
                    'is_active'     => ['required', 'boolean'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $expense = Expense::with('bankAccount')
                ->with('user')
                ->with('currency')
                ->whereBetween('due_date_at', [$input['start_at'], $input['end_at']])
                ->where('is_closed', $input['is_closed'])
                ->where('is_active', $input['is_active'])
                ->get();

                if ($expense->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('expense', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('expense', 'total-found', ['total' => $expense->count()]),
                        'data'    => ['expense' => $expense->toArray()]
                    ]
                );


            break;
        }
    }

    public function one(Request $request) {
        switch ($request->version) {
            /**
             * retrive all bank rows
             *
             * @author  William Novak
             * @date    2017-02-22
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
                            'message' => message('expense', 'empty-id')
                        ]
                    );
                }

                $expense = Expense::with('bankAccount')->with('user')->with('currency')->where('id', $input['id'])->first();

                if ($expense == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('expense', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('expense', 'found'),
                        'data'    => ['expense' => $expense->toArray()]
                    ]
                );

            break;
        }
    }

}
