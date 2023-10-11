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
use App\Http\Controllers\Admin\TravelAgentCommissionController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\DriverCommissionController;
use App\Http\Controllers\Report\OrderController;
use App\Http\Controllers\Report\SubAdminOrderController;
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

Route::get('error/500', function () {
    return view('error.error_500');
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
    Route::group(['prefix' => 'travel_agent_commission'], function () {
        Route::get('get_travel_agent_commission', [TravelAgentCommissionController::class, 'get_commision_prices'])->name('get_travel_agent_commission.index');
        Route::get('/', [TravelAgentCommissionController::class, 'index'])->name('travel_agent_commission.index');
        Route::get('update_price/{id}', [TravelAgentCommissionController::class, 'update_price'])->name('travel_agent_commission.update_price');
    });
    Route::group(['prefix' => 'driver_commission'], function () {
        Route::get('get_driver_commission', [DriverCommissionController::class, 'get_commision_prices'])->name('driver_commission.index');
        Route::get('/', [DriverCommissionController::class, 'index'])->name('driver_commission.index');
        Route::get('update_price/{id}', [DriverCommissionController::class, 'update_price'])->name('driver_commission.update_price');
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
        Route::get('update_agent_com/{transport_journey_price_id}', [TransportJourneyPricesController::class, 'update_driver_com'])->name('transport_journey_prices.update_driver_com');
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

    Route::group(['prefix' => 'order'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::get('get_order', [OrderController::class, 'get_order'])->name('order.index');
        Route::get('details_list/{order_id}', [OrderController::class, 'get_order_details_list'])->name('order.get_order_details_list');
        Route::post('update_order_status/{order_id}', [OrderController::class, 'update_order_status'])->name('order.update_order_status');
        Route::post('update_order_detail_driver/{order_detail_id}', 
                        [OrderController::class, 'update_order_detail_driver']);
        Route::get('create', [OrderController::class, 'create'])->name('order.create'); //add
        Route::post('save', [OrderController::class, 'save'])->name('order.save');
        Route::get('edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
        Route::post('update/{id}', [OrderController::class, 'update'])->name('order.update');
        Route::post('delete/{id}', [OrderController::class, 'destroy_undestroy'])->name('order.delete');
    });
//admin/sub_admin
 
});


Route::group([ 'prefix' => 'admin/sub_admin','middleware' => 'sub_admin_auth'], function () {

    //  =================================  order ==========================
    Route::group(['prefix' => 'order'], function () {
        Route::get('/', [SubAdminOrderController::class, 'index'])->name('sub_admin.order.index');
        Route::get('get_order', [SubAdminOrderController::class, 'get_order'])->name('sub_admin.order.index');
        Route::get('get_driver_order', [SubAdminOrderController::class, 'get_drivers_order'])->name('sub_admin.get_drivers_order.index');
        Route::get('details_list/{order_id}', [SubAdminOrderController::class, 'get_order_details_list'])->name('sub_admin.order.get_order_details_list');
        // Route::post('update_order_status/{order_id}', [SubAdminOrderController::class, 'update_order_status'])->name('sub_admin.order.update_order_status');
        // Route::get('create', [SubAdminOrderController::class, 'create'])->name('sub_admin.order.create'); //add
        // Route::post('save', [SubAdminOrderController::class, 'save'])->name('sub_admin.order.save');
        // Route::get('edit/{id}', [SubAdminOrderController::class, 'edit'])->name('sub_admin.order.edit');
        // Route::post('update/{id}', [SubAdminOrderController::class, 'update'])->name('sub_admin.order.update');
        // Route::post('delete/{id}', [SubAdminOrderController::class, 'destroy_undestroy'])->name('sub_admin.order.delete');
    });
});
//  =================================  contact_us ==========================
Route::group(['prefix' => 'admin/contactus'], function () {
    Route::get('/', [ContactUsController::class, 'index'])->name('contact_us.index');
    Route::get('get_contactus', [ContactUsController::class, 'get_contactus'])->name('contact_us.index');
    Route::get('create', [ContactUsController::class, 'create'])->name('contact_us.create'); //add
    Route::post('save', [ContactUsController::class, 'save'])->name('contact_us.save');
    Route::get('edit/{id}', [ContactUsController::class, 'edit'])->name('contact_us.edit');
    Route::post('update/{id}', [ContactUsController::class, 'update'])->name('contact_us.update');
    Route::post('delete/{id}', [ContactUsController::class, 'destroy_undestroy'])->name('contact_us.delete');
});
