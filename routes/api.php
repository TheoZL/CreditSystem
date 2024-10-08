<?php

use App\Http\Controllers\Api\V1\AddressDataController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BillingScheduleController;
use App\Http\Controllers\Api\V1\CreditController;
use App\Http\Controllers\Api\V1\CreditStatusController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\PaymentHistoryController;
use App\Http\Controllers\Api\V1\RouteController;
use App\Http\Controllers\Api\V1\VehicleController;
use Illuminate\Support\Facades\Route;

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


Route::post('V1/login', [AuthController::class, 'login']);

Route::group([
    'prefix' => '/V1',
    'middleware' => ['auth:sanctum']
], function (){
    //Resources Routes
    Route::resource('/customers', CustomerController::class)->except(['create','edit']);
    Route::resource('/vehicles', VehicleController::class)->except(['create','edit']);
    Route::resource('/routes', RouteController::class)->except(['create','edit']);
    Route::resource('/payment-type', RouteController::class)->except(['create','edit']);
    Route::resource('/billing-schedule', BillingScheduleController::class)->except(['create','edit']);
    Route::resource('/credit-status', CreditStatusController::class)->except(['create','edit']);
    Route::resource('/payment-history', PaymentHistoryController::class)->except(['create','edit']);
    Route::resource('/credits', CreditController::class)->except(['create','edit']);
    Route::resource('/payment-history', CreditController::class)->except(['create','edit']);


    //Address Data
    Route::get('/provinces', [AddressDataController::class, 'getAllProvinces']);
    Route::get('/cantons', [AddressDataController::class, 'getAllCantons']);
    Route::get('/districts', [AddressDataController::class, 'getAllDistricts']);
    Route::get('/address-all', [AddressDataController::class, 'getAllInfo']);


    //logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
