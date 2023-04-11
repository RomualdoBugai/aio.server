<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;
use App\Models\App as App;
use App\Models\Budget as Budget;
use App\Models\BudgetRecords as BudgetRecords;
use App\Models\Plan as Plan;

class BudgetService extends Api
{

    protected static $controller = 'budget';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 18/08/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'name'                          => ['required', 'max:112'],
                    'email'                         => ['required', 'email'],
                    'phone'                         => ['required', 'max:20'],
                    'records'                       => ['required', 'max:255']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }  

                $app = $input['app'];

                $check = Budget::where('email', $input['email'])
                    ->where('app_id', $app->id)
                    ->where('budget_status_id', 1)
                    ->first();

                if ($check != null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists'),
                        ]
                    );
                }

                $budget = new Budget;
                $budget = $budget::create(
                    [
                        'name'                          => $input['name'],
                        'email'                         => $input['email'],
                        'phone'                         => $input['phone'],
                        'budget_status_id'              => 1,
                        'app_id'                        => $app->id
                    ]
                );

                if($budget->id == null){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'error'),
                        ]
                    );
                }

                $budgetRecords = new BudgetRecords;
                $budgetRecords = $budgetRecords::create(
                    [
                        'records'                    => $input['records'],
                        'budget_id'                  => $budget->id
                    ]
                );

                # send email
                $title  = message('common', 'mail.budget-created.title', ['id'  => $budget->id]);

                $data = [
                    'user'          => $budget->name,
                    'id'            => $budget->id,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => message('common', 'mail.budget-created.footer')
                    ]
                ];

                $user = $budget;

                Mail::send('account.budget-created', $data, function($message) use (&$user, &$title){
                    $message->to($user->email, $user->name)->subject($title);
                });

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
             * @date 18/06/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'status'        => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $budget = Budget::
                    select(
                        'budget.*',
                        'app.name AS name_app'
                    )
                    ->where('budget_status_id', $input['status'])
                    ->join('app', 'app.id', '=', 'budget.app_id')
                    ->get();

                if ( $budget->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $budget->toArray()
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
            /**
             * @author Romualdo Bugai
             * @date 18/08/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'     => ['required', 'numeric'],
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

                $budget = Budget::
                    select(
                        'budget.*',
                        'app.name AS name_app'
                    )
                    ->where('user_id', $user->id)
                    ->join('app', 'app.id', '=', 'budget.app_id')
                    ->get();

                if ( $budget->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $budget->toArray()
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
             * @date 18/08/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'     => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $budget = Budget::where('id', $input['id'])
                ->first();

                if ($budget == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $budget->getAttributes()
                    ]
                );

            break;
        }
    }

    public function update(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 18/08/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'                    => ['required', 'numeric'],
                    'user_id'               => ['required', 'numeric'],
                    'records'               => ['required', 'max:255']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $budget = Budget::find($input['id']);

                if ($budget == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                if($budget->budget_status_id != 1){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-reply'),
                        ]
                    );
                }

                # send email
                $title  = message('common', 'mail.budget-reply.title', ['id'  => $budget->id]);

                $data = [
                    'user'          => $budget->name,
                    'id'            => $budget->id,
                    'created_at'    => $budget->created_at,
                    'records'       => $input['records'],
                    'name'          => $user->name,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => message('common', 'mail.budget-reply.footer')
                    ]
                ];

                $user = $budget;

                Mail::send('account.budget-reply', $data, function($message) use (&$user, &$title){
                    $message->to($user->email, $user->name)->subject($title);
                });

                $data = array(
                    'user_id'                   => $input['user_id'],
                    'budget_status_id'          => 2
                );

                Budget::where('id', $budget->id)->update($data);

                $budgetRecords = new BudgetRecords;
                $budgetRecords = $budgetRecords::create(
                    [
                        'records'                       => $input['records'],
                        'user_id'                       => $input['user_id'],
                        'budget_id'                     => $budget->id
                    ]
                );


                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 21/08/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'                    => ['required', 'numeric'],
                    'price'                 => ['required', 'numeric'],
                    'user_limit'            => ['required', 'numeric'],
                    'enterprise_limit'      => ['required', 'numeric'],
                    'upload_limit'          => ['required', 'numeric'],
                    'records'               => ['required', 'max:255']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $budget = Budget::find($input['id']);

                if ($budget == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                if($budget->budget_status_id == 1){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-reply'),
                        ]
                    );
                }

                if($budget->budget_status_id == 3 || $budget->budget_status_id == 4){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-finished'),
                        ]
                    );
                }

                $app = App::find($budget->app_id);

                # check user
                $user = User::where('email', $budget->email)
                    ->first();

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'user-not-create-account')
                    ]);
                }

                $plan_id = $budget->plan_id;

                if($plan_id == null){
                    # create plan
                    $plan = new Plan;
                    $plan = $plan::create(
                        [
                            'name'                      => 'sob medida',
                            'user_limit'                => $input['user_limit'],
                            'enterprise_limit'          => $input['enterprise_limit'],
                            'upload_limit'              => $input['upload_limit'],
                            'send_file_email'           => (bool) true,
                            'is_active'                 => (bool) true,
                            'allow_choose'              => (bool) true,
                            'price'                     => $input['price'],
                            'product_code'              => '0', # fixo
                            'app_id'                    => $app->id
                        ]
                    );

                    $plan_id = $plan->id;

                }

                $array = explode("com/",$app->url); 

                $link    = $app->url.'/'.substr($array[1], 3).'/index.php/checkout/pagamento/'.$user->id.'/'.$plan_id;

                # send email
                $title  = message('common', 'mail.budget-accept.title', ['id'  => $budget->id]);

                $data = [
                    'user'          => $budget->name,
                    'id'            => $budget->id,
                    'created_at'    => $budget->created_at,
                    'link'          => $link,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => message('common', 'mail.budget-reply.footer')
                    ]
                ];

                $user = $budget;

                Mail::send('account.budget-accept', $data, function($message) use (&$user, &$title){
                    $message->to($user->email, $user->name)->subject($title);
                });

                $data = array(
                    'id'                        => $budget->id,
                    'budget_status_id'          => 3,
                    'plan_id'                   => $plan_id
                );

                Budget::where('id', $budget->id)->update($data);

                $budgetRecords = new BudgetRecords;
                $budgetRecords = $budgetRecords::create(
                    [
                        'records'                       => $input['records'],
                        'user_id'                       => $budget->user_id,
                        'budget_id'                     => $budget->id
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 21/08/2017
             * @return void
             */
            case '1.2':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'                    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $budget = Budget::find($input['id']);

                if ($budget == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                if($budget->budget_status_id != 2){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-not-reply'),
                        ]
                    );
                }

                # send email
                $title  = message('common', 'mail.budget-denied.title', ['id'  => $budget->id]);

                $data = [
                    'user'          => $budget->name,
                    'id'            => $budget->id,
                    'created_at'    => $budget->created_at,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => message('common', 'mail.budget-reply.footer')
                    ]
                ];

                $user = $budget;

                Mail::send('account.budget-denied', $data, function($message) use (&$user, &$title){
                    $message->to($user->email, $user->name)->subject($title);
                });

                $data = array(
                    'id'                        => $budget->id,
                    'budget_status_id'          => 4
                );

                Budget::where('id', $budget->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;

        }
    }

    public function check(Request $request)
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
                    'id'                    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $budget = Budget::find($input['id']);

                if ($budget == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                if($budget->budget_status_id == 1){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-reply'),
                        ]
                    );
                }

                if($budget->budget_status_id == 3 || $budget->budget_status_id == 4){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-finished'),
                        ]
                    );
                }

                $app = App::find($budget->app_id);

                # check user
                $user = User::where('email', $budget->email)
                    ->first();

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'user-not-create-account')
                    ]);
                }

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'user-right')
                    ]
                );

            break;
        
        }
    }
}
