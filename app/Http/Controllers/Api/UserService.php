<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Services\Useful\Auth as Auth;
use App\Services\Useful\UserData as UserData;

use App\Models\User as User;
use App\Models\App as App;
use App\Models\BlockedUser as BlockedUser;

class UserService extends Api
{

    protected static $controller = 'user';

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'name'          => ['required', 'min:7', 'max:64'],
                    'email'         => ['required', 'email', 'unique:user,email'],
                    'password'      => ['required', 'min:32', 'max:32']
                ];
                # define messages
                $messages = [
                    'min'           => message('change-password', 'invalid-encryption'),
                    'max'           => message('change-password', 'invalid-encryption'),
                    'unique'        => message(self::$controller, 'email-unavailable', ['email' => $input['email']])
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $app            = $request->input('app');
                $user           = new User;
                $user->name     = (string) trim(strtolower($input['name']));
                $user->email    = (string) trim(strtolower($input['email']));
                $user->password = (string) trim($input['password']);
                $user->save();

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found')
                    ]);
                }

                # email já confirmado
                $store = \App\Models\ConfirmEmail::create(
                    [
                        'user_id' => $user->id,
                        'email'   => $user->email,
                        'is_confirmed' => true,
                        'verify'  => md5(date('Ymdhis'))
                    ]
                );

                #
                $userApp          = new \App\Models\UserApp;
                $userApp->user_id = $user->id;
                $userApp->app_id  = $app->id;
                $userApp->save();

                $title  = message('common', 'mail.user-welcome.title', ['name'  => ownName($user->name), 'app'   => $app->name]);
                $resume = json_decode($app->resume, true);

                $data = [
                    'user' => $user,
                    'app'  => $app,
                    'url'  => $app->url,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('account.welcome', $data, function($message) use (&$user, &$title) {
                    $message->to($user->email, $user->name)->subject($title);
                });

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'email-send'),
                        'data' => [
                            'id'    => (int)    $user->id,
                            'name'  => (string) $user->name,
                            'email' => (string) $user->email
                        ]
                    ]
                );

            break;

            /**
             * @author William Novak
             * @date 19/10/2016
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'name'          => ['required', 'min:7', 'max:64'],
                    'email'         => ['required', 'email', 'unique:user,email'],
                    'password'      => ['required', 'min:32', 'max:32'],
                    'confirmEmail'  => ['required', 'boolean'],
                    'url'           => ['required_if:confirmEmail,true']
                ];
                # define messages
                $messages = [
                    'min'            => message(self::$controller, 'invalid-encryption'),
                    'max'            => message(self::$controller, 'invalid-encryption'),
                    'unique'         => message(self::$controller, 'email-unavailable')
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $app            = $request->input('app');
                $user           = new User;
                $user->name     = (string) trim(strtolower($input['name']));
                $user->email    = (string) trim(strtolower($input['email']));
                $user->password = (string) trim($input['password']);
                $user->save();

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found')
                    ]);
                }

                $confirmEmailService = [
                    'status'    => false,
                    'message'   => message(self::$controller, 'not-used')
                ];

                if ($input['confirmEmail'] == true) {
                    $store = \App\Models\ConfirmEmail::create(
                        [
                            'user_id' => $user->id,
                            'email'   => $user->email,
                            'is_confirmed' => false,
                            'verify'  => md5(date('Ymdhis'))
                        ]
                    );
                    $confirmEmail = \App\Models\ConfirmEmail::find($store->id);

                    # security
                    $key    = $confirmEmail->id;
                    $verify = $confirmEmail->verify;

                    # build query
                    $raw = [
                        'i' => $key,
                        'v' => $verify,
                        'e' => $user->email
                    ];

                    $url = $input['url'] . "?" . http_build_query($raw);

                    $title  = message('common', 'mail.user-confirm-email.title');
                    $resume = json_decode($app->resume, true);

                    $data = [
                        'user'  => $user,
                        'url'   => $url,
                        'app'   => $app,
                        'template'      => [
                            'title'     => $title,
                            'language'  => \App::getLocale(),
                            'footer'    => $resume[\App::getLocale()]
                        ]
                    ];

                    Mail::send('account.confirm-email', $data, function($message) use (&$user, &$title){
                        $message->to($user->email, $user->name)->subject($title);
                    });

                    $confirmEmailService = [
                        'status'    => true,
                        'message'   => message(self::$controller, 'email-send')
                    ];

                } else {

                    $title  = message('common', 'mail.user-welcome.title', ['name'  => ownName($user->name), 'app'   => $app->name]);
                    $resume = json_decode($app->resume, true);

                    $data = [
                        'user' => $user,
                        'app'  => $app,
                        'url'  => $app->url,
                        'template'      => [
                            'title'     => $title,
                            'language'  => \App::getLocale(),
                            'footer'    => $resume[\App::getLocale()]
                        ]
                    ];

                    Mail::send('account.welcome', $data, function($message) use (&$user, &$title) {
                        $message->to($user->email, $user->name)->subject($title);
                    });

                }

                #
                $userApp          = new \App\Models\UserApp;
                $userApp->user_id = $user->id;
                $userApp->app_id  = $app->id;
                $userApp->save();

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'check-your-email'),
                        'data' => [
                            'id'    => (int)    $user->id
                        ],
                        'confirmEmail' => (array) $confirmEmailService
                    ]
                );

            break;
        }
    }

    public function fromInvite(Request $request)
    {
        $post     = $request->input('createUserAccount');
        $invite   = InviteUser::find($post['invite']);
        if ($invite != null) {
            $name     = $invite->name;
            $email    = $invite->email;
            $password = trim(md5($post['password']));

            # create user account
            $user           = new User;
            $user->name     = $name;
            $user->email    = $email;
            $user->password = $password;
            $user->save();

            # ties the user to another user
            $userEqual                = new UserEqual;
            $userEqual->user_id       = $user->id;
            $userEqual->equal_user_id = $invite->user_id;
            $userEqual->save();

            # turn of
            $invite->update(array('is_active' => false));

            $auth = new Auth;
    		$userData = new UserData($email, $post['password']);
    		return $auth->initilize($userData, false);
        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 26/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'email'        => ['required', 'email'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status == false) {
                    return response()->json($validate);
                }

                $user = new User;
                $user = $user::where('email', $input['email'])->first();

                if ($user == null) {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found')
                    ]);
                }

                return response()->json([
                    'status'  => true,
                    'data'    => [
                        'id'   => $user->id,
                        'name' => $user->name
                    ],
                    'message' => message(self::$controller, 'found')
                ]);

            break;

            /**
             * @author William Novak
             * @date 26/10/2016
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'        => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status == false) {
                    return response()->json($validate);
                }

                $user = User::find($input['id']);

                if ($user == null)
                {
                    return response()->json([
                        'status'  => false,
                        'message' => message(self::$controller, 'not-found')
                    ]);
                }

                $user = $user->makeHidden('password')->toArray();

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'found'),
                    'data'    => [
                        'user'  => $user
                    ]

                ]);

            break;

            /**
             * @author William Novak
             * @date 2017-02-09
             * @return void
             */
            case '1.2':
                $input  = $request->input();

                $app    = $input['app'];
                $users  = $app->users()->get();

                $result = [];

                foreach($users as $user) {
                    $result[] = $user->user->makeHidden('password')->toArray();
                }

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'found'),
                    'data'    => [
                        'users' => $result
                    ]
                ]);

            break;

            /**
             * @author Romualdo Bugai
             * @date 07/06/1994
             * @return void
             */
            case '1.3':
                $input  = $request->input();

                $users = User::get();

                return response()->json([
                    'status'  => true,
                    'message' => message(self::$controller, 'found'),
                    'data'    => $users
                ]);

            break;
        }
    }

    public function disable(Request $request)
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
                    'user_id'     => ['required', 'exists:user,id'],
                    'description' => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $app   = $input['app'];

                $check = BlockedUser::where('app_id', $app->id)
                ->where('user_id', $input['user_id'])
                ->first();

                if ($check != null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'already-disabled'),
                        ]
                    );
                }

                BlockedUser::create(
                    [
                        'user_id'     => (int)    $input['user_id'],
                        'app_id'      => (int)    $app->id,
                        'description' => (string) strtolower(trim($input['description']))
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'disabled'),
                    ]
                );

            break;
        }
    }

    public function enable(Request $request)
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
                    'user_id'     => ['required', 'exists:user,id'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $app   = $input['app'];

                $check = BlockedUser::where('app_id', $app->id)
                ->where('user_id', $input['user_id'])
                ->first();

                if ($check == null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-disabled'),
                        ]
                    );
                }

                $check->delete();

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'enabled'),
                    ]
                );

            break;
        }
    }

    public function one(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 03/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'id'    => ['required'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $user = User::find($input['id']);

                if ($user == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => [
                            'user' => $user->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

    public function check(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author Romualdo Bugai
             * @date 30/05/2017
             * @return void
             */
            case '1.0':

                $input = $request->input();
                # define rules
                $rules = [
                    'email'             => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);


                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::where('email', $input['email'])
                ->first();

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                    ]
                );

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
                    'email'             => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);


                if ($validate->status === false) {
                    return response()->json($validate);
                }

                $user = User::where('email', $input['email'])
                ->first();

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found'),
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'id'      => $user->id
                    ]
                );

            break;
            
        }
    }
}
