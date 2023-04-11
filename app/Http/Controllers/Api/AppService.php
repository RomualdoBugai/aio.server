<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\App as App;

class AppService extends Api
{

    protected static $controller = 'app';

    public function one(Request $request)
    {
        switch ($request->version) {
            /**
             * retrive one app
             *
             * @author  William Novak
             * @date    2017-02-09
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $app = $input['app'];

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $app->getAttributes()
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 30/05/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'     => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $data = App::where('id', $input['id'])
                ->first();

                if ( $data->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $data->toArray()
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]
                );

            break;
        }
    }

}
