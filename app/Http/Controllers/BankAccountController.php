<?php

namespace App\Http\Controllers;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form as Form;
use App\Services\Useful\User as User;

class BankAccountController extends Controller
{

    protected static $controller    = 'bank_account';

    protected static $fillable      = [
        'bank_id',
        'name',
        'agency_number',
        'agency_number_digit',
        'account_number',
        'account_number_digit',
        'opening_balance',
        'opening_at',
        'is_savings_account',
        'is_current_account',
    ];

    protected static $guarded       = ['id'];

    /**
    * get
    * @param   int $id (default: 0)
    * @access  protected
    * @return  array
    * @version 1.0 2017-01-06
    */
    public static function get($id = 0)
    {
        if ($id > 0) {
            $input = [
                'id'            => (int) $id,
            ];
            $route          = 'bankAccountServiceOne';
            $version        = '1.0';
            $client         = new \App\Services\Client();
            $result         = $client->execute($input, $route, $version);
            return $result;
        } else {
            $input = [];
            $route          = 'bankAccountServiceGet';
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
        $bank_account = [];
        if ($id > 0) {
            $result = self::get($id);
            if ($result['status'] == true) {
                $bank_account = $result['data'][self::$controller];
            }
        }

        $route      = 'bankServiceGet';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $banks      = $client->execute([], $route, $version);
        $banks      = $banks['data']['bank'];
        $bank       = [];

        foreach($banks as $k => $b) {
            $bank[$b['id']] = $b['name'];
        }

        return view('app.bank-account.form', [
            'form'  => Form::make(self::$fillable, self::$guarded, self::$controller, $bank_account),
            'options'   => [
                'bank' => $bank
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
    public function new(Request $request) {
        return view('app.bank-account.new', [
            'title' => message(self::$controller, 'new'),
            'form'  => self::form(null),
            'url'   => route("bankAccount.insert"),
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "all"),
                    'url'   => route('bankAccount.index')
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

        $bank_account = self::get($id);

        if (isArray($bank_account)) {
            if ($bank_account['status'] == false) {
                flash($bank_account['message'], 'red');
                return redirect()->route('bankAccount.index');
            }
        }

        $bankAccountName = $bank_account['data']['bank_account']['name'];

        return view('app.bank-account.edit', [
            'title'     => message(self::$controller, 'edit'),
            'form'      => self::form($id),
            'url'       => route("bankAccount.update"),
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "index"),
                    'url'   => route('bankAccount.index')
                ],
                [
                    'link'  => true,
                    'label' => $bankAccountName,
                    'url'   => route('bankAccount.show', ['id' => $id])
                ],
                [
                    'link'  => false,
                    'label' => message(self::$controller, 'edit'),
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
    * @version 1.0 2017-02-20
    */
    public function index(Request $request) {
        $bankAccounts   = [];
        $get        = self::get();
        if ($get['status'] == true) {
            $bankAccounts = $get['data']['bank_account'];
        }
        return view('app.bank-account.index', [
            'title'         => message(self::$controller, 'index'),
            'bankAccounts'  => $bankAccounts,
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
      * @version 1.0 2017-02-20
      */
    public function insert(Request $request)
    {
        $input      = $request->input();
        $post       = $input[self::$controller];
        $input      = $post;

        $input['is_savings_account'] = 1;
        $input['is_current_account'] = 1;

        $route      = 'bankAccountServiceCreate';
        $version    = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);
        if ($result['status'] == true) {
             $result['url']   = route("bankAccount.show", ['id' => $result['data']['bank_account']['id']]);
        }


        return response()->json($result);
    }

    /**
    * update
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-02-20
    */
    public function update(Request $request)
    {
        $input  = $request->input();
        $post   = $input[self::$controller];
        $input  = $post;

        $input['is_savings_account'] = 1;
        $input['is_current_account'] = 1;

        $route   = 'bankAccountServiceUpdate';
        $version = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);
        return  response()->json($result);
    }

    /**
    * disable
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-02-20
    */
    public function disable(Request $request, $id)
    {
        $input   = ['id' => $id];

        $route   = 'bankAccountServiceDisable';
        $version = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);

        if ($result['status'] == true) {
            flash($result['message'], 'green');
        } else {
            flash($result['message'], 'red');
        }
        return redirect()->route('bankAccount.show', ['id' => $id]);
    }

    /**
    * enable
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-02-20
    */
    public function enable(Request $request, $id)
    {
        $input  = ['id' => $id];

        $route   = 'bankAccountServiceEnable';
        $version = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);

        if ($result['status'] == true) {
            flash($result['message'], 'green');
        } else {
            flash($result['message'], 'red');
        }
        return redirect()->route('bankAccount.show', ['id' => $id]);
    }

    /**
      * show
      * @param   Request  $request
      * @method  get
      * @access  public
      * @return  View
      * @version 1.0 2017-02-20
      */
    public function show(Request $request, $id)
    {
        $bank_account = self::get($id);

        if (isArray($bank_account)) {
            if ($bank_account['status'] == false) {
                flash($bank_account['message'], 'orange');
                return redirect()->route('bankAccount.index');
            }
        }

        $bank_account = $bank_account['data']['bank_account'];

        return view('app.bank-account.show', [
            'title'         => message(self::$controller, 'show'),
            'bank_account'  => $bank_account,
            'actions'       => [
                'edit'      => [
                    'visible'   => true,
                    'label'     => message(self::$controller, 'action.edit'),
                    'url'       => route("bankAccount.edit", ['id' => $id])
                ]
            ],
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "all"),
                    'url'   => route('bankAccount.index')
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
