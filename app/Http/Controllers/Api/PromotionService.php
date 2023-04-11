<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\Promotion as Promotion;

class PromotionService extends Api
{

    protected static $controller = 'promotion';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 4/08/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'           => ['required', 'numeric'],
                    'name'              => ['required', 'max:224'],
                    'description'       => ['required', 'max:224'],
                    'days'              => ['required', 'numeric'],
                    'code'              => ['required', 'max:8'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }

                $promotion = Promotion::where('user_id', $input['user_id'])
                    ->where('days', $input['days'])
                    ->where('is_active', (bool) true)
                    ->first();

                if ($promotion != null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-exists'),
                        ]
                    );
                }

                $promotion = Promotion::create(
                    [
                        'user_id'           => $user->id,
                        'name'              => $input['name'],
                        'description'       => $input['description'],
                        'days'              => $input['days'],
                        'code'              => $input['code']
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'created'),
                        'data'      => [
                            'promotion'       => $promotion
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
             * @author Romualdo Bugai
             * @date 04/08/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'     => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message('user', 'not-found'),
                    ]);
                }
                
                $promotion = Promotion::where('user_id', $user->id)
                    ->get();

                if ( $promotion->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $promotion->toArray()
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
            /**
             * @author Romualdo Bugai
             * @date 04/08/2017
             * @return void
             */
            case '1.1':
                
                $promotion = Promotion::get();

                if ( $promotion->count() > 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, 'found'),
                            'data'    => $promotion->toArray()
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

    public function update(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 04/08/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'           => ['required', 'numeric'],
                    'column'       => ['required'],
                    'status'       => ['required', 'bool']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $promotion = Promotion::find($input['id']);

                if ($promotion == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                $data = array(
                    $input['column']      => (bool) $input['status']
                );

                $promotion = Promotion::where('id', $promotion->id)->update($data);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'updated')
                    ]
                );

            break;
        }
    }

    public function check(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 04/08/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'code'     => ['required', 'max:8']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $promotion = Promotion::where('code', $input['code'])
                    ->first();

                if ($promotion == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                if($promotion->is_active == false){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'canceled'),
                        ]
                    );
                }

                if($promotion->approved == false){
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-approved'),
                        ]
                    );
                }

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $promotion->getAttributes()
                    ]
                );                

            break;
        }
    }
}
