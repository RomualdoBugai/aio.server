<?php

namespace App\Services\Useful;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Route;

use Session;
use Log;

use App\Models\User as User;
use App\Models\UserEqual as UserEqual;
use App\Models\UserSession as UserSession;
use App\Models\ConfirmEmail as ConfirmEmail;

use Jenssegers\Agent\Agent;

use App\Services\Useful\UserData as UserData;

class Auth
{

	const INVALID_PASSWORD      = 'invalid-password';
	const ACCOUNT_NOT_FOUND     = 'email-not-found';
	const AUTH_SUCCESS          = 'welcome';
    const AUTH_DENIED           = 'blocked-account';
    const AUTH_EXIT             = 'come-back-soon';
    const AUTH_INVALID          = 'no-user-active-session';
    const USER_APP_NOT_ALLOWED  = 'user-app-not-allowed';

    protected $userData     = [
        'user.id',
        'user.name',
        'user.logged_in',
        'userSession.id',
        'inheritance.id'
    ];

	protected $ignoreApp = false;

    public function setIgnoreApp($value)
    {
        $this->ignoreApp = (bool) $value;
        return $this;
    }

    public function getIgnoreApp()
    {
        return $this->ignoreApp;
    }

    public function __construct($confirmedEmail = false)
    {
        $this->confirmedEmail = $confirmedEmail;
    }

    protected function saveSession($userData = [])
    {
        if (is_array($userData))
        {
            if (array_key_exists("user.id", $userData))
            {
                #Session::flush();
                foreach ($userData as $k => $v)
                {
                    if (in_array($k, $this->userData))
                    {
                        Session::put("{$k}", $v);
                    }
                }
            } else {
                Log::error("o array passado na fun&ccedil;ao " . __FUNCTION__ . " n&atilde;o est&aacute; v&aacute;lido");
            }
        } else {
            Log::error("a fun&ccedil;ao " . __FUNCTION__ . " espera por um array");
        }
    }

    private function clearUserData()
    {
        Session::flush();
    }

    private $confirmedEmail = false;

    public function setConfirmedEmail($value = false)
    {
        $this->setConfirmedEmail = (bool) $value;
        return $this;
    }


    private $startTrial = true;

    /**
     *
     * @param boolean $value
     * @access public
     * @return Auth
     *
     */
    public function setStartTrial($value = true)
    {
        $this->startTrial = $value; return $this;
    }

