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

use App\Mail\WelcomeAgent;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/welcome_agent', function () {
    $user = \App\Models\User::find(5);
    $user->user_password='agent@123';
    $response = \Mail::to('emma-lynch@spam4.me')->send(new WelcomeAgent($user));
    dd($response);
   // return view('emails.agents.welcome')->with('user',$user);
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => array('auth')], function() {

    Route::prefix('admin')->group(function () {

        Route::get('/dashboard', 'UserController@admin_dashboard')->name('admin.dashboard');
        Route::get('/profile/{id}', 'UserController@profile')->name('admin.profile');
        Route::put('/profile/{id}', 'UserController@update_profile');
        Route::get('/notifications', 'NotificationController@index')->name('admin.notifications');
        Route::get('/change_password', 'UserController@change_password')->name('admin.change_password');
        Route::put('/update_password/{user_id}', 'UserController@updatePassword');
        Route::get('/store_city', 'UserController@storeCity')->name('admin.store_city');

        Route::resource('agents', 'AgentController');
        Route::resource('stores', 'StoreController');
        Route::resource('couriers', 'CourierController');
        Route::resource('expenses', 'ExpenseController');

        // Master Routes
        Route::resource('expense_types', 'ExpensetypeController');
        Route::resource('status', 'StatusController');
        Route::resource('package_types', 'PackagetypeController');
        Route::resource('service_types', 'ServicetypeController');
        Route::resource('content_types', 'ContenttypeController');

    });

    Route::prefix('agent')->group(function () {

        Route::get('/dashboard', 'UserController@agent_dashboard')->name('agent.dashboard');
        Route::get('/profile/{id}', 'UserController@profile')->name('agent.profile');
        Route::get('/change_password', 'UserController@change_password')->name('agent.change_password');

        Route::resource('couriers', 'CourierController');
    });

    Route::prefix('store')->group(function () {

        Route::get('/dashboard', 'UserController@store_dashboard')->name('store.dashboard');
        Route::get('/profile/{id}', 'UserController@profile')->name('store.profile');
        Route::get('/change_password', 'UserController@change_password')->name('store.change_password');
        Route::resource('expenses', 'ExpenseController');

    });

});