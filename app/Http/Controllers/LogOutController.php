<?php

namespace App\Http\Controllers;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Useful\User as User;

class LogOutController extends Controller {

    const SERVICE = 'logOutServiceCheck';

    public function logOut(Request $request) {
        $userSession = userSession();

        if (!haveSession()) {
            return self::logIn();
        }

        $input['id']             = User::session();

        $client = new \App\Services\Client();
        $result = $client->execute($input, self::SERVICE, '1.0');

        /*
        if ($result['status'] == true) {
            Flash::warning($result['message']);
        } else {
            Flash::success($result['message']);
        }
        */

        $request->session()->forget('key');
        $request->session()->flush();

        return self::logIn();
    }

    private static final function logIn() {
        return Redirect::to(route('logIn'));
    }

}
