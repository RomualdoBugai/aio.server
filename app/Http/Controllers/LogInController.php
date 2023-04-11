<?php

namespace App\Http\Controllers;
use Redirect;
use App\Http\Requests;
use App\Services\Useful\User;
use Illuminate\Http\Request as Request;

class LogInController extends Controller {

    const SERVICE = "logInServiceCheck";

    public function index() {
        if (haveSession()) {
            return Redirect::to(route('welcome'));
        }

        $data = [
            'title' => message('common', 'log-in')
        ];

        return view('app.login.index', $data);
    }

    public function logIn(Request $request) {

        $input                  = $request->input();
        $input                  = $input['logIn'];
        $input['password']      = md5($input['password']);
        $input['startTrial']    = false;
        $input['localSession']  = false;

        $client = new \App\Services\Client();
        $result = $client->execute($input, self::SERVICE, '1.0');

        if ($result['status'] == true) {
            $session        = $result['data'];

            $client         = new \App\Services\Client();
            $userSettings   = $client->execute(['id' => $session['user']],        'userSettingsServiceOne', '1.0');

            if ($userSettings['status'] == false) {
                $internationalization   = config('internationalization');
                extract($internationalization);

                $userSettings['timezone']               = $internationalization['default']['timezone'];
                $userSettings['date_format']            = $internationalization['default']['date_format'];
                $userSettings['input_date_format']      = $internationalization['default']['input_date_format'];
            } else {
                $userSettings['timezone']               = $userSettings['data']['user_settings']['timezone'];
                $userSettings['date_format']            = $userSettings['data']['user_settings']['date_format'];
                $userSettings['input_date_format']      = $userSettings['data']['user_settings']['input_date_format'];
            }

            $session['timezone']                        = $userSettings['timezone'];
            $session['date_format']                     = $userSettings['date_format'];
            $session['input_date_format']               = $userSettings['input_date_format'];

            \App\Services\Useful\User::create($session);
        }

        return response()->json($result);
    }

}
