<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;
use App\Models\UserEqual as UserEqual;
use App\Models\UserInviteRequest as UserInviteRequest;

class InviteUserService extends Api
{
    protected static $controller = 'user-invite';

    public function create(Request $request) {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 2017-02-20
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'     => ['required', 'numeric'],
                    'email'       => ['required', 'email'],
                    'name'        => ['required'],
                    'url'         => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

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

                # check invete
                $check = UserInviteRequest::where('email', $input['email'])
                ->where('user_id', $user->id)
                ->where('is_active', (bool) true)
                ->first();

                if ($check != null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'already-exists')
                    ]);
                }

                # check user equal
                /*$check = UserEqual::where('user_id', $user->id)
                ->where('is_active', (bool) true)
                ->first();

                if ($check != null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'already-exists-confirmed')
                    ]);
                }*/

                $another = User::where('email', $input['email'])->first();

                $invite = UserInviteRequest::create(
                    [
                        'user_id'   => $user->id,
                        'email'     => $input['email'],
                        'name'      => $input['name'],
                        'is_active' => true,
                        'token'     => strtolower(md5( uniqid() . $user->id . $input['email'] . $input['name'] )),
                        'app_id'    => $app->id
                    ]
                );

                $raw = [
                    'app'       => (string) strtolower($app->name),
                    'token'     => (string) $invite->token,
                    'email'     => (string) $invite->email,
                    'from'      => (int)    ( $user->id * 1000 ),
                    'name'      => (string) ( $invite->name ),
                    'to'        => (int)    ( $another == null ? 0 : $another->id * 1000 )
                ];

                $url    = $input['url'] . "?" . http_build_query($raw);

                $title  = message('common', 'mail.invite-user.title', ['name'  => ownName($user->name), 'app'   => $app->name]);
                $resume = json_decode($app->resume, true);

                $data   = [
                    'hasInvited' => $user,
                    'wasInvited' => $invite,
                    'app'        => $app,
                    'url'        => $url,
                    'user'       => $user,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.invite-user', $data, function($message) use (&$invite, &$title) {
                    $message->to($invite->email, $invite->name)->subject($title);
                });

                return response()->json([
                    'status'    => true,
                    'message'   => message(self::$controller, 'send'),
                    'data'      => [
                        'request'       => $invite->id,
                        'created_at'    => $invite->created_at
                    ]
                ]);

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
                    'user_id'     => ['required', 'numeric'],
                    'email'       => ['required', 'email'],
                    'name'        => ['required'],
                    'request'     => ['required'],
                    'url'         => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

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

                # check invete
                $check = UserInviteRequest::where('email', $input['email'])
                ->where('user_id', $user->id)
                ->where('is_active', (bool) true)
                ->where('request', $input['request'])
                ->first();

                if ($check != null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'already-exists')
                    ]);
                }

                # check user equal
                /*$check = UserEqual::where('user_id', $user->id)
                ->where('is_active', (bool) true)
                ->first();

                if ($check != null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'already-exists-confirmed')
                    ]);
                }*/

                $another = User::where('email', $input['email'])->first();

                $invite = UserInviteRequest::create(
                    [
                        'user_id'   => $user->id,
                        'email'     => $input['email'],
                        'name'      => $input['name'],
                        'token'     => strtolower(md5( uniqid() . $user->id . $input['email'] . $input['name'] )),
                        'app_id'    => $app->id,
                        'is_active' => true,
                        'request'   => $input['request']
                    ]
                );

                $raw = [
                    'app'       => (string) strtolower($app->name),
                    'token'     => (string) $invite->token,
                    'email'     => (string) $invite->email,
                    'from'      => (int)    ( $user->id * 1000 ),
                    'name'      => (string) ( $invite->name ),
                    'to'        => (int)    ( $another == null ? 0 : $another->id * 1000 ),
                    'request'   => (string) ( $invite->request )
                ];

                $url    = $input['url'] . "?" . http_build_query($raw);

                $title  = message('common', 'mail.invite-user.title', ['name'  => ownName($user->name), 'app'   => $app->name]);
                $resume = json_decode($app->resume, true);

                $data   = [
                    'hasInvited' => $user,
                    'wasInvited' => $invite,
                    'app'        => $app,
                    'url'        => $url,
                    'user'       => $user,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.invite-user', $data, function($message) use (&$invite, &$title) {
                    $message->to($invite->email, $invite->name)->subject($title);
                });

                return response()->json([
                    'status'    => true,
                    'message'   => message(self::$controller, 'send'),
                    'data'      => [
                        'request'       => $invite->id,
                        'created_at'    => $invite->created_at
                    ]
                ]);

            break;
        }
    }

    public function check(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 29/09/2016
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'email'         => ['required', 'email'],
                    'invite_token'  => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $request = UserInviteRequest::where('token', $input['invite_token'])
                ->where('email', $input['email'])
                ->where('is_active', 1)
                ->first();

                if ($request == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'found'),
                ]);

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
                    'user_id'       => ['required', 'numeric'],
                    'email'         => ['required', 'email'],
                    'request'       => ['required'],
                    'invite_token'  => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

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

                $request = UserInviteRequest::where('email', $input['email'])
                ->where('token', $input['invite_token'])
                //->where('app_id', $app->id)
                ->where('request', $input['request'])
                ->where('is_active', 1)
                ->first();

                if ($request == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'found'),
                ]);

            break;
        }
    }

    public function disable(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 29/09/2016
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'    => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $request = UserInviteRequest::find($input['id']);

                if ($request == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                $update = [
                    'is_active'       => (bool) false,
                ];

                UserInviteRequest::where('id', $input['id'])
                ->update($update);

                //UserInviteRequest::destroy($input['id']);

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'disable'),
                ]);

            break;

            /**
             * @author  William Novak
             * @date    2017-05-24
             * @return  void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $input = $request->input();
                # define rules
                $rules = [
                    'email'         => ['required', 'email'],
                    'invite_token'  => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $request = UserInviteRequest::where('token', $input['invite_token'])
                ->where('email', $input['email'])
                ->where('is_active', 1)
                ->first();

                if ($request == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                $update = [
                    'is_active'       => (bool) false,
                ];

                UserInviteRequest::where('id', $input['id'])
                ->update($update);

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'disable'),
                ]);

            break;
        }
    }

    public function delete(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 29/09/2016
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'id'    => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $request = UserInviteRequest::find($input['id']);

                if ($request == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                UserInviteRequest::destroy($input['id']);

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'deleted'),
                ]);

            break;
        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 2017-02-15
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'     => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

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

                $request = UserInviteRequest::where('user_id', $input['user_id'])
                ->where('is_active', true)
                ->get();

                if ($request->count() == 0) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'found'),
                    'data'    => [
                        'pending_request' => $request->toArray()
                    ]
                ]);

            break;
            /**
             * @author Romualdo Bugai
             * @date 31/05/2017
             * @return void
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'user_id'     => ['required', 'numeric'],
                    'request'     => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                $app = $input['app'];

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

                $request = UserInviteRequest::where('user_id', $input['user_id'])
                ->where('request', $input['request'])
                ->where('app_id', $app->id)
                ->where('is_active', true)
                ->get();

                if ($request->count() == 0) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'found'),
                    'data'    => [
                        'pending_request' => $request->toArray()
                    ]
                ]);

            break;

            /**
             * @author Romualdo Bugai
             * @date 06/06/2017
             * @return void
             */
            case '1.2':

                $input = $request->input();
                # define rules
                $rules = [
                    'request'     => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $request = UserInviteRequest::where('request', $input['request'])
                ->where('is_active', true)
                ->get();

                if ($request->count() == 0) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found'),
                    ]);
                }

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'found'),
                    'data'    => $request->toArray()
                ]);

            break;
        }
    }

}
