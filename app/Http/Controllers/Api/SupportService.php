<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Services\Useful\Auth as Auth;
use App\Services\Useful\UserData as UserData;

use App\Models\User as User;
use App\Models\App as App;
use App\Models\Support as Support;

class SupportService extends Api
{

    protected static $controller = 'support';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 2017-03-20
             * @return void
             */
            case '1.0':
                $input  = $request->input();
                # define rules
                $rules  = [
                    'user_id'   => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found')
                    ]);
                }

                $app    = $request->input('app');

                $check  = AppSupport::where('user_id', $input['user_id'])->first();

                if ($check != null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('app', 'support.user-already-exists')
                    ]);
                }

                $appSupport = AppSupport::create([
                    'user_id'   => $user->id,
                    'app_id'    => $app->id,
                    'is_active' => 1
                ]);

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'support.created'),
                        'data' => [
                            'app_support' => $appSupport->id
                        ]
                    ]
                );

            break;

        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {

            /**
             * @author William Novak
             * @date 2017-03-19
             * @return void
             */
            case '1.0':
                $input      = $request->input();

                $app        = $input['app'];

                $assistents = $app->support;

                if ($assistents->count() == 0) {
                    return response()
                    ->json([
                        'status'  => false,
                        'message' => message('user', 'not-found')
                    ]);
                }

                $data       = [];

                foreach($assistents as $assistent) {
                    $data[] = $assistent->user->makeHidden(['created_at', 'updated_at', 'password'])->toArray();
                }

                return response()->json([
                    'status'  => true,
                    'message' => message('user', 'found'),
                    'data'    => [
                        'app_support' => $data
                    ],

                ]);

            break;

        }
    }

}
