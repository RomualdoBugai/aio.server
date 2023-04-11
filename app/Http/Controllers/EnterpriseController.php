<?php

namespace App\Http\Controllers;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;
use App\Services\Useful\User as User;

class EnterpriseController extends Controller
{

    protected static $fillable      = ['name', 'fantasy_name', 'national_code', 'country_id'];

    protected static $guarded       = ['id', 'is_matrix'];

    protected static $controller    = 'enterprise';

    /**
    * get one
    * @param   int $id
    * @access  protected
    * @return  array
    * @version 1.0 2017-01-06
    */
    protected static function get($id)
    {
        if ($id > 0) {
            $input = [
                'id'            => (int) $id,
                'addresses'     => true,
                'emails'        => false,
                'phones'        => false,
                'additional'    => false,
                'certificates'  => false
            ];

            $route   = 'enterpriseServiceOne';
            $version = '1.1';

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
        $enterprise = [];
        if ($id > 0) {
            $result = self::get($id);
            if ($result['status'] == true) {
                $enterprise = $result['data'][self::$controller];
            }
        }

        $route      = 'countryServiceGet';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $countries  = $client->execute([], $route, $version);
        $countries  = $countries['data']['country'];
        $country    = [];

        foreach($countries as $k => $c) {
            $country[$c['id']] = message("country", $c['name']);
        }

        return view('app.enterprise.form', [
            'form'  => Form::make(self::$fillable, self::$guarded, self::$controller, $enterprise),
            'options'   => [
                'country' => $country
            ]
        ])->render();
    }

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2017-01-06
    */
    public function new(Request $request)
    {
        return view('app.enterprise.new', [
            'title' => message(self::$controller, 'new'),
            'form'  => self::form(null),
            'url'   => route("enterprise.insert"),
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "all"),
                    'url'   => route('enterprise.index')
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
    * @version 1.0 2017-01-06
    */
    public function edit(Request $request, $id)
    {

        $enterprise = self::get($id);

        if (isArray($enterprise)) {
            if ($enterprise['status'] == false) {
                flash($enterprise['message'], 'danger');
                return redirect()->route('enterprise.index');
            }
        }

        $enterpriseName = $enterprise['data']['enterprise']['name'];

        return view('app.enterprise.edit', [
            'title'     => message(self::$controller, 'edit'),
            'form'      => self::form($id),
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "index"),
                    'url'   => route('enterprise.index')
                ],
                [
                    'link'  => true,
                    'label' => $enterpriseName,
                    'url'   => route('enterprise.show', ['id' => $id])
                ],
                [
                    'link'  => false,
                    'label' => message('enterprise', 'edit'),
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
    * @version 1.0 2017-01-06
    */
    public function index(Request $request)
    {

        /*

        function getDirContents($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
        } else if($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}


        $path = resource_path() . "/" . "lang" . "/" . "en";
        $data = [];
        $files = getDirContents($path);


        $outputBuffer = fopen("php://output", 'w');

        $json = [];

        foreach($files as $file) {
            if (is_file($file)){
                $fullPath = explode("/", $file);
                $ctrl = $fullPath[count($fullPath) - 2];
                $data[$ctrl] = include $file;
            }
        }

        echo json_encode($data, JSON_PRETTY_PRINT);
        exit(2);

        */

        $get = function($is_active, $count) {

            $route      = 'enterpriseServiceGet';
            $version    = '1.0';
            $input      = [
                'is_active' => $is_active,
                'count'     => $count
            ];

            $client = new \App\Services\Client();
            $result = $client->execute($input, $route, $version);

            if ($result['status'] == true) {
                return $result['data']['enterprise'];
            }

            return false;

        };

        $enabled = $get(1, 0);
        if ($enabled == false) {
            return redirect()->route('enterprise.new');
        }

        $disabled = $get([0], 1);

        return view('app.enterprise.index', [
            'title' => message(self::$controller, 'index'),
            'enabled'   => $enabled,
            'disabled'  => $disabled,
            'breadcrumbs' => [
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
      * @version 1.0 2017-01-06
      */
    public function insert(Request $request)
    {
        $input      = $request->input();
        $post       = $input[self::$controller];
        $input      = $post;

        $route      = 'enterpriseServiceCreate';
        $version    = '1.1';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);

        if ($result['status'] == true) {

            $result['url']   = route("enterprise.show", ['id' => $result['data']['enterprise']['id']]);

            $input      = [
                'enterprise_id' => $result['data']['enterprise']['id'],
                'user_id'       => User::id()
            ];

            $route      = 'userEnterpriseServiceCreate';
            $version    = '1.1';

            $client     = new \App\Services\Client();
            $relation   = $client->execute($input, $route, $version);
        }

        $serviceCache   = new \ServiceCache;
        $enterprise     = $serviceCache::initialize('enterprise');
        $enterprise::reset('count');

        return response()->json($result);

    }

    /**
    * update
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-01-06
    */
    public function update(Request $request)
    {
        $input  = $request->input();
        $post   = $input[self::$controller];
        $input  = $post;

        $route   = 'enterpriseServiceUpdate';
        $version = '1.1';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);
        return response()->json($result);
    }

    /**
    * disable
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-01-07
    */
    public function disable(Request $request, $id)
    {
        $input   = ['id' => $id];

        $route   = 'enterpriseServiceDisable';
        $version = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);

        if ($result['status'] == true) {
            flash($result['message'], 'success');
        } else {
            flash($result['message'], 'danger');
        }
        return redirect()->route('enterprise.show', ['id' => $id]);
    }

    /**
    * enable
    * @param   Request $request
    * @method  post
    * @access  public
    * @return  string  json
    * @version 1.0 2017-01-07
    */
    public function enable(Request $request, $id)
    {
        $input  = ['id' => $id];

        $route   = 'enterpriseServiceEnable';
        $version = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);

        if ($result['status'] == true) {
            flash($result['message'], 'green');
        } else {
            flash($result['message'], 'red');
        }
        return redirect()->route('enterprise.show', ['id' => $id]);
    }

    /**
      * show
      * @param   Request  $request
      * @method  get
      * @access  public
      * @return  View
      * @version 1.0 2017-01-07
      */
    public function show(Request $request, $id)
    {
        $enterprise = self::get($id);

        if (isArray($enterprise)) {
            if ($enterprise['status'] == false) {
                flash($enterprise['message'], 'red');
                return redirect()->route('enterprise.index');
            }
        }

        $addresses  = ( isset($enterprise['data']['addresses']) ? $enterprise['data']['addresses'] : [] );
        $enterprise = $enterprise['data']['enterprise'];


        return view('app.enterprise.show', [
            'title'         => message(self::$controller, 'show'),
            'enterprise'    => $enterprise,
            'addresses'     => $addresses,
            'forms'         => $this->forms($id),
            'actions'   => [
                'edit'      => [
                    'visible'   => true,
                    'label'     => message(self::$controller, 'edit'),
                    'url'       => route("enterprise.edit", ['id' => $id])
                ],
                'status'    => [
                    'visible'   => true,
                    'label'     => ($enterprise['is_active'] ? message(self::$controller, 'disable') : message(self::$controller, 'enable') ),
                    'url'       => ($enterprise['is_active'] ? route('enterprise.disable', ['id' => $id]) : route('enterprise.enable', ['id' => $id]) ),
                ],
            ],
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message(self::$controller, "all"),
                    'url'   => route('enterprise.index')
                ],
                [
                    'link'  => false,
                    'label' => message(self::$controller, "show"),
                    'url'   => null
                ],
            ]
        ]);
    }

    private function forms($id)
    {
        return [
            'address' => \App::call("App\Http\Controllers\AddressController@new",
                [
                    'controller'    => 'enterprise',
                    'controller_id' => $id,
                ]
            ),
            'phone' => \App::call("App\Http\Controllers\PhoneController@new",
                [
                    'controller'    => 'enterprise',
                    'controller_id' => $id,
                ]
            ),
            'email' => \App::call("App\Http\Controllers\EmailController@new",
                [
                    'controller'    => 'enterprise',
                    'controller_id' => $id,
                ]
            ),
            'person' => \App::call("App\Http\Controllers\EnterprisePersonController@new",
                [
                    'enterprise_id' => $id,
                ]
            ),
            'followUp' => \App::call("App\Http\Controllers\FollowUpController@new",
                [
                    'controller'    => 'enterprise',
                    'controller_id' => $id,
                ]
            ),
            'scheduling' => \App::call("App\Http\Controllers\SchedulingController@new",
                [
                    'controller'    => 'enterprise',
                    'controller_id' => $id,
                ]
            ),
            'follow' => \App::call("App\Http\Controllers\Widget\FollowController@show",
                [
                    'controller'    => 'enterprise',
                    'controller_id' => $id,
                ]
            ),
            'attachment' => \App::call("App\Http\Controllers\AttachmentController@new",
                [
                    'controller'    => 'enterprise',
                    'controller_id' => $id,
                ]
            ),
        ];
    }


}