    /**
     *
     * @param instance of UserData $userdata
     * @access public
     * @return Auth
     *
     */
    public function initilize(UserData $userdata)
    {

        $email    = $userdata->email;
        $password = $userdata->password;
        $user     = User::check($email, $password);

        # if user has found
        if (!is_null($user)) {
            # check if user is blocked, if return false there is not blocked
            $isBlocked = \App\Models\BlockedUser::isBlocked($user);
            if ($isBlocked == false) {
				return [
		            'status'  => (bool)   false,
		            'message' => (string) message('auth', self::AUTH_DENIED),
		        ];
            } else {

                if ($this->confirmedEmail == true) {

                    $confirmEmail = ConfirmEmail::where('user_id', $user->id)
                    ->where('is_confirmed', true)
                    ->first();

                    if ($confirmEmail == null) {
                        return [
                            'status'  => false,
                            'message' => message('auth', 'email-not-confirmed', ['email' => $email])
                        ];
                    }
                }

                $app = \App\Models\App::where("name", $this->getApp())->first();

                if ($this->getIgnoreApp() == false) {

                    $checkApp = \App\Models\UserApp::where('user_id', $user->id)
                    ->where('app_id', $app->id)
                    ->first();

                    if ($checkApp == null) {
                        return [
                            'status'  => false,
                            'message' => message('auth', self::USER_APP_NOT_ALLOWED, ['email' => $email])
                        ];
                    }
                }

                $agent = new Agent();
                $info = json_encode(
                    [
                        'browser'           => $agent->browser(),
                        'browserVersion'    => $agent->version($agent->browser()),
                        'plataform'         => $agent->platform(),
                        'plataformVersion'  => $agent->version($agent->platform())
                    ]
                );
                $affectedRows = UserSession::where('user_id', $user->id)
                ->where('is_active', true)
                ->update(array('is_active' => false));

                # define to inheritance for user, maybe is someone else or himself
                $inheritance = $user->id;

                # check if user is part of inheritance
                $equalUser = $user->isEqualAnotherUser;
                if ($equalUser != null) {
                    $inheritance = $equalUser->equal_user_id;
                }

                $app = \App\Models\App::where('name', $this->getApp())->first();

                # create a session data
				$request                 = new \Request;
                $userSession             = new UserSession();
                $userSession->user_id    = $user->id;
                $userSession->info       = $info;
                $userSession->is_active  = 1;
                $userSession->ip_address = $request::ip();
                $userSession->app_id     = $app->id;
                $userSession->save();

                # flush user data
                $this->clearUserData();

                if ($this->getLocalSession() === true) {

    				# save user data
                    $userData = [
                        'user.id'          => $user->id,
                        'user.name'        => $user->name,
                        'user.logged_in'   => date("Y-m-d H:i:s"),
                        'userSession.id'   => $userSession->id,
                        'inheritance.id'   => $inheritance,
                        'app.name'         => $this->getApp(),
                    ];

    				# save this session
                    $this->saveSession($userData);

                }

                # check if start trial is enabled
                if ($this->startTrial == true) {
                    # check if inheritance not equal a user
                    if ($equalUser == null) {

                        /**
                         * add user in table of trial date interval
                         * @package Vault
                         */
                        $trialPlanInUse = false;

                        $userTrial = new \App\Models\UserTrial;
                        $userTrial = $userTrial::where('user_id', $user->id)->first();
                        if ( $userTrial != null ) {
                            $userPlan = new \App\Models\UserPlan;
                            $userPlan = $userPlan::where('user_id', $user->id)->first();
                            if ($userPlan != null) {
                                Session::put("app.userPlan", $userPlan->plan_id);
                                if ($userPlan->plan_id == 1 ) {
                                    $trialPlanInUse = true;
                                    Session::put("app.trialPeriod", true);
                                }
                            }
                            if ($trialPlanInUse == true) {
                                $today   = strtotime(date("Y-m-d"));
                                $endDate = strtotime($userTrial->end_at);

                                $ofToday = new \DateTime(date("Y-m-d"));
                                $untilAnotherDay = new \DateTime($userTrial->end_at);
                                $interval  = $ofToday->diff($untilAnotherDay);
                                Session::put("app.trialPeriodDaysLeft", $interval->format('%a'));

                                if ($today > $endDate) {
                                    Session::put("app.disabledTrial", true);
                                }
                            }
                        }
                    }
                }

                return [
		            'status'  => (bool)   true,
		            'message' => (string) trans(self::AUTH_SUCCESS),
                    'data'    => [
                        'user'    => (int)    $user->id,
                        'session' => (int)    $userSession->id,
                        'name'    => (string) $user->name
                    ],
		        ];
            }

        } else {
            # if email ou password not match
            $user = User::getOneByEmail($email);
			return [
				'status'  => (bool)   false,
				'message' => (string) ( is_null($user) ? message("auth", self::ACCOUNT_NOT_FOUND, ['email' => $email]) : message("auth", self::INVALID_PASSWORD) ),
			];

        }
    }

    protected $app = 'vault';

    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }

    public function getApp()
    {
        return $this->app;
    }

    protected $localSession = true;

    public function setLocalSession($localSession)
    {
        $this->localSession = $localSession;
        return $this;
    }

    public function getLocalSession()
    {
        $this->localSession;
    }

    public function logOutSession()
    {
        $user = session('user.id');
        $name = session('user.name');
        Session::flush();
        if ($user > 0) {
            $affectedRows = UserSession::where('user_id', $user)
            ->where('is_active', true)
            ->update(
                ['is_active' => false]
            );
        }
		return [
			'status'  => (bool)   ($user > 0),
			'message' => (string) message('auth', ($user > 0 ? self::AUTH_EXIT : self::AUTH_INVALID) ),
            'user' => array(
                'name' => $name
            )
		];
    }

    public function logOutService(User $user)
    {
        if ($user->id == null) {
            return [
    			'status'  => (bool)   false,
    			'message' => (string) message('auth', self::AUTH_INVALID)
    		];
        }

        $app = \App\Models\App::where("name", $this->getApp())->first();

        $haveSession = UserSession::where('user_id', $user->id)
        ->where('is_active', true)
        ->where('app_id', $app->id)
        ->first();

        if ($haveSession == null) {
            return [
    			'status'  => (bool)   false,
    			'message' => (string) message('auth', self::AUTH_INVALID)
    		];
        }

        $haveSession = UserSession::where('user_id', $user->id)
        ->where('is_active', true)
        ->where('app_id', $app->id)
        ->update(['is_active' => false]);

        return [
			'status'  => (bool)   true,
			'message' => (string) message('auth', self::AUTH_EXIT, ['name' => $user->name]),
		];
    }



}
