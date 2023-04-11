<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('google/maps/{lat}/{lng}', ['as' => 'googleMaps', function ($lat, $lng) {

    $googleMaps = new \App\Services\GoogleMaps;
    return response()
    ->json($googleMaps->execute($lat, $lng));

}]);

Route::group(['prefix' => '', 'middleware' => ['language', 'web']], function(){

    Route::get('',         ['as' => 'default',       'uses' => 'LogInController@index']);

    Route::get('change/language/{language}/{url}', ['as' => 'changeLanguage',       'uses' => 'ChangeLanguageController@change']);

    # logIn
    Route::get('logIn',         ['as' => 'logIn',       'uses' => 'LogInController@index']);
    Route::post('logIn',        ['as' => 'logInSubmit', 'uses' => 'LogInController@logIn']);

    # logout
    Route::get('logOut',        ['as' => 'logOut',      'uses' => 'LogOutController@logOut']);

    # welcome
    Route::get('welcome',       ['as' => 'welcome',     'uses' => 'WelcomeController@index']);

    # scheduling
    Route::get('schedule',    ['as' => 'schedule',            'uses' => 'ScheduleController@index']);
    Route::post('schedule',   ['as' => 'userScheduleByDate',    'uses' => 'ScheduleController@userScheduleByDate']);


    # me
    Route::get('me',       ['as' => 'me',     'uses' => 'MeController@index']);

    # social
    Route::get('social/{id}',       ['as' => 'social',     'uses' => 'SocialController@index']);

    # enterprise
    Route::group(['prefix' => 'enterprise'], function() {

        Route::get('',          ['as' => 'enterprise.index',    'uses' => 'EnterpriseController@index'  ]);
        Route::get('{id}/show', ['as' => 'enterprise.show',     'uses' => 'EnterpriseController@show'   ]);
        Route::get('new',       ['as' => 'enterprise.new',      'uses' => 'EnterpriseController@new'    ]);
        Route::get('{id}/edit', ['as' => 'enterprise.edit',     'uses' => 'EnterpriseController@edit'   ]);
        Route::get('disable/{id}',  ['as' => 'enterprise.disable',  'uses' => 'EnterpriseController@disable']);
        Route::get('enable/{id}',   ['as' => 'enterprise.enable',  'uses' => 'EnterpriseController@enable' ]);
        Route::post('insert',   ['as' => 'enterprise.insert',   'uses' => 'EnterpriseController@insert' ]);
        Route::post('update',   ['as' => 'enterprise.update',   'uses' => 'EnterpriseController@update' ]);

        # enterprise person
        Route::group(['prefix' => 'person'], function() {
            # new & insert
            Route::get('new/{controller_id}',   ['as' => 'enterprise.person.new',      'uses' => 'EnterprisePersonController@new'    ]);
            Route::post('insert',               ['as' => 'enterprise.person.insert',   'uses' => 'EnterprisePersonController@insert' ]);
            # edit & update
            Route::get('{id}/edit',             ['as' => 'enterprise.person.edit',     'uses' => 'EnterprisePersonController@edit'   ]);
            Route::post('update',               ['as' => 'enterprise.person.update',   'uses' => 'EnterprisePersonController@update' ]);
            # index
            Route::get('index/{controller_id}', ['as' => 'enterprise.person.index',    'uses' => 'EnterprisePersonController@index'  ]);
        });

    });

    Route::group(['prefix' => 'bank/account'], function() {
        Route::get('',          ['as' => 'bankAccount.index',    'uses' => 'BankAccountController@index'  ]);
        Route::get('{id}/show', ['as' => 'bankAccount.show',     'uses' => 'BankAccountController@show'   ]);
        Route::get('new',       ['as' => 'bankAccount.new',      'uses' => 'BankAccountController@new'    ]);
        Route::get('{id}/edit', ['as' => 'bankAccount.edit',     'uses' => 'BankAccountController@edit'   ]);
        Route::get('disable/{id}',  ['as' => 'bankAccount.disable',  'uses' => 'BankAccountController@disable']);
        Route::get('enable/{id}',   ['as' => 'bankAccount.enable',  'uses' => 'BankAccountController@enable' ]);
        Route::post('insert',   ['as' => 'bankAccount.insert',   'uses' => 'BankAccountController@insert' ]);
        Route::post('update',   ['as' => 'bankAccount.update',   'uses' => 'BankAccountController@update' ]);
    });

    Route::group(['prefix' => 'expense'], function() {
        Route::get('',          ['as' => 'expense.index',    'uses' => 'ExpenseController@index'  ]);
        Route::get('{id}/show', ['as' => 'expense.show',     'uses' => 'ExpenseController@show'   ]);
        Route::get('new',       ['as' => 'expense.new',      'uses' => 'ExpenseController@new'    ]);
        Route::get('{id}/edit', ['as' => 'expense.edit',     'uses' => 'ExpenseController@edit'   ]);
        Route::post('insert',   ['as' => 'expense.insert',   'uses' => 'ExpenseController@insert' ]);
        Route::post('update',   ['as' => 'expense.update',   'uses' => 'ExpenseController@update' ]);
        Route::get('enable/{id}',   ['as' => 'expense.enable',   'uses' => 'ExpenseController@enable' ]);
        Route::get('disable/{id}',  ['as' => 'expense.disable',  'uses' => 'ExpenseController@disable']);
        Route::get('close/{id}',    ['as' => 'expense.close',    'uses' => 'ExpenseController@close'  ]);
    });

    Route::group(['prefix' => 'address'], function(){
        Route::get('new/{controller}',          ['as' => 'address.new',      'uses' => 'AddressController@new'    ]);
        Route::get('{id}/{controller}/edit',    ['as' => 'address.edit',     'uses' => 'AddressController@edit'   ]);
        Route::post('insert',                   ['as' => 'address.insert',   'uses' => 'AddressController@insert' ]);
        Route::post('update',                   ['as' => 'address.update',   'uses' => 'AddressController@update' ]);
        Route::get('index/{controller}/{controller_id}',                     ['as' => 'address.index',     'uses' => 'AddressController@index'   ]);
    });

    Route::group(['prefix' => 'phone'], function(){
        Route::get('new/{controller}',          ['as' => 'phone.new',      'uses' => 'PhoneController@new'    ]);
        Route::get('{id}/{controller}/edit',    ['as' => 'phone.edit',     'uses' => 'PhoneController@edit'   ]);
        Route::post('insert',                   ['as' => 'phone.insert',   'uses' => 'PhoneController@insert' ]);
        Route::post('update',                   ['as' => 'phone.update',   'uses' => 'PhoneController@update' ]);
        Route::get('index/{controller}/{controller_id}',                     ['as' => 'phone.index',     'uses' => 'PhoneController@index'   ]);
    });

    Route::group(['prefix' => 'followUp'], function(){
        Route::get('new/{controller}',          ['as' => 'followUp.new',      'uses' => 'FollowUpController@new'    ]);
        Route::get('{id}/{controller}/edit',    ['as' => 'followUp.edit',     'uses' => 'FollowUpController@edit'   ]);
        Route::post('insert',                   ['as' => 'followUp.insert',   'uses' => 'FollowUpController@insert' ]);
        Route::post('update',                   ['as' => 'followUp.update',   'uses' => 'FollowUpController@update' ]);
        Route::get('index/{controller}/{controller_id}',                     ['as' => 'followUp.index',     'uses' => 'FollowUpController@index'   ]);
    });

    Route::group(['prefix' => 'attachment'], function(){
        Route::get('new/{controller}',                  ['as' => 'attachment.new',      'uses' => 'AttachmentController@new'    ]);
        Route::get('{id}/{controller}/edit',            ['as' => 'attachment.edit',     'uses' => 'AttachmentController@edit'   ]);
        Route::post('insert',                           ['as' => 'attachment.insert',   'uses' => 'AttachmentController@insert' ]);
        Route::post('update',                           ['as' => 'attachment.update',   'uses' => 'AttachmentController@update' ]);
        Route::get('index/{controller}/{controller_id}',['as' => 'attachment.index',    'uses' => 'AttachmentController@index'   ]);
    });

    Route::group(['prefix' => 'scheduling'], function(){
        Route::get('new/{controller}',          ['as' => 'scheduling.new',      'uses' => 'SchedulingController@new'    ]);
        Route::get('{id}/{controller}/edit',    ['as' => 'scheduling.edit',     'uses' => 'SchedulingController@edit'   ]);
        Route::post('insert',                   ['as' => 'scheduling.insert',   'uses' => 'SchedulingController@insert' ]);
        Route::post('update',                   ['as' => 'scheduling.update',   'uses' => 'SchedulingController@update' ]);
        Route::get('index/{controller}/{controller_id}',                     ['as' => 'scheduling.index',     'uses' => 'SchedulingController@index'   ]);
    });

    Route::group(['prefix' => 'email'], function(){
        Route::get('new/{controller}',          ['as' => 'email.new',       'uses' => 'EmailController@new'     ]);
        Route::get('{id}/{controller}/edit',    ['as' => 'email.edit',      'uses' => 'EmailController@edit'    ]);
        Route::post('insert',                   ['as' => 'email.insert',    'uses' => 'EmailController@insert'  ]);
        Route::post('update',                   ['as' => 'email.update',    'uses' => 'EmailController@update'  ]);
        Route::get('index/{controller}/{controller_id}',                     ['as' => 'email.index',     'uses' => 'EmailController@index'   ]);
    });

    Route::group(['prefix' => 'currencyQuote'], function(){
        Route::get('',                          ['as' => 'currencyQuote.index',   'uses' => 'CurrencyQuoteController@index' ]);
        Route::get('auto',                      ['as' => 'currencyQuote.auto',    'uses' => 'CurrencyQuoteController@auto' ]);
    });

    Route::group(['prefix' => 'me'], function(){
        Route::post('update',                    ['as' => 'me.update',   'uses' => 'MeController@update' ]);
    });

    Route::group(['prefix' => 'follow'], function(){
        Route::get('{controller}/{controller_id}',  ['as' => 'follow.show',    'uses' => 'Widget\FollowController@show'      ]);
        Route::get('{controller}',                  ['as' => 'follow.index',   'uses' => 'Widget\FollowController@index'      ]);
        Route::post('{controller}/{controller_id}', ['as' => 'follow.update',  'uses' => 'Widget\FollowController@update'    ]);
    });

    Route::group(['prefix' => 'user'], function(){
        Route::get('',          ['as' => 'user.index',    'uses' => 'UserController@index'  ]);
        Route::get('{id}/show', ['as' => 'user.show',     'uses' => 'UserController@show'   ]);
        Route::get('new',       ['as' => 'user.new',      'uses' => 'UserController@new'    ]);
        Route::get('{id}/edit', ['as' => 'user.edit',     'uses' => 'UserController@edit'   ]);
        Route::post('insert',   ['as' => 'user.insert',   'uses' => 'UserController@insert' ]);
        Route::post('update',   ['as' => 'user.update',   'uses' => 'UserController@update' ]);
        Route::post('disable',  ['as' => 'user.disable',  'uses' => 'UserController@disable']);
        Route::post('enable',   ['as' => 'user.enable',   'uses' => 'UserController@enable' ]);
    });

    Route::group(['prefix' => 'setup'], function(){

        Route::get('',              ['as' => 'setup.index',         'uses' => 'Setup\SetupController@index'  ]);

        Route::group(['prefix'      => 'invites'], function(){
            Route::get('',          ['as' => 'inviteUser.index',    'uses' => 'Setup\InviteUserController@index'    ]);
            Route::post('insert',   ['as' => 'inviteUser.insert',   'uses' => 'Setup\InviteUserController@insert'   ]);
            Route::post('delete',   ['as' => 'inviteUser.delete',   'uses' => 'Setup\InviteUserController@delete'   ]);
            Route::get('accept',    ['as' => 'inviteUser.accept',   'uses' => 'Setup\InviteUserController@accept'   ]);
        });

    });

    Route::group(['prefix' => 'service'], function(){

        Route::group(['prefix' => 'postman'], function(){
            Route::get('schedule',              ['as' => 'service.schedule',         'uses' => 'Service\PostmanController@schedule'  ]);
        });

        Route::get('cache/clear',              ['as' => 'service.cacheClear',         'uses' => 'Service\CacheController@clear'  ]);

    });


    Route::group(['prefix' => 'support'], function(){

        Route::get('',  ['as' => 'support.index', 'uses' => 'Support\IndexController@index']);

        Route::group(['prefix' => 'issues'], function(){
            Route::get('',       ['as' => 'support.issue.index', 'uses' => 'Support\IssueController@index']);
        });

    });

    Route::group(['prefix' => 'shopping-cart'], function(){
        Route::get('',  ['as' => 'shoppingCart', 'uses' => 'Payment\ShoppingCartController@index']);
        Route::post('/update-item',  ['as' => 'updateItem', 'uses' => 'Payment\ShoppingCartController@updateItem']);
        Route::get('/checkout',  ['as' => 'checkout', 'uses' => 'Payment\CheckoutController@index']);
        Route::post('/checkout',  ['as' => 'checkout', 'uses' => 'Payment\CheckoutController@checkout']);

        Route::get('/choose-plan',  ['as' => 'choosePlan', 'uses' => 'Payment\ShoppingCartController@choosePlan']);
        Route::post('/choose-plan/add',  ['as' => 'addPlan', 'uses' => 'Payment\ShoppingCartController@addPlan']);
        Route::post('/choose-plan/on-request',  ['as' => 'onRequest', 'uses' => 'Payment\ShoppingCartController@onRequest']);
    });


});
