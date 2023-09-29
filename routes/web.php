<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\SlotController;
use App\Http\Controllers\Admin\JourneyController;
use App\Http\Controllers\Admin\DriverJourneyController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\TransportTypeController;
use App\Http\Controllers\Admin\TransportPricesController;
use App\Http\Controllers\Admin\SaltAgentController;
use App\Http\Controllers\Admin\TravelAgentController;
use App\Http\Controllers\Admin\TransportJourneyPricesController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\User\CommonServicesController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('admin/login', [AdminController::class, 'index']);
Route::post('admin/checklogin', [AdminController::class, 'checklogin']);
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('admin/logout', [AdminController::class, 'logout']);
Route::post('cropper/crop_image', [CommonServicesController::class, 'crop_image'])->name('crop.image');


Route::group(['prefix' => 'admin/', 'middleware' => 'admin_auth'], function () {

    //  =================================  user ==========================
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('get_user', [UserController::class, 'get_user'])->name('user.index');
        Route::get('create', [UserController::class, 'create'])->name('user.create'); //add
        Route::post('save', [UserController::class, 'save'])->name('user.save');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::post('delete/{id}', [UserController::class, 'destroy_undestroy'])->name('user.delete');
    });
    //  =================================  location ==========================
    Route::group(['prefix' => 'location'], function () {
        Route::get('/', [LocationController::class, 'index'])->name('location.index');
        Route::get('get_location', [LocationController::class, 'get_location'])->name('location.index');
        Route::get('create', [LocationController::class, 'create'])->name('location.create'); //add
        Route::post('save', [LocationController::class, 'save'])->name('location.save');
        Route::get('edit/{id}', [LocationController::class, 'edit'])->name('location.edit');
        Route::post('update/{id}', [LocationController::class, 'update'])->name('location.update');
        Route::post('delete/{id}', [LocationController::class, 'destroy_undestroy'])->name('location.delete');
    });
    //  =================================  slot ==========================
    Route::group(['prefix' => 'slot'], function () {
        Route::get('/', [SlotController::class, 'index'])->name('slot.index');
        Route::get('get_slot', [SlotController::class, 'get_slot'])->name('slot.index');
        Route::get('create', [SlotController::class, 'create'])->name('slot.create'); //add
        Route::post('save', [SlotController::class, 'save'])->name('slot.save');
        Route::get('edit/{id}', [SlotController::class, 'edit'])->name('slot.edit');
        Route::post('update/{id}', [SlotController::class, 'update'])->name('slot.update');
        Route::post('delete/{id}', [SlotController::class, 'destroy_undestroy'])->name('slot.delete');
    });
    //  =================================  journey ==========================
    Route::group(['prefix' => 'journey'], function () {
        Route::get('/', [JourneyController::class, 'index'])->name('journey.index');
        Route::get('get_journey', [JourneyController::class, 'get_journey'])->name('journey.index');
        Route::get('create', [JourneyController::class, 'create'])->name('journey.create'); //add
        Route::post('save', [JourneyController::class, 'save'])->name('journey.save');
        Route::get('edit/{id}', [JourneyController::class, 'edit'])->name('journey.edit');
        Route::post('update/{id}', [JourneyController::class, 'update'])->name('journey.update');
        Route::post('delete/{id}', [JourneyController::class, 'destroy_undestroy'])->name('journey.delete');
    });
    //  =================================  driver_journey ==========================
    Route::group(['prefix' => 'driver_journey'], function () {
        Route::get('/', [DriverJourneyController::class, 'index'])->name('driver_journey.index');
        Route::get('get_driver_journey', [DriverJourneyController::class, 'get_driver_journey'])->name('driver_journey.index');
        Route::get('create', [DriverJourneyController::class, 'create'])->name('driver_journey.create'); //add
        Route::post('save', [DriverJourneyController::class, 'save'])->name('driver_journey.save');
        Route::get('edit/{id}', [DriverJourneyController::class, 'edit'])->name('driver_journey.edit');
        Route::post('update/{id}', [DriverJourneyController::class, 'update'])->name('driver_journey.update');
        Route::post('delete/{id}', [DriverJourneyController::class, 'destroy_undestroy'])->name('driver_journey.delete');
    });
    //  =================================  car ==========================
    Route::group(['prefix' => 'car'], function () {
        Route::get('/', [CarController::class, 'index'])->name('car.index');
        Route::get('get_car', [CarController::class, 'get_car'])->name('car.index');
        Route::get('create', [CarController::class, 'create'])->name('car.create'); //add
        Route::post('save', [CarController::class, 'save'])->name('car.save');
        Route::get('edit/{id}', [CarController::class, 'edit'])->name('car.edit');
        Route::post('update/{id}', [CarController::class, 'update'])->name('car.update');
        Route::post('delete/{id}', [CarController::class, 'destroy_undestroy'])->name('car.delete');
    });
    Route::group(['prefix' => 'price'], function () {
        Route::get('get_car_prices', [TransportPricesController::class, 'get_car_prices'])->name('get_car_prices.index');
        Route::get('/', [TransportPricesController::class, 'index'])->name('car.index');
        Route::get('update_price/{id}', [TransportPricesController::class, 'update_price'])->name('car.update_price');
    });
    //  =================================  transport_type ==========================
    Route::group(['prefix' => 'transport_type'], function () {
        Route::get('/', [TransportTypeController::class, 'index'])->name('transport_type.index');
        Route::get('get_transport_type', [TransportTypeController::class, 'get_transport_type'])->name('transport_type.index');
        Route::get('create', [TransportTypeController::class, 'create'])->name('transport_type.create'); //add
        Route::post('save', [TransportTypeController::class, 'save'])->name('transport_type.save');
        Route::get('edit/{id}', [TransportTypeController::class, 'edit'])->name('transport_type.edit');
        Route::post('update/{id}', [TransportTypeController::class, 'update'])->name('transport_type.update');
        Route::post('delete/{id}', [TransportTypeController::class, 'destroy_undestroy'])->name('transport_type.delete');
    });
    //  =================================  sale_agent ==========================
    Route::group(['prefix' => 'sale_agent'], function () {
        Route::get('/', [SaltAgentController::class, 'index'])->name('sale_agent.index');
        Route::get('get_sale_agent', [SaltAgentController::class, 'get_sale_agent'])->name('sale_agent.index');
        Route::get('create', [SaltAgentController::class, 'create'])->name('sale_agent.create'); //add
        Route::post('save', [SaltAgentController::class, 'save'])->name('sale_agent.save');
        Route::get('edit/{id}', [SaltAgentController::class, 'edit'])->name('sale_agent.edit');
        Route::post('update/{id}', [SaltAgentController::class, 'update'])->name('sale_agent.update');
        Route::post('delete/{id}', [SaltAgentController::class, 'destroy_undestroy'])->name('sale_agent.delete');
    });
    //  =================================  travel_agent ==========================
    Route::group(['prefix' => 'travel_agent'], function () {
        Route::get('/', [TravelAgentController::class, 'index'])->name('travel_agent.index');
        Route::get('get_travel_agent', [TravelAgentController::class, 'get_travel_agent'])->name('travel_agent.index');
        Route::get('create', [TravelAgentController::class, 'create'])->name('travel_agent.create'); //add
        Route::post('save', [TravelAgentController::class, 'save'])->name('travel_agent.save');
        Route::get('edit/{id}', [TravelAgentController::class, 'edit'])->name('travel_agent.edit');
        Route::post('update/{id}', [TravelAgentController::class, 'update'])->name('travel_agent.update');
        Route::post('delete/{id}', [TravelAgentController::class, 'destroy_undestroy'])->name('travel_agent.delete');
    });
    //  =================================  transport_journey_prices ==========================
    Route::group(['prefix' => 'transport_journey_prices'], function () {
        Route::get('/', [TransportJourneyPricesController::class, 'index'])->name('transport_journey_prices.index');
        Route::get('get_transport_journey_prices', [TransportJourneyPricesController::class, 'get_transport_journey_prices'])->name('transport_journey_prices.index');
        Route::get('create', [TransportJourneyPricesController::class, 'create'])->name('transport_journey_prices.create'); //add
        Route::post('save', [TransportJourneyPricesController::class, 'save'])->name('transport_journey_prices.save');
        Route::get('edit/{id}', [TransportJourneyPricesController::class, 'edit'])->name('transport_journey_prices.edit');
        Route::post('update/{id}', [TransportJourneyPricesController::class, 'update'])->name('transport_journey_prices.update');
        Route::post('delete/{id}', [TransportJourneyPricesController::class, 'destroy_undestroy'])->name('transport_journey_prices.delete');
        Route::get('update_price/{id}', [TransportJourneyPricesController::class, 'update_price'])->name('transport_journey_prices.update_price');
        Route::get('update_sale_agent/{id}', [TransportJourneyPricesController::class, 'update_sale_agent'])->name('transport_journey_prices.update_sale_agent');
        Route::get('update_sale_agent_com/{id}', [TransportJourneyPricesController::class, 'update_sale_agent_com'])->name('transport_journey_prices.update_sale_agent_com');
        Route::get('update_travel_agent/{id}', [TransportJourneyPricesController::class, 'update_travel_agent'])->name('transport_journey_prices.update_travel_agent');
        Route::get('update_tavel_agent_com/{id}', [TransportJourneyPricesController::class, 'update_tavel_agent_com'])->name('transport_journey_prices.update_tavel_agent_com');
        Route::get('update_driver_com/{id}', [TransportJourneyPricesController::class, 'update_driver_com'])->name('transport_journey_prices.update_driver_com');
    });
    //  =================================  driver ==========================
    Route::group(['prefix' => 'driver'], function () {
        Route::get('/', [DriverController::class, 'index'])->name('driver.index');
        Route::get('get_driver', [DriverController::class, 'get_driver'])->name('driver.index');
        Route::get('create', [DriverController::class, 'create'])->name('driver.create'); //add
        Route::post('save', [DriverController::class, 'save'])->name('driver.save');
        Route::get('edit/{id}', [DriverController::class, 'edit'])->name('driver.edit');
        Route::post('update/{id}', [DriverController::class, 'update'])->name('driver.update');
        Route::post('delete/{id}', [DriverController::class, 'destroy_undestroy'])->name('driver.delete');
    });
});
