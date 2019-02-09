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

Route::get('/test', function () {
    $couriers = \App\Models\Courier::whereNull('courier_date')->get();
    foreach ($couriers as $cour){
        $courier = \App\Models\Courier::find($cour->id);
        $courier->courier_date = date('Y-m-d',strtotime($cour->created_at));
        $courier->save();
    }
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
        Route::get('/create_courier_csv','CourierController@createCourierCsv')->name('createCourierCsv');
        Route::post('/import_courier_csv','CourierController@importCourierCSv')->name('couriers.import-csv');
        Route::get('/generate_barcode/{id}','CourierController@generateBarcode')->name('admin.generate_barcode');
        Route::get('/payment_expense','ReportController@payment_expense')->name('payment_expense.payment_expense');
        Route::get('/couriers/box_details/{id}', 'CourierController@boxDetails')->name('couriers.box_details');
        Route::post('/save_courier_boxes', 'CourierController@saveBoxDetails');
        Route::post('/manifest/create_manifest', 'ManifestController@createManifest');
        Route::post('/manifest/save_manifest', 'ManifestController@saveManifest');
        Route::get('/courier_report/{id}','CourierController@courierReport')->name('courierReport');
        Route::get('/manifest/print/{id}','ManifestController@printManifest')->name('manifest.print');
        Route::get('/manifest/download','ManifestController@downloadManifest')->name('manifest.download');



        Route::get('/reports/walking_customer','ReportController@walkingCustomer')->name('reports.walking_customer');
        Route::get('/reports/agent_payment','ReportController@agentPayment')->name('reports.agent_payment');
        Route::get('/reports/payment_expense','ReportController@paymentExpense')->name('reports.payment_expense');
        Route::get('/couriers/delete/{id}','CourierController@destroy')->name('courier.destroy');
        Route::get('/couriers/payment_details/{id}', 'CourierController@paymentDetails')->name('couriers.payment_details');
        Route::get('/expenses/delete/{id}','ExpenseController@destroy')->name('expenses.destroy');
        Route::get('/payments/delete/{id}','PaymentController@destroy')->name('payments.destroy');
        Route::get('/reports/manifest','ReportController@manifestPayment')->name('reports.manifest');






        Route::resource('agents', 'AgentController');
        Route::resource('stores', 'StoreController');
        Route::resource('couriers', 'CourierController');
        Route::resource('expenses', 'ExpenseController');
        Route::resource('payments', 'PaymentController');
        Route::resource('reports', 'ReportController');
        Route::resource('manifest', 'ManifestController');


        // Master Routes
        Route::resource('expense_types', 'ExpensetypeController');
        Route::resource('status', 'StatusController');
        Route::resource('package_types', 'PackagetypeController');
        Route::resource('service_types', 'ServicetypeController');
        Route::resource('content_types', 'ContenttypeController');
        Route::resource('courier_services', 'CourierServiceController');
        Route::resource('banks', 'BankController');
        Route::resource('vendors', 'VendorController');



    });

    Route::prefix('agent')->group(function () {

        Route::get('/dashboard', 'UserController@agent_dashboard')->name('agent.dashboard');
        Route::get('/profile/{id}', 'UserController@profile')->name('agent.profile');
        Route::get('/change_password', 'UserController@change_password')->name('agent.change_password');
        Route::get('/generate_barcode/{id}','CourierController@generateBarcode')->name('agent.generate_barcode');
        Route::get('/couriers/box_details/{id}', 'CourierController@boxDetails')->name('couriers.box_details');
        Route::get('/courier_report/{id}','CourierController@courierReport')->name('courierReport');
        Route::get('/reports/agent_payment','ReportController@agentPayment')->name('reports.agent_payment');
        Route::get('/couriers/payment_details/{id}', 'CourierController@paymentDetails')->name('couriers.payment_details');


        Route::resource('couriers', 'CourierController');
    });

    Route::prefix('store')->group(function () {

        Route::get('/dashboard', 'UserController@store_dashboard')->name('store.dashboard');
        Route::get('/profile/{id}', 'UserController@profile')->name('store.profile');
        Route::get('/change_password', 'UserController@change_password')->name('store.change_password');
        Route::get('/generate_barcode/{id}','CourierController@generateBarcode')->name('store.generate_barcode');
        Route::get('/payment_expense','ReportController@payment_expense')->name('payment_expense.payment_expense');
        Route::get('/couriers/box_details/{id}', 'CourierController@boxDetails')->name('couriers.box_details');
        Route::get('/courier_report/{id}','CourierController@courierReport')->name('courierReport');
        Route::get('/manifest/print/{id}','ManifestController@printManifest')->name('manifest.print');
        Route::get('/couriers/payment_details/{id}', 'CourierController@paymentDetails')->name('couriers.payment_details');
        Route::post('/save_courier_payment', 'CourierController@savePaymentDetails');
        Route::get('/reports/walking_customer','ReportController@walkingCustomer')->name('reports.walking_customer');
        Route::get('/reports/agent_payment','ReportController@agentPayment')->name('reports.agent_payment');
        Route::get('/reports/payment_expense','ReportController@paymentExpense')->name('reports.payment_expense');
        Route::get('/reports/manifest','ReportController@manifestPayment')->name('reports.manifest');
        Route::get('/manifest/download','ManifestController@downloadManifest')->name('manifest.download');

        Route::resource('expenses', 'ExpenseController');
        Route::resource('couriers', 'CourierController');
        Route::resource('reports', 'ReportController');
        Route::resource('agents', 'AgentController');
        Route::resource('payments', 'PaymentController');
        Route::resource('manifest', 'ManifestController');


    });

});