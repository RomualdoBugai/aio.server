<?php

namespace App\Http\Controllers\Widget;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Useful\User as User;

class FollowController extends \App\Http\Controllers\Controller {


    private static final function check($controller, $controller_id)
    {
        $input['user_id']  = User::id();

        switch ($controller) {
            case 'enterprise':
                $input['enterprise_id'] = $controller_id;

                $route   = 'userEnterpriseFollowServiceCheck';
                $version = '1.0';
            break;
        }

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);
        return (bool) $result['status'];
    }

    /**
    * show
    * @param   Request  	$request
    * @param   string 	$controller (enterprise)
    * @param   int 		$controller_id (enterprise@id)
    * @access  public
    * @return  string 	View
    * @version 1.0 2017-02-17
    */
    public function show(Request $request, $controller, $controller_id) {

        $input['user_id']   = User::id();

        switch ($controller) {
            case 'enterprise':
                $input['enterprise_id'] = $controller_id;

                $route      = 'userEnterpriseFollowServiceCheck';
                $version    = '1.0';
            break;
        }

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);

        return view('app.widget.follow.form', [
            'following' 	=> (bool) self::check($controller, $controller_id),
            'controller'	=> (string) $controller,
            'controller_id'	=> (string) $controller_id
        ])->render();
    }

    /**
    * update
    * @param   Request  $request
    * @param   string 	$controller (enterprise)
    * @param   int 		$controller_id (enterprise@id)
    * @access  public
    * @return  string 	View
    * @version 1.0 2017-02-17
    */
    public function update(Request $request, $controller, $controller_id) {

        $input['user_id']  = User::id();

        switch ($controller) {
            case 'enterprise':

                if (self::check($controller, $controller_id) == false)
                {
                    $input['enterprise_id'] = $controller_id;
                    $route   = 'userEnterpriseFollowServiceCreate';
                    $version = '1.0';
                } else {
                    $input['enterprise_id'] = $controller_id;
                    $route   = 'userEnterpriseFollowServiceDelete';
                    $version = '1.0';
                }

            break;
        }

        $client = new \App\Services\Client();
        return $client->execute($input, $route, $version);
    }

    /**
    * update
    * @param   Request  $request
    * @param   string 	$controller (enterprise)
    * @access  public
    * @return  string 	View
    * @version 1.0 2017-02-20
    */
    public function index(Request $request, $controller) {

        $input['user_id']  = User::id();

        switch ($controller) {
            case 'enterprise':

                $route   = 'userEnterpriseFollowServiceGet';
                $version = '1.0';

            break;
        }

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);

        return view("app.widget.follow.{$controller}.index", [
            'status' 	=> (bool) $result['status'],
            'data' 	    => ( $result['status'] == true ? $result['data']['user_' . $controller . '_follow'] : [] )
        ])->render();
    }

}
