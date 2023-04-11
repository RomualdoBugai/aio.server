<?php

use Illuminate\Http\Request;

$mapa = [
	
	'promotion' => [
		'controller' 	=> 'Promotion',
		'method' 		=> ['create', 'get', 'update', 'check']
	],

	'user/birth' => [
		'controller' 	=> 'UserBirth',
		'method' 		=> ['create', 'one', 'update']
	],

	'order/error' => [
		'controller' 	=> 'OrderError',
		'method' 		=> ['create']
	],

	'budget/records' => [
		'controller' 	=> 'BudgetRecords',
		'method' 		=> ['create', 'get']
	],

	
	'budget' => [
		'controller' 	=> 'Budget',
		'method' 		=> ['create', 'get', 'one', 'update', 'check']
	],
	
	'expiration/plan' => [
		'controller' 	=> 'ExpirationPlan',
		'method' 		=> ['notify', 'create', 'get', 'one', 'update', 'check', 'close']
	],	

	'plan/additional' => [
		'controller' 	=> 'PlanAdditional',
		'method' 		=> ['get', 'one']
	],

	'payment/method' => [
		'controller' 	=> 'PaymentMethod',
		'method' 		=> ['get']
	],
	
	'pag/seguro/transaction' => [
		'controller' 	=> 'PagSeguroTransaction',
		'method' 		=> ['create', 'get', 'one']
	],

	'pag/seguro/notification' => [
		'controller' 	=> 'PagSeguroNotification',
		'method' 		=> ['create', 'get', 'one']
	],

	'pag/seguro' => [
		'controller' 	=> 'PagSeguro',
		'method' 		=> ['create', 'get', 'one']
	],

	'order/payment' => [
		'controller' 	=> 'OrderPayment',
		'method' 		=> ['create', 'get', 'one']
	],

	'order/item' => [
		'controller' 	=> 'OrderItem',
		'method' 		=> ['create', 'get', 'one']
	],

	'order' => [
		'controller' 	=> 'Order',
		'method' 		=> ['create', 'get', 'one', 'notify', 'complete', 'failed', 'update']
	],

	'user/plan' => [
		'controller' 	=> 'UserPlan',
		'method' 		=> ['create', 'one', 'update']
	],

	'plan' => [
		'controller' 	=> 'Plan',
		'method' 		=> ['get', 'one']
	],

	'user/administrator' => [
		'controller' 	=> 'UserAdministrator',
		'method' 		=> ['create', 'one', 'get', 'check']
	],
	
	'user/partner' => [
		'controller' 	=> 'UserPartner',
		'method' 		=> ['create', 'get', 'check', 'count', 'update']
	],

	'user/client' => [
		'controller' 	=> 'UserClient',
		'method' 		=> ['create', 'get', 'check', 'count']
	],

	'user/equal/permission' => [
		'controller' 	=> 'UserEqualPermission',
		'method' 		=> ['create', 'update', 'disable', 'delete', 'one']
	],

	'user/app' => [
		'controller' 	=> 'UserApp',
		'method' 		=> ['create', 'get']
	],


	'attachment' => [
		'controller' 	=> 'Attachment',
		'method' 		=> ['create', 'one', 'get']
	],

	'user/notification' => [
		'controller' 	=> 'UserNotification',
		'method' 		=> ['create', 'update', 'one']
	],

	'app/support' => [
		'controller' 	=> 'Support',
		'method' 		=> ['create', 'get', 'one']
	],

	'user' => [
		'controller' 	=> 'User',
		'method' 		=> ['create', 'update', 'enable', 'disable', 'get', 'one', 'check']
	],

	'expense' => [
		'controller' 	=> 'Expense',
		'method' 		=> ['create', 'update', 'enable', 'disable', 'get', 'one', 'close']
	],

	'enterprise/log' => [
		'controller' 	=> 'EnterpriseLog',
		'method' 		=> ['create', 'get']
	],

	'user/enterprise/follow/' => [
		'controller' 	=> 'UserEnterpriseFollow',
		'method' 		=> ['create', 'get', 'delete', 'check']
	],

	'app' 			=> [
		'controller' 	=> 'App',
		'method' 		=> ['one']
	],

	'user/share' => [
		'controller' 	=> 'UserShare',
		'method' 		=> ['create', 'disable', 'update']
	],

	'user/equal' => [
		'controller' 	=> 'UserEqual',
		'method' 		=> ['create', 'disable', 'get', 'check', 'count']
	],

	'lead' => [
		'controller' 	=> 'Lead',
		'method' 		=> ['create', 'update', 'enable', 'disable', 'get', 'one']
	],

	'person' => [
		'controller' 	=> 'Person',
		'method' 		=> ['create', 'update', 'enable', 'disable', 'get', 'one']
	],

	'person' => [
		'controller' 	=> 'Person',
		'method' 		=> ['create', 'update', 'enable', 'disable', 'get', 'one']
	],

	'user/invite' => [
		'controller' 	=> 'InviteUser',
		'method' 		=> ['create', 'disable', 'delete', 'get', 'check']
	],

	'app/email' => [
		'controller' 	=> 'EmailBlacklist',
		'method' 		=> ['unsubscribe', 'subscribe']
	],

	'issue' => [
		'controller' 	=> 'Issue',
		'method' 		=> ['create', 'get', 'one']
	],

	'user/issue' => [
		'controller' 	=> 'UserIssue',
		'method' 		=> ['get']
	],

	'user/enterprise' => [
		'controller' 	=> 'UserEnterprise',
		'method' 		=> ['create', 'get', 'disable', 'enable', 'check']
	],

	'enterprise' => [
		'controller' 	=> 'Enterprise',
		'method' 		=> ['create', 'update', 'delete', 'toActivate', 'check', 'enable', 'disable', 'get', 'one']
	],

	'user/changePassword' => [
		'controller' 	=> 'ChangePassword',
		'method' 		=> ['update']
	],

	'enterprise/additional' => [
		'controller' 	=> 'Enterprise',
		'method' 		=> ['create', 'update']
	],

	'enterprise/additional' => [
		'controller' 	=> 'EnterpriseAdditional',
		'method' 		=> ['create', 'update']
	],

	'enterprise/address' => [
		'controller' 	=> 'EnterpriseAddress',
		'method' 		=> ['create', 'update', 'get', 'one']
	],

	'user/session' => [
		'controller' 	=> 'UserSession',
		'method' 		=> ['one']
	],

	'logIn' => [
		'controller' 	=> 'LogIn',
		'method' 		=> ['check']
	],

	'logOut' => [
		'controller' 	=> 'LogOut',
		'method' 		=> ['check']
	],

	'recoverPassword' => [
		'controller' 	=> 'RecoverPassword',
		'method' 		=> ['start', 'finish']
	],


	'checkEmail' => [
		'controller' 	=> 'CheckEmail',
		'method' 		=> ['check']
	],

	'confirmEmail' => [
		'controller' 	=> 'ConfirmEmail',
		'method' 		=> ['confirm']
	],

	'certificate' => [
		'controller' 	=> 'Certificate',
		'method' 		=> ['create', 'update', 'get', 'notify']
	],

	'bank' => [
		'controller' 	=> 'Bank',
		'method' 		=> ['one', 'get']
	],

	'currency' => [
		'controller' 	=> 'Currency',
		'method' 		=> ['one', 'get']
	],


	'currencyQuote' => [
		'controller' 	=> 'CurrencyQuote',
		'method' 		=> ['one', 'get', 'last', 'create', 'update']
	],

	'enterprise/person' => [
		'controller' 	=> 'EnterprisePerson',
		'method' 		=> ['create', 'get', 'one']
	],

	'phone' => [
		'controller' 	=> 'Phone',
		'method' 		=> ['create', 'update', 'get', 'one']
	],

	'email' => [
		'controller' 	=> 'Email',
		'method' 		=> ['create', 'update', 'get', 'one']
	],

	'network' => [
		'controller' 	=> 'Network',
		'method' 		=> ['create', 'update', 'get', 'one']
	],

	'bank/account' => [
		'controller' 	=> 'BankAccount',
		'method' 		=> ['create', 'update', 'get', 'one', 'gross']
	],

	'user/address' => [
		'controller' 	=> 'UserAddress',
		'method' 		=> ['create', 'update', 'get', 'one', 'enable', 'disable', 'check']
	],

	'scheduling' => [
		'controller' 	=> 'Scheduling',
		'method' 		=> ['create', 'update', 'get', 'one', 'enable', 'disable']
	],

	'scheduling/person' => [
		'controller' 	=> 'SchedulingPerson',
		'method' 		=> ['create', 'get', 'enable', 'disable']
	],

	'scheduling/enterprise' => [
		'controller' 	=> 'SchedulingEnterprise',
		'method' 		=> ['create', 'get', 'enable', 'disable']
	],

	'scheduling/user' => [
		'controller' 	=> 'SchedulingUser',
		'method' 		=> ['create', 'get', 'enable', 'disable']
	],

	'followUp' => [
		'controller' 	=> 'FollowUp',
		'method' 		=> ['create', 'update', 'get', 'one']
	],

	'followUp/reason' => [
		'controller' 	=> 'FollowUpReason',
		'method' 		=> ['get']
	],

	'enterprise/branch' => [
		'controller' 	=> 'EnterpriseBranch',
		'method' 		=> ['create', 'get', 'one']
	],

	'country' 			=> [
		'controller' 	=> 'Country',
		'method' 		=> ['get', 'one']
	],

	'user/settings' 	=> [
		'controller' 	=> 'UserSettings',
		'method' 		=> ['update', 'one']
	],

];


Route::group(
	[
		'prefix' 		=> '{app}',
		'before' 		=> ['rate', 'cors', 'app']
	], function() use (&$mapa) {

	foreach($mapa as $k => $route1) {
		Route::group(['prefix' => "{$k}/{version}"], function() use (&$route1, &$k) {
			foreach($route1['method'] as $x => $method) {
				Route::any($method, ['as' => lcfirst($route1['controller']) . 'Service' . ucwords($method), 'uses'	=> 'Api\\' . $route1['controller'] . "Service@" . $method]);
			}
		});
	}

});
