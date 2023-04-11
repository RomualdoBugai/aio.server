<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Useful\User as User;
use Carbon\Carbon;

class MeController extends Controller {

    public function index() {
        $client         = new \App\Services\Client();
        $session        = $client->execute(['id' => User::session()],   'userSessionServiceOne', '1.0');

        $userSettings   = $client->execute(['id' => User::id()],        'userSettingsServiceOne', '1.0');

        $internationalization   = config('internationalization');
        extract($internationalization);

        if ($userSettings['status'] == false) {
            $userSettings['input_date_format']  = $internationalization['default']['input_date_format'];
            $userSettings['timezone']           = $internationalization['default']['timezone'];
            $userSettings['date_format']        = $internationalization['default']['date_format'];
        } else {
            $userSettings['timezone']           = $userSettings['data']['user_settings']['timezone'];
            $userSettings['date_format']        = $userSettings['data']['user_settings']['date_format'];
            $userSettings['input_date_format']  = $userSettings['data']['user_settings']['input_date_format'];
        }

        $timezonesService = $timezones(true);

        $timezones  = [];

        if (isArray($timezonesService)) {
            foreach($timezonesService as $timezone) {
                $timezones[$timezone['countryName']][$timezone['zoneName']] = Carbon::createFromTimestamp($timezone['timestamp'])->format("H:i A");
            }
        }

        $date_format = [
            'Y-m-d h:i A'      => ["yyyy-mm-dd hh:ii aa",   Carbon::now()->format("Y-m-d h:i A")    ],
            'Y-m-d h:i'        => ["yyyy-mm-dd hh:ii",      Carbon::now()->format("Y-m-d h:i")      ],
            'd/m/Y h:i A'      => ["dd/mm/yyyy hh:mm aa",   Carbon::now()->format("d/m/Y h:i A")    ],
            'd/m/Y h:i'        => ["dd/mm/yyyy hh:mm",      Carbon::now()->format("d/m/Y h:i")      ],
            'd F, y h:i A'     => ["dd M, yyyy hh:ii A",    Carbon::now()->format("d F, Y h:i A")   ],
            'd F, y h:i'       => ["dd M, yyyy hh:ii",      Carbon::now()->format("d F, Y h:i")     ],
            'D d F, y h:i A'   => ["D d F, yyyy hh:ii A",   Carbon::now()->format("D d F, Y h:i A") ],
            'D d F, y h:i'     => ["D d F, yyyy hh:ii",     Carbon::now()->format("D d F, Y h:i")   ]
        ];

        $input_date_format = [
            'Y-m-d'      => [message('common', 'yyyy-mm-dd'),   Carbon::now()->format("Y-m-d")],
            'd/m/Y'      => [message('common', 'dd/mm/yyyy'),   Carbon::now()->format("d/m/Y")]
        ];

        return view('app.me.index', [
            'title'     => message('me', 'title'),
            'user'      => $session['data']['user'],
            'session'   => $session['data']['session'],
            'breadcrumbs' => [
                [
                    'link'  => false,
                    'label' => message("me", "index"),
                    'url'   => null,
                ], [
                    'link'  => false,
                    'label' => message("me", "show"),
                    'url'   => null
                ]
            ],
            'userSettings' => $userSettings,
            'form' => view('app.me.form', [
                'userSettings' => $userSettings,
                'options' => [
                    'timezones'         => $timezones,
                    'date_format'       => $date_format,
                    'input_date_format' => $input_date_format
                ]
            ])->render(),
            'timestamp' => [
                'server' => Carbon::now()->format("H:i:s")
            ]
        ]);
    }

    /**
      * update
      * @param   Request $request
      * @method  post
      * @access  public
      * @return  string  json
      * @version 1.0 2016-01-08
      */
    public function update(Request $request) {
        $input  = $request->input();
        $post   = $input['userSettings'];
        $input  = $post;
        $input['id']    = User::id();

        $route   = 'userSettingsServiceUpdate';
        $version = '1.0';

        $client = new \App\Services\Client();
        $result = $client->execute($input, $route, $version);
        return response()->json($result);
    }

}
