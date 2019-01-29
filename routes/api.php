<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/getStates','API\MasterController@getStates')->name('getStates');
Route::get('/getCities','API\MasterController@getCities')->name('getCities');
Route::post('/update_courier_status','CourierController@updateCourierStatus')->name('update_courier_status');
Route::post('/update_notification_status','NotificationController@updateNotificationStatus')->name('update_notification_status');
Route::get('/getCouriers','CourierController@getCouriers')->name('getCouriers');
Route::post('/save_courier_charge','CourierController@saveCourierCharge')->name('save_courier_charge');
Route::post('/get_agent_name','UserController@getAgentName')->name('get_agent_name');
Route::post('/update_pickup_status','CourierController@update_pickup_status')->name('update_pickup_status');
Route::post('/get_user_name','UserController@getUserName')->name('get_user_name');
Route::get('/generate_report','ReportController@generateReport')->name('generate_report');
Route::get('/getpayments','PaymentController@getPayments')->name('getpayments');
Route::get('/getexpenses','ExpenseController@getExpenses')->name('getexpenses');
Route::post('/get_store_agent','UserController@getStoreAgent')->name('get_store_agent');
Route::get('/get_sender_phone','CourierController@getSenderPhone')->name('get_sender_phone');
Route::get('/get_recipient_address','CourierController@getRecipientAddress')->name('get_recipient_address');
Route::get('/generate_payment_expense','ReportController@generatePaymentExpense')->name('generate_report');
Route::get('/getAgentPayment','ReportController@getAgentPayment')->name('getAgentPayment');
Route::post('/get_walking_customer','UserController@getWalkingCustomer')->name('get_walking_customer');
Route::get('/getWalkingCustomerPayment','ReportController@getWalkingCustomerPayment')->name('getWalkingCustomerPayment');
Route::post('/get_vendors','VendorController@getVendors')->name('get_vendors');
Route::get('/getManifestPayment','ReportController@getManifestPayment')->name('getManifestPayment');























