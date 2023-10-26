<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\DocumentController;
use App\Controllers\DriverVehicleController;
use App\Controllers\JobController;

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

Route::group(['namespace' => 'Api'], function () {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('reset-password', 'AuthController@reset_password');
    Route::post('verify-otp', 'AuthController@verify_code');
    Route::post('change-password', 'AuthController@change_password');
});

Route::middleware(['auth:sanctum'])->group(function () {
   Route::get('logout', 'Api\AuthController@logout');
   //edit-user-profile
   Route::post('edit-profile/{id}','Api\ProfileController@update');
   //user-documents
   Route::get('document-index/{id}','Api\DocumentController@index');
   Route::get('document-index/{id}','Api\DocumentController@index');
   //user-vehicles
   Route::get('driver-vehicle-index/{id}','Api\DriverVehicleController@index');
   Route::get('driver-vehicle-create/{id}','Api\DriverVehicleController@create');
   Route::post('driver-vehicle-add/{id}','Api\DriverVehicleController@store');
   //owner-jobs
   Route::post('owner-index/{id}','Api\JobController@store');
   Route::post('user-query/{id}','Api\HelpAndSupportController@queryStore');
   //get owners jobs
   Route::get('owners-jobs-show/{id}','Api\OwnerJobsController@index');
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();
});
