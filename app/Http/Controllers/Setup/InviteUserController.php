<?php

namespace App\Http\Controllers\Setup;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;
use App\Services\Useful\User as User;

class InviteUserController extends \App\Http\Controllers\Controller {

    protected static $controller    = 'invite_user';

    protected static $fillable      = ['name', 'email'];

    protected static $guarded       = ['id'];

    /**
    * get
    * @access  protected
    * @return  array
    * @version 1.0 2017-02-09
    */
    protected static function get() {
        $input      = ['user_id' => User::id()];
        $route      = 'inviteUserServiceGet';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        return $client->execute($input, $route, $version);
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

        $pendingRequest   = [];
        $service = self::get();

        if (isArray($service)) {
            if ($service['status'] == true) {
                $pendingRequest = $service['data']['pending_request'];
            }
        }

        return view('app.setup.invite-user.index', [
            'status'            => (bool) $service['status'],
            'pendingRequests'   => $pendingRequest
        ])->render();
    }

    /**
    * new
    * @access  public
    * @return  View
    * @version 1.0 2017-02-21
    */
    public function new() {
        return view('app.setup.invite-user.new', [
            'form'  => Form::make(self::$fillable, self::$guarded, self::$controller, [])
        ])->render();
    }

    /**
      * new
      * @param   Request $request
      * @method  post
      * @access  public
      * @return  string  json
      * @version 1.0 2017-02-21
      */
    public function insert(Request $request) {
        $input      = $request->input();
        $post       = $input[self::$controller];
        $input      = $post;
        $input['user_id']   = User::id();
        $input['url']       = route('inviteUser.accept');
        $route      = 'inviteUserServiceCreate';
        $version    = '1.0';
        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);
        return response()->json($result);
    }

    /**
      * new
      * @param   Request $request
      * @method  post
      * @access  public
      * @return  string  json
      * @version 1.0 2017-02-21
      */
    public function delete(Request $request) {
        $input      = $request->input();
        $post       = $input[self::$controller];
        $input      = $post;

        $route      = 'inviteUserServiceDelete';
        $version    = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);
        return response()->json($result);
    }

    /**
      * new
      * @param   Request $request
      * @method  get
      * @access  public
      * @return  string  json
      * @version 1.0 2017-02-21
      */
    public function accept(Request $request) {
        $get = $request->input();
        return redirect()->route('user.new', $get);
    }

}
