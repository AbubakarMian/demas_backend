<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\JourneyController;
use App\Http\Controllers\Api\OrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::post('register', 'Api\UserController@register');



Route::post('contactus',[ContactUsController::class, 'contactus']);


Route::group(['middleware' => 'auth.client_token'], function () {

    Route::post('register_or_login',[UserController::class, 'register_or_login']);
    Route::post('validate_otp',[UserController::class, 'validate_otp']);
    // Route::post('register',[UserController::class, 'register']);
    Route::post('login',[UserController::class, 'login']);
    Route::get('journey/verify',[JourneyController::class, 'verify_journey']);
    Route::get('cars/get_all',[CarController::class, 'get_all']);
    Route::get('car/details/{car_id}',[CarController::class, 'car_details']);
    Route::get('locations/get_all',[LocationController::class, 'get_all']);

});

Route::group(['middleware' => 'auth.user_loggedin'], function () {

    Route::post('order/create',[OrderController::class, 'create']);
    Route::get('order',[OrderController::class, 'index']);
    Route::get('order/{order_id}',[OrderController::class, 'detail']);
    // Route::post('login',[UserController::class, 'login']);
});