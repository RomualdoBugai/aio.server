<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;
use App\Models\Budget as Budget;
use App\Models\BudgetRecords as BudgetRecords;
use App\Models\Plan as Plan;

class BudgetRecordsService extends Api
{

    protected static $controller = 'budget-records';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 21/08/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'                       => ['required', 'numeric'],
                    'budget_id'                     => ['required', 'numeric'],
                    'records'                       => ['required', 'max:255']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }  

                $user = User::find($input['user_id']);

                if ($user == null){
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found')
                    ]);
                }

                $budget = Budget::find($input['budget_id']);

                if ($budget == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $budgetRecords = new BudgetRecords;
                $budgetRecords = $budgetRecords::create(
                    [
                        'user_id'                    => $user->id,
                        'budget_id'                  => $budget->id,
                        'records'                    => $input['records']
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
             * @date 21/08/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'budget_id'     => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $budgetRecords = BudgetRecords::where('budget_id', $input['budget_id'])
                    ->get();

                if ( $budgetRecords->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $budgetRecords->toArray()
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
}
