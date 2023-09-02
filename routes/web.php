<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\Journey_SlotController;

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
