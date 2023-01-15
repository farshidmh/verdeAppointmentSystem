<?php

use App\Http\Controllers\AgentController;
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


});