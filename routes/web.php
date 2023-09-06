<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\Journey_SlotController;
use App\Http\Controllers\Admin\JourneyController;
use App\Http\Controllers\Admin\DriverJourneyController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\TransportTypeController;

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


Route::get('admin/login',[AdminController::class, 'index']);
Route::post('admin/checklogin',[AdminController::class, 'checklogin']);
Route::get('admin/dashboard',[AdminController::class, 'dashboard'])->name('dashboard');
Route::get('admin/logout',[AdminController::class, 'logout']);



 //  =================================  user ==========================
 Route::group(['prefix'=>'admin/user'],function(){
    Route::get('/',[UserController::class, 'index'])->name('user.index');
    Route::get('get_user',[UserController::class, 'get_user'])->name('user.index');
    Route::get('create',[UserController::class, 'create'])->name('user.create'); //add
    Route::post('save',[UserController::class, 'save'])->name('user.save');
    Route::get('edit/{id}',[UserController::class, 'edit'])->name('user.edit');
    Route::post('update/{id}',[UserController::class, 'update'])->name('user.update');
    Route::post('delete/{id}',[UserController::class, 'destroy_undestroy'])->name('user.delete');
});

 //  =================================  location ==========================
 Route::group(['prefix'=>'admin/location'],function(){
    Route::get('/',[LocationController::class, 'index'])->name('location.index');
    Route::get('get_location',[LocationController::class, 'get_location'])->name('location.index');
    Route::get('create',[LocationController::class, 'create'])->name('location.create'); //add
    Route::post('save',[LocationController::class, 'save'])->name('location.save');
    Route::get('edit/{id}',[LocationController::class, 'edit'])->name('location.edit');
    Route::post('update/{id}',[LocationController::class, 'update'])->name('location.update');
    Route::post('delete/{id}',[LocationController::class, 'destroy_undestroy'])->name('location.delete');
});

 //  =================================  journey_slot ==========================
 Route::group(['prefix'=>'admin/journey_slot'],function(){
    Route::get('/',[Journey_SlotController::class, 'index'])->name('journey_slot.index');
    Route::get('get_journey_slot',[Journey_SlotController::class, 'get_journey_slot'])->name('journey_slot.index');
    Route::get('create',[Journey_SlotController::class, 'create'])->name('journey_slot.create'); //add
    Route::post('save',[Journey_SlotController::class, 'save'])->name('journey_slot.save');
    Route::get('edit/{id}',[Journey_SlotController::class, 'edit'])->name('journey_slot.edit');
    Route::post('update/{id}',[Journey_SlotController::class, 'update'])->name('journey_slot.update');
    Route::post('delete/{id}',[Journey_SlotController::class, 'destroy_undestroy'])->name('journey_slot.delete');
});


 //  =================================  journey ==========================
 Route::group(['prefix'=>'admin/journey'],function(){
    Route::get('/',[JourneyController::class, 'index'])->name('journey.index');
    Route::get('get_journey',[JourneyController::class, 'get_journey'])->name('journey.index');
    Route::get('create',[JourneyController::class, 'create'])->name('journey.create'); //add
    Route::post('save',[JourneyController::class, 'save'])->name('journey.save');
    Route::get('edit/{id}',[JourneyController::class, 'edit'])->name('journey.edit');
    Route::post('update/{id}',[JourneyController::class, 'update'])->name('journey.update');
    Route::post('delete/{id}',[JourneyController::class, 'destroy_undestroy'])->name('journey.delete');
});

 //  =================================  driver_journey ==========================
 Route::group(['prefix'=>'admin/driver_journey'],function(){
    Route::get('/',[DriverJourneyController::class, 'index'])->name('driver_journey.index');
    Route::get('get_driver_journey',[DriverJourneyController::class, 'get_driver_journey'])->name('driver_journey.index');
    Route::get('create',[DriverJourneyController::class, 'create'])->name('driver_journey.create'); //add
    Route::post('save',[DriverJourneyController::class, 'save'])->name('driver_journey.save');
    Route::get('edit/{id}',[DriverJourneyController::class, 'edit'])->name('driver_journey.edit');
    Route::post('update/{id}',[DriverJourneyController::class, 'update'])->name('driver_journey.update');
    Route::post('delete/{id}',[DriverJourneyController::class, 'destroy_undestroy'])->name('driver_journey.delete');
});


//  =================================  car ==========================
Route::group(['prefix'=>'admin/car'],function(){
    Route::get('/',[CarController::class, 'index'])->name('car.index');
    Route::get('get_car',[CarController::class, 'get_car'])->name('car.index');
    Route::get('create',[CarController::class, 'create'])->name('car.create'); //add
    Route::post('save',[CarController::class, 'save'])->name('car.save');
    Route::get('edit/{id}',[CarController::class, 'edit'])->name('car.edit');
    Route::post('update/{id}',[CarController::class, 'update'])->name('car.update');
    Route::post('delete/{id}',[CarController::class, 'destroy_undestroy'])->name('car.delete');
});



 //  =================================  transport_type ==========================
 Route::group(['prefix'=>'admin/transport_type'],function(){
    Route::get('/',[TransportTypeController::class, 'index'])->name('transport_type.index');
    Route::get('get_transport_type',[TransportTypeController::class, 'get_transport_type'])->name('transport_type.index');
    Route::get('create',[TransportTypeController::class, 'create'])->name('transport_type.create'); //add
    Route::post('save',[TransportTypeController::class, 'save'])->name('transport_type.save');
    Route::get('edit/{id}',[TransportTypeController::class, 'edit'])->name('transport_type.edit');
    Route::post('update/{id}',[TransportTypeController::class, 'update'])->name('transport_type.update');
    Route::post('delete/{id}',[TransportTypeController::class, 'destroy_undestroy'])->name('transport_type.delete');
});
