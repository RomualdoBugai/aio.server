<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\App as Model;
use App\Models\Api as Api;

class App {

    const SERVICE_NOT_FOUND             = 'service-not-found';
    const APP_NOT_FOUND                 = 'app-not-found';
    const VERSION_UNAVAILABLE           = 'version-unavailable';
    const REQUEST_METHOD_NOT_ALLOWED    = 'request-method-not-allowed';


    protected static $guarded           = [

        #pagseguro transaction
        'promotionServiceCreate'                => ['1.0'],
        'promotionServiceGet'                   => ['1.0', '1.1'],
        'promotionServiceUpdate'                => ['1.0', '1.1'],
        'promotionServiceCheck'                 => ['1.0'],

        #user birth
        'userBirthServiceCreate'                => ['1.0'],
        'userBirthServiceOne'                   => ['1.0'],
        'userBirthServiceUpdate'                => ['1.0'],

        #order error
        'orderErrorServiceCreate'               => ['1.0'],

        # budget
        'budgetRecordsServiceCreate'            => ['1.0'],
        'budgetRecordsServiceGet'               => ['1.0'],

        # budget plan
        'budgetPlanServiceCreate'               => ['1.0'],
        'budgetPlanServiceGet'                  => ['1.0'],
        'budgetPlanServiceOne'                  => ['1.0'],

        # budget
        'budgetServiceCreate'                   => ['1.0'],
        'budgetServiceGet'                      => ['1.0', '1.1'],
        'budgetServiceOne'                      => ['1.0'],
        'budgetServiceUpdate'                   => ['1.0', '1.1','1.2'],
        'budgetServiceCheck'                    => ['1.0'],

        #expiration plan
        'expirationPlanServiceNotify'           => ['1.0'],
        'expirationPlanServiceCreate'           => ['1.0', '1.1'],
        'expirationPlanServiceGet'              => ['1.0'],
        'expirationPlanServiceOne'              => ['1.0', '1.1'],
        'expirationPlanServiceUpdate'           => ['1.0', '1.1', '1.2'],
        'expirationPlanServiceCheck'            => ['1.0', '1.1', '1.2', '1.3'],
        'expirationPlanServiceClose'            => ['1.0', '1.1', '1.2'],

        #plan additional
        'planAdditionalServiceGet'              => ['1.0'],
        'planAdditionalServiceOne'              => ['1.0'],

        #payment method
        'paymentMethodServiceGet'               => ['1.0'],

        #pagseguro transaction
        'pagSeguroTransactionServiceCreate'     => ['1.0'],
        'pagSeguroTransactionServiceGet'        => ['1.0'],
        'pagSeguroTransactionServiceOne'        => ['1.0'],

        #pagseguro notification
        'pagSeguroNotificationServiceCreate'    => ['1.0'],
        'pagSeguroNotificationServiceGet'       => ['1.0'],
        'pagSeguroNotificationServiceOne'       => ['1.0'],

        #pagseguro
        'pagSeguroServiceCreate'                => ['1.0'],
        'pagSeguroServiceGet'                   => ['1.0'],
        'pagSeguroServiceOne'                   => ['1.0'],

        #order payment
        'orderPaymentServiceCreate'             => ['1.0'],
        'orderPaymentServiceGet'                => ['1.0', '1.1'],
        'orderPaymentServiceOne'                => ['1.0'],

        #order item
        'orderItemServiceCreate'                => ['1.0'],
        'orderItemServiceGet'                   => ['1.0'],
        'orderItemServiceOne'                   => ['1.0'],

        #order
        'orderServiceCreate'                    => ['1.0'],
        'orderServiceGet'                       => ['1.0'],
        'orderServiceOne'                       => ['1.0'],
        'orderServiceNotify'                    => ['1.0'],
        'orderServiceComplete'                  => ['1.0'],
        'orderServiceFailed'                    => ['1.0'],
        'orderServiceUpdate'                    => ['1.0', '1.1'],

        #user plan
        'userPlanServiceCreate'                 => ['1.0'],
        'userPlanServiceOne'                    => ['1.0', '1.1'],
        'userPlanServiceUpdate'                 => ['1.0'],

        # plan
        'planServiceGet'                        => ['1.0', '1.1'],
        'planServiceOne'                        => ['1.0'],

        'userAdministratorServiceCreate'        => ['1.0'],
        'userAdministratorServiceOne'           => ['1.0'],
        'userAdministratorServiceGet'           => ['1.0'],
        'userAdministratorServiceCheck'         => ['1.0'],

        'userAppServiceCreate'                  => ['1.0'],
        'userAppServiceGet'                     => ['1.0'],

        'attachmentServiceCreate'               => ['1.0'],
        'attachmentServiceUpdate'               => ['1.0'],
        'attachmentServiceOne'                  => ['1.0'],
        'attachmentServiceGet'                  => ['1.0'],


        'userNotificationServiceCreate'         => ['1.0'],
        'userNotificationServiceUpdate'         => ['1.0'],
        'userNotificationServiceOne'            => ['1.0'],

        'expenseServiceCreate'                  => ['1.0'],
        'expenseServiceUpdate'                  => ['1.0'],
        'expenseServiceGet'                     => ['1.0', '1.1'],
        'expenseServiceOne'                     => ['1.0'],
        'expenseServiceEnable'                  => ['1.0'],
        'expenseServiceDisable'                 => ['1.0'],
        'expenseServiceClose'                   => ['1.0'],

        'enterpriseLogServiceCreated'           => ['1.0'],
        'enterpriseLogServiceGet'               => ['1.0'],

        'userEnterpriseFollowServiceCreate'     => ['1.0'],
        'userEnterpriseFollowServiceDelete'     => ['1.0'],
        'userEnterpriseFollowServiceCheck'      => ['1.0'],
        'userEnterpriseFollowServiceGet'        => ['1.0'],

        'appServiceOne'                         => ['1.0', '1.1'],

        'userShareServiceCreate'                => ['1.0', '1.1'],
        'userShareServiceUpdate'                => ['1.0'],
        'userShareServiceDisable'               => ['1.0'],

        'inviteUserServiceCreate'               => ['1.0', '1.1'],
        'inviteUserServiceDisable'              => ['1.0', '1.1'],
        'inviteUserServiceDelete'               => ['1.0'],
        'inviteUserServiceGet'                  => ['1.0', '1.1', '1.2'],
        'inviteUserServiceCheck'                => ['1.0', '1.1'],

        'userPartnerServiceCreate'              => ['1.0'],
        'userPartnerServiceGet'                 => ['1.0', '1.1'],
        'userPartnerServiceCheck'               => ['1.0'],
        'userPartnerServiceCount'               => ['1.0'],
        'userPartnerServiceUpdate'              => ['1.0'],

        'userClientServiceCreate'               => ['1.0', '1.1'],
        'userClientServiceGet'                  => ['1.0', '1.1'],
        'userClientServiceCheck'                => ['1.0'],
        'userClientServiceCount'                => ['1.0'],

        'userEqualServiceCreate'                => ['1.0', '1.1'],
        'userEqualServiceDisable'               => ['1.0'],
        'userEqualServiceGet'                   => ['1.0'],
        'userEqualServiceCheck'                 => ['1.0', '1.1'],
        'userEqualServiceCount'                 => ['1.0'],

        # permissions enterprise share
        'userEqualPermissionServiceCreate'      => ['1.0'],
        'userEqualPermissionServiceUpdate'      => ['1.0', '1.1'],
        'userEqualPermissionServiceDisable'     => ['1.0', '1.1'],
        'userEqualPermissionServiceDelete'      => ['1.0', '1.1'],
        'userEqualPermissionServiceOne'         => ['1.0', '1.1'],

        # user enterprise
        'userEnterpriseServiceCheck'            => ['1.0', '1.1'],
        'userEnterpriseServiceGet'              => ['1.0'],

        # scheduling
        'schedulingServiceCreate'               => ['1.0'],
        'schedulingServiceUpdate'               => ['1.0'],
        'schedulingServiceEnable'               => ['1.0'],
        'schedulingServiceDisable'              => ['1.0'],
        'schedulingServiceOne'                  => ['1.0'],
        'schedulingServiceGet'                  => ['1.0'],

        # scheduling person
        'schedulingPersonServiceCreate'         => ['1.0'],
        'schedulingPersonServiceEnable'         => ['1.0'],
        'schedulingPersonServiceDisable'        => ['1.0'],
        'schedulingPersonServiceGet'            => ['1.0'],

        # scheduling user
        'schedulingUserServiceCreate'           => ['1.0'],
        'schedulingUserServiceEnable'           => ['1.0'],
        'schedulingUserServiceDisable'          => ['1.0'],
        'schedulingUserServiceGet'              => ['1.0', '1.1', '1.2'],
        'schedulingUserServiceOne'              => ['1.0'],

        # scheduling enterprise
        'schedulingEnterpriseServiceCreate'     => ['1.0'],
        'schedulingEnterpriseServiceEnable'     => ['1.0'],
        'schedulingEnterpriseServiceDisable'    => ['1.0'],
        'schedulingEnterpriseServiceGet'        => ['1.0'],

        # person
        'personServiceCreate'                   => ['1.0', '1.1'],
        'personServiceUpdate'                   => ['1.0'],
        'personServiceEnable'                   => ['1.0'],
        'personServiceDisable'                  => ['1.0'],
        'personServiceOne'                      => ['1.0'],
        'personServiceGet'                      => ['1.0'],

        # lead
        'leadServiceCreate'                     => ['1.0'],
        'leadServiceUpdate'                     => ['1.0'],
        'leadServiceEnable'                     => ['1.0'],
        'leadServiceDisable'                    => ['1.0'],
        'leadServiceOne'                        => ['1.0'],
        'leadServiceGet'                        => ['1.0'],

        'enterpriseBranchServiceCreate'         => ['1.0'],
        'enterpriseBranchServiceOne'            => ['1.0'],
        'enterpriseBranchServiceGet'            => ['1.0', '1.1'],

        'issueServiceGet'                       => ['1.0', '1.1'],
        'issueServiceOne'                       => ['1.0'],
        'issueServiceCreate'                    => ['1.0'],
        'issueServiceUpdate'                    => ['1.0'],
        'userIssueServiceGet'                   => ['1.0'],

        # user enterprise
        'userEnterpriseServiceEnable'           => ['1.0'],
        'userEnterpriseServiceDisable'          => ['1.0'],

        # enterprise
        'enterpriseServiceDelete'               => ['1.0'],
        'enterpriseServiceToActivate'           => ['1.0'],
        'enterpriseServiceCheck'                => ['1.0'],
        'enterpriseServiceEnable'               => ['1.0'],
        'enterpriseServiceDisable'              => ['1.0'],

        'enterpriseAddressServiceCreate'        => ['1.0', '1.1'],
        'enterpriseAddressServiceUpdate'        => ['1.0', '1.1'],
        'enterpriseAddressServiceOne'           => ['1.0'],
        'enterpriseAddressServiceGet'           => ['1.0'],

        'changePasswordServiceUpdate'           => ['1.0'], # testado

        'enterpriseAdditionalServiceCreate'     => ['1.0'],

        'enterpriseAdditionalServiceUpdate'     => ['1.0'],

        'enterprisePersonServiceCreate'         => ['1.0'],
        'enterprisePersonServiceGet'            => ['1.0'],
        'enterprisePersonServiceOne'            => ['1.0'],

        'phoneServiceCreate'                    => ['1.0'],
        'phoneServiceUpdate'                    => ['1.0'],
        'phoneServiceGet'                       => ['1.0'],
        'phoneServiceOne'                       => ['1.0'],

        'emailServiceCreate'                    => ['1.0', '1.1'],
        'emailServiceUpdate'                    => ['1.0', '1.1'],
        'emailServiceGet'                       => ['1.0'],
        'emailServiceOne'                       => ['1.0'],

        'networkServiceCreate'                  => ['1.0'],
        'networkServiceUpdate'                  => ['1.0'],
        'networkServiceGet'                     => ['1.0'],


        # create enterprise
        'enterpriseServiceCreate'               => ['1.0', '1.1'],
        'enterpriseServiceUpdate'               => ['1.0', '1.1'],
        'enterpriseServiceOne'                  => ['1.0', '1.1'],
        'enterpriseServiceGet'                  => ['1.0'],

        # user & enterprise
        'userEnterpriseServiceCreate'           => ['1.0', '1.1'],

        # user
        'userServiceGet'                        => ['1.0', '1.1', '1.2', '1.3'],
        'userServiceCreate'                     => ['1.0', '1.1'], #testado
        'userServiceUpdate'                     => ['1.0'],
        'userServiceDisable'                    => ['1.0'],
        'userServiceEnable'                     => ['1.0'],
        'userServiceOne'                        => ['1.0'],
        'userServiceCheck'                      => ['1.0', '1.1'],
        'userSessionServiceOne'                 => ['1.0'],

        'logInServiceCheck'                     => ['1.0', '1.1', '1.2'], #testado
        'logOutServiceCheck'                    => ['1.0'], #testado

        'recoverPasswordServiceStart'           => ['1.0'], #testado
        'recoverPasswordServiceFinish'          => ['1.0'], #testado

        'checkEmailServiceCheck'                => ['1.0', '1.1'], # testado
        'confirmEmailServiceConfirm'            => ['1.0'], #testado

        'certificateServiceCreate'              => ['1.0'],
        'certificateServiceUpdate'              => ['1.0'],
        'certificateServiceGet'                 => ['1.0'],
        'certificateServiceNotify'              => ['1.0', '1.1'],

        # bank
        'bankServiceGet'                        => ['1.0'],
        'bankServiceOne'                        => ['1.0'],

        # currency
        'currencyServiceGet'                    => ['1.0'],
        'currencyServiceOne'                    => ['1.0'],

        # country
        'countryServiceGet'                     => ['1.0'],
        'countryServiceOne'                     => ['1.0'],

        # currency quote
        'currencyQuoteServiceGet'               => ['1.0', '1.1'],
        'currencyQuoteServiceLast'              => ['1.0'],
        'currencyQuoteServiceOne'               => ['1.0'],
        'currencyQuoteServiceCreate'            => ['1.0'],
        'currencyQuoteServiceUpdate'            => ['1.0'],

        # bank account
        'bankAccountServiceGet'                 => ['1.0'],
        'bankAccountServiceOne'                 => ['1.0'],
        'bankAccountServiceCreate'              => ['1.0'],
        'bankAccountServiceUpdate'              => ['1.0'],
        'bankAccountServiceGross'               => ['1.0'],

        # user address
        'userAddressServiceGet'                 => ['1.0'],
        'userAddressServiceOne'                 => ['1.0', '1.1'],
        'userAddressServiceCreate'              => ['1.0'],
        'userAddressServiceUpdate'              => ['1.0'],
        'userAddressServiceEnable'              => ['1.0'],
        'userAddressServiceDisable'             => ['1.0'],
        'userAddressServiceCheck'               => ['1.0'],

        'followUpServiceCreate'                 => ['1.0', '1.1'],
        'followUpServiceUpdate'                 => ['1.0'],
        'followUpServiceOne'                    => ['1.0'],
        'followUpServiceGet'                    => ['1.0'],

        'followUpReasonServiceGet'              => ['1.0'],

        'userSettingsServiceOne'                => ['1.0'],
        'userSettingsServiceUpdate'             => ['1.0'],

        'supportServiceCreate' => ['1.0'],
        'supportServiceGet' => ['1.0'],
        'supportServiceOne' => ['1.0'],

    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $app         = null;
        $application = $request->app;

        if (!$request->isMethod('post')) {
            return response()->json(
                [
                    'status'  => (bool)   false,
                    'message' => (string) message('api', self::REQUEST_METHOD_NOT_ALLOWED)
                ]
            );
        }

        $service = \Request::route()->getName();

        $version = $request->version;

        $serviceCheck = (bool) in_array($service, array_keys(self::$guarded));

        if ( $serviceCheck == false ) {
            return response()->json(
                [
                    'status'  => (bool)   false,
                    'message' => (string) message('api', self::SERVICE_NOT_FOUND, ['service' => $service])
                ]
            );
        }

        if ( $serviceCheck === true ) {
            $serviceVersion = array_values(self::$guarded[$service]);
            if ( !in_array($version, $serviceVersion) ) {
                return response()->json(
                    [
                        'status'  => (bool)   false,
                        'message' => (string) message('api', self::VERSION_UNAVAILABLE, ['version' => $version])
                    ]
                );
            }
        }

        if ($application != null) {
            $app = Model::where('name', $application)->first();
        }

        if ($app == null) {
            return response()->json(
                [
                    'status'  => false,
                    'message' => (string) message('api', self::APP_NOT_FOUND, ['app' => $application])
                ]
            );
        }

        $token = $request->input('token');
        if (Api::check($token) === false) {
            return response()->json(
                [
                    'status'  => false,
                    'message' => "invalid token"
                ]
            );
        }

        $lang =  $request->lang;

        if ($lang == null) {
            $lang = 'pt';
        }

        \App::setLocale($lang);

        $request->merge(array('app' => $app));

        return $next($request);

    }

}
