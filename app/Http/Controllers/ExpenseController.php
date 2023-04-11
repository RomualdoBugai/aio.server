<?php

namespace App\Http\Controllers;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;
use App\Services\Useful\User as User;

class ExpenseController extends Controller {

    protected static $controller    = 'expense';

    protected static $fillable      = [
        'name',
        'description',
        'due_date_at',
        'amount',
        'bank_account_id',
        'currency_id',
    ];

    protected static $guarded   = [
        'id',
        'user_id',
        'is_active'
    ];

    protected static $dependencies = [
        'bankAccount'
    ];

    /**
    * get
    * @param   int $id (default: 0)
    * @access  protected
    * @return  array
    * @version 1.0 2017-02-22
    */
    protected static function get($id = 0)
    {
        if ($id > 0) {
            $input          = ['id'            => (int) $id];
            $route          = 'expenseServiceOne';
            $version        = '1.0';
            $client         = new \App\Services\Client();
            $result         = $client->execute($input, $route, $version);
            return $result;
        } else {
            $input          = [];
            $route          = 'expenseServiceGet';
            $version        = '1.0';
            $client         = new \App\Services\Client();
            $result         = $client->execute($input, $route, $version);
            return $result;
        }
    }

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2017-01-06
    */
    protected static function form($id)
    {
        $expense = [];

        if ($id > 0) {
            $result = self::get($id);
            if ($result['status'] == true) {
                $expense = $result['data'][self::$controller];
            }
        }

        $bankAccount    = [];
        $bankAccounts   = \App::call("App\Http\Controllers\BankAccountController@get");

        dd($bankAccounts);

        foreach($bankAccounts as $k => $b) {
            $bankAccount[$b['id']] = $b['name'];
        }

        # currency
        $route              = 'currencyServiceGet';
        $version            = '1.0';
        $client             = new \App\Services\Client();
        $currencys          = $client->execute([], $route, $version);
        $currencys          = $currencys['data']['currency'];
        $currency           = [];

        foreach($currencys as $k => $b) {
            $currency[$b['id']] = $b['name'];
        }

        return view('app.expense.form', [
            'form'      => Form::make(self::$fillable, self::$guarded, self::$controller, $expense),
            'options'   => [
                'bankAccount'   => $bankAccount,
                'currency'      => $currency
            ]
        ])->render();
    }

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2017-02-20
    */
    public function new(Request $request)
    {

        $bankAccountDependency = \App\Services\Dependencies::check('bank_account');
        if ( $bankAccountDependency == false ) {
            flash(message('bank_account', 'dependencie'), 'red');
            return redirect()->route('expense.index');
        }

        return view('app.expense.new', [
            'title' => message(self::$controller, 'new'),
            'form'  => self::form(null),
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "all"),
                    'url'   => route('expense.index')
                ],
                [
                    'link'  => false,
                    'label' => message(self::$controller, "new"),
                    'url'   => null
                ],
            ]
        ]);
    }

    /**
    * edit
    * @param   Request  $request
    * @method  post
    * @param   int      $id
    * @access  public
    * @return  View
    * @version 1.0 2017-02-20
    */
    public function edit(Request $request, $id)
    {

        $expense = self::get($id);

        if (isArray($expense)) {
            if ($expense['status'] == false) {
                flash($expense['message'], 'red');
                return redirect()->route('expense.index');
            }
        }

        $expenseName = $expense['data']['expense']['name'];

        return view('app.expense.edit', [
            'title'     => message(self::$controller, 'edit'),
            'form'      => self::form($id),
            'url'       => route("expense.update"),
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "index"),
                    'url'   => route('expense.index')
                ],
                [
                    'link'  => true,
                    'label' => $expenseName,
                    'url'   => route('expense.show', ['id' => $id])
                ],
                [
                    'link'  => false,
                    'label' => message('expense', 'edit'),
                    'url'   => null
                ],
            ]
        ]);
    }

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2017-02-22
    */
    public function index(Request $request)
    {

        $expenses   = [];
        $get        = self::get();

        if ($get['status'] == true) {
            $expenses = $get['data']['expense'];
        }

        return view('app.expense.index', [
            'title'         => message(self::$controller, 'index'),
            'expenses'      => $expenses,
            'breadcrumbs'   => [
                [
                    'link'  => false,
                    'label' => message(self::$controller, "all"),
                    'url'   => null
                ],
            ]
        ]);
    }

    /**
      * new
      * @param   Request $request
      * @method  post
      * @access  public
      * @return  string  json
      * @version 1.0 2017-02-22
      */
    public function insert(Request $request)
    {

        $input      = $request->input();
        $post       = $input[self::$controller];
        $input      = $post;

        $input['amount'] = preg_replace("/[^A-Za-z0-9]/", "", $input['amount']);

        $input['user_id']   = User::id();

        $route      = 'expenseServiceCreate';
        $version    = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);

        if ($result['status'] == true) {
             $result['url']   = route("expense.show", ['id' => $result['data']['expense']['id']]);
        }

        return response()->json($result);
    }

    /**
    * update
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-02-22
    */
    public function update(Request $request)
    {

        $input  = $request->input();
        $post   = $input[self::$controller];
        $input  = $post;

        $route      = 'expenseServiceUpdate';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);

        return  response()->json($result);
    }

    /**
    * update
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-02-28
    */
    public function enable(Request $request, $id)
    {

        $input      = ['id' => $id];
        $route      = 'expenseServiceEnable';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);

        if ($result['status'] == true) {
            flash($result['message'], 'green');
        } else {
            flash($result['message'], 'red');
        }
        return redirect()->route('expense.show', ['id' => $id]);
    }

    /**
    * update
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-02-28
    */
    public function disable(Request $request, $id)
    {

        $input      = ['id' => $id];
        $route      = 'expenseServiceDisable';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);

        if ($result['status'] == true) {
            flash($result['message'], 'green');
        } else {
            flash($result['message'], 'red');
        }
        return redirect()->route('expense.show', ['id' => $id]);
    }

    /**
    * update
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-03-01
    */
    public function close(Request $request, $id)
    {

        $input      = ['id' => $id];
        $route      = 'expenseServiceClose';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);

        if ($result['status'] == true) {
            flash($result['message'], 'green');
        } else {
            flash($result['message'], 'red');
        }

        return redirect()->route('expense.show', ['id' => $id]);
    }

    /**
      * show
      * @param   Request  $request
      * @method  get
      * @access  public
      * @return  View
      * @version 1.0 2017-02-22
      */
    public function show(Request $request, $id)
    {
        $expense = self::get($id);

        if (isArray($expense)) {
            if ($expense['status'] == false) {
                flash($expense['message'], 'red');
                return redirect()->route('expense.index');
            }
        }

        $expense = $expense['data']['expense'];

        return view('app.expense.show', [
            'title'         => message(self::$controller, 'show'),
            'expense'       => $expense,
            'actions'       => [
                'edit'      => [
                    'visible'   => true,
                    'label'     => message(self::$controller, 'edit'),
                    'url'       => route("expense.edit", ['id' => $id])
                ],
                'status'    => [
                    'visible'   => true,
                    'label'     => ( $expense['is_active'] ? message(self::$controller, 'disable') : message(self::$controller, 'enable') ),
                    'url'       => ( $expense['is_active'] ? route('expense.disable', ['id' => $id]) : route('expense.enable', ['id' => $id]) ),
                ],
                'close'     => [
                    'visible'   => ( $expense['is_closed'] == true ? false : true ),
                    'label'     => message(self::$controller, 'close'),
                    'url'       => route('expense.close', ['id' => $id])
                ],
            ],
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "all"),
                    'url'   => route('expense.index')
                ],
                [
                    'link'  => false,
                    'label' => message(self::$controller, "show"),
                    'url'   => null
                ],
            ]
        ]);
    }

}
