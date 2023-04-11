<?php

namespace App\Http\Controllers;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;

class UserController extends Controller
{

    protected static $fillable      = [
        'name',
        'email',
        'password',
        'confirm_password',
    ];

    protected static $guarded       = [
        'id',
        'user_id',
        'invite_token'
    ];

    protected static $controller    = 'user';

    /**
    * get one
    * @param   int $id
    * @access  protected
    * @return  array
    * @version 1.0 2017-02-09
    */
    protected static function get($id = 0)
    {
        $input      = ( $id > 0 ? ['id' => $id] : [] );
        $route      = 'userServiceGet';
        $version    = ( $id > 0 ? '1.1' : '1.2' );
        $client     = new \App\Services\Client();
        return $client->execute($input, $route, $version);
    }

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2016-02-09
    */
    public function index(Request $request)
    {
        $users   = [];
        $service = self::get();
        if (isArray($service)) {
            if ($service['status'] == true) {
                $users = $service['data']['users'];
            }
        }

        return view('app.user.index', [
            'status' => (bool) $service['status'],
            'users'  => $users
        ])->render();
    }

    private static function forms($id)
    {
        return [
            'address' => \App::call("App\Http\Controllers\AddressController@new",
                [
                    'controller'    => 'user',
                    'controller_id' => $id,
                ]
            ),
            'phone' => \App::call("App\Http\Controllers\PhoneController@new",
                [
                    'controller'    => 'user',
                    'controller_id' => $id,
                ]
            ),
            'scheduling' => \App::call("App\Http\Controllers\SchedulingController@new",
                [
                    'controller'    => 'user',
                    'controller_id' => $id,
                ]
            ),
        ];
    }

    private static function indexes($id)
    {
        return [
            'address' => \App::call("App\Http\Controllers\AddressController@index",
                [
                    'controller'    => 'user',
                    'controller_id' => $id,
                ]
            ),
            'phone' => \App::call("App\Http\Controllers\PhoneController@index",
                [
                    'controller'    => 'user',
                    'controller_id' => $id,
                ]
            ),
            'scheduling' => \App::call("App\Http\Controllers\SchedulingController@new",
                [
                    'controller'    => 'user',
                    'controller_id' => $id,
                ]
            ),
        ];
    }

    /**
      * show
      * @param   Request  $request
      * @method  get
      * @access  public
      * @return  View
      * @version 1.0 2017-02-07
      */
    public function show(Request $request, $id)
    {
        $service = self::get($id);

        if (isArray($service)) {
            if ($service['status'] == false) {
                flash($service['message'], 'red');
                return redirect()->route('user.index');
            }
        }

        $user = $service['data']['user'];

        return view('app.user.show', [
            'title'         => message(self::$controller, 'title'),
            'user'          => $user,
            'forms'         => self::forms($id),
            'indexes'       => self::indexes($id),
            'actions'       => [
                'edit'      => [
                    'visible'   => true,
                    'label'     => message(self::$controller, 'edit'),
                    'url'       => route("user.edit", ['id' => $id])
                ],
            ],
            'breadcrumbs'   => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "index"),
                    'url'   => route('user.index')
                ],
                [
                    'link'  => false,
                    'label' => message(self::$controller, "show"),
                    'url'   => null
                ],
            ]
        ]);
    }

    /**
    * new
    * @param   int  $id
    * @access  protected static
    * @return  View
    * @version 1.0 2017-02-22
    */
    protected static function form($fromInvite = false, $name = null, $email = null, $user_id = null, $invite_token = null) {

        $user = [];
        if ($fromInvite) {
            $user = [
                'fromInvite'    => (bool)   true,
                'name'          => (string) $name,
                'email'         => (string) $email,
                'user_id'       => (int)    $user_id,
                'invite_token'  => (string) $invite_token
            ];
        }

        return view('app.user.form', [
            'form'  => Form::make(self::$fillable, self::$guarded, self::$controller, $user),
        ])->render();
    }

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2017-02-22
    */
    public function new(Request $request) {

        $get = $request->input();

        $form = self::form();

        if ($get['token'] != null) {

            $input  = [
                'user_id'       => $get['from'  ] / 1000,
                'email'         => $get['email' ],
                'invite_token'  => $get['token' ],
                'name'          => $get['name'  ]
            ];

            # check

            $route      = 'inviteUserServiceCheck';
            $version    = '1.0';
            $client     = new \App\Services\Client();
            $check      = $client->execute($input, $route, $version);

            # if not exists or invalid token

            if ($check['status'] == false) {
                flash($check['message'], 'flash');
                return redirect()->route('logIn');
            }

            $checkIfUserExists  = $client->execute(['email' => $input['email']], 'userServiceGet', '1.0');

            if ($checkIfUserExists['status'] == true) {

                $id                 = $checkIfUserExists['data']['id'];
                $client             = new \App\Services\Client();
                $linkToApp          = $client->execute(['user_id' => $id], 'userAppServiceCreate', '1.0');

                if ($linkToApp['status'] == true) {

                    $data = [
                        'email'         => $input['email'],
                        'invite_token'  => $input['invite_token']
                    ];

                    $disableRequest     = $client->execute($data, 'inviteUserServiceDisable', '1.1');

                }

                flash($linkToApp['message'], ($linkToApp['status'] == true ? 'green' : 'red'));
                return redirect()->route('logIn');

            }

            $form   = self::form(true, $input['name'], $input['email'], $input['user_id'], $input['invite_token']);

        }

        return view('app.user.new', [
            'title' => message(self::$controller, 'new'),
            'form'  => $form
        ]);
    }

    /**
      * new
      * @param   Request $request
      * @method  post
      * @access  public
      * @return  string  json
      * @version 1.0 2017-02-23
      */
    public function insert(Request $request) {
        $input      = $request->input();
        $post       = $input[self::$controller];
        $input      = $post;
        $input['confirmEmail']      = false;
        $input['password']          = md5($input['password']);
        $input['confirmPassword']   = md5($input['confirm_password']);

        $route      = 'userServiceCreate';
        $version    = '1.1';

        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);
        if ($result['status'] == true) {

            if ($input['user_id'] > 0) {

                $input      = [
                    'user_id'       => $input['user_id'],
                    'user_equal_id' => $result['data']['id'],
                    'invite_token'  => $input['invite_token']
                ];
                $route      = 'userShareServiceCreate';
                $version    = '1.1';
                $client     = new \App\Services\Client();
                $result     = $client->execute($input, $route, $version);
            }


            $result['url']   = route("logIn");
        }

        return response()->json($result);
    }

}
