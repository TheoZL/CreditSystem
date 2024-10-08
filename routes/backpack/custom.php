<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('billing-schedule', 'BillingScheduleCrudController');
    Route::crud('canton', 'CantonCrudController');
    Route::crud('credit', 'CreditCrudController');
    Route::crud('credit-status', 'CreditStatusCrudController');
    Route::crud('customer', 'CustomerCrudController');
    Route::crud('distrit', 'DistritCrudController');
    //Route::crud('payment-history', 'PaymentHistoryCrudController');
    Route::crud('payment-type', 'PaymentTypeCrudController');
    Route::crud('province', 'ProvinceCrudController');
    Route::crud('route', 'RouteCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('vehicle', 'VehicleCrudController');
    Route::crud('product', 'ProductCrudController');

    //Custom Routes
    Route::get('/payment-type/json', 'App\Http\Controllers\Admin\PaymentTypeCrudController@getAll');
    Route::get('/credit/{id}/history', 'App\Http\Controllers\Admin\PaymentHistoryController@showHistory')->name('payment.history');
    Route::post('/credit/payment/{id}', 'App\Http\Controllers\Admin\PaymentHistoryController@applyPayment');
    Route::post('/credit/payment/delete/{id}', 'App\Http\Controllers\Admin\PaymentHistoryController@deletePayment');
    Route::post('/credit/payment/update/{id}', 'App\Http\Controllers\Admin\PaymentHistoryController@updatePayment');

}); // this should be the absolute last line of this file
