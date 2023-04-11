<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\Core\EmailBlacklist as EmailBlacklist;

class EmailBlacklistService extends Api
{

    protected static $controller = 'email-black-list';

    public function unsubscribe(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'email'     => ['required', 'email', 'exists:user,email'],
                ];
                # define messages
                $messages = [
                    'exists'    => message(self::$controller, "email-not-found"),
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $app = $input['app'];

                $check = EmailBlacklist::where("email", $input['email'])
                        ->where('app_id', $app->id)
                        ->first();

                if ($check != null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "email-out-of-mailing-list", ['email' => $input['email']])
                        ]
                    );
                }

                EmailBlacklist::create(
                    [
                        'email'     => (string) strtolower($input['email']),
                        'app_id'    => (int) $app->id
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, "email-out-of-mailing-list", ['email' => $input['email']])
                    ]
                );

            break;
        }
    }

    public function subscribe(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'email'    => ['required', 'email', 'exists:user,email'],
                ];
                # define messages
                $messages = [
                    'exists'    => messsage(self::$controller, "email-not-found")
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $app = $input['app'];

                $check = EmailBlacklist::where("email", $input['email'])
                        ->where('app_id', $app->id)
                        ->first();

                if ($check == null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => messsage(self::$controller, "email-out-of-mailing-list", ['email' => $input['email']])
                        ]
                    );
                }

                $check->delete();

                return response()->json(
                    [
                        'status'  => true,
                        'message' => messsage(self::$controller, "email-already-in-mailing-list", ['email' => $input['email']])
                    ]
                );

            break;
        }
    }

}
