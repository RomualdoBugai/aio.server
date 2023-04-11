<?php

namespace App\Http\Controllers\Setup;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Useful\User as User;

class SetupController extends \App\Http\Controllers\Controller
{

	protected static $controller = 'setup';

    protected static $path       = null;

    protected static function path()
    {
        $e      = new \Exception();
        $trace  = $e->getTrace();
        $action = $trace[1]['function'];
        return  implode(".", ['app', self::$controller, $action]);
    }

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2017-02-21
    */
    public function index(Request $request) {
        return view(self::path(), [
            'title'         => message(self::$controller, 'index'),
            'breadcrumbs'   => [
                [
                    'link'  => false,
                    'label' => message(self::$controller, "index"),
                    'url'   => false
                ]
            ],
            'form' => [
                'inviteUser' => \App::call("App\Http\Controllers\Setup\InviteUserController@new"),
            ],
        ]);
    }

}
