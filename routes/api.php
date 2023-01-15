<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\CustomerController;
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

Route::group(['prefix' => 'v1'], function () {

    /** Agent specific API */
    Route::controller(AgentController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
    });

    Route::group(['middleware' => 'auth:api', 'controller' => AgentController::class], function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('me', 'getAgent');
    });

    /** Customer specific API */
    Route::group(['controller' => CustomerController::class,'prefix' => 'customer'], function () {
        Route::post('', 'createCustomer');
        Route::get('/{email}', 'getCustomerAppointmentsByEmail');
        Route::get('/', 'getAllCustomers');
    });

    /** Appointment Specific API */
    Route::group(['controller' => AppointmentsController::class,'prefix' => 'appointment','middleware' => 'auth:api'], function () {
        Route::post('', 'createAppointment');
        Route::delete('', 'deleteAppointment');

    });



});