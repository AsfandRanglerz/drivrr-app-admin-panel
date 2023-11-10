<?php

use Illuminate\Http\Request;
use App\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Controllers\ProfileController;
use App\Controllers\DocumentController;
use App\Controllers\DriverVehicleController;
use App\Controllers\HelpAndSupportController;
use App\Controllers\OwnerJobsController;
use App\Controllers\ReviewController;
use App\Controllers\BankAccountController;

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
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register/{id}', 'AuthController@register');
    Route::get('/checkEmailExists', 'AuthController@checkEmailExists');
    Route::get('get-user-id', 'AuthController@getUserID');
    Route::post('login-otp-send', 'AuthController@user_otp_login_send')->name('login-otp-send');
    Route::post('login-otp-verify', 'AuthController@user_otp_login_verify')->name('login-otp-verify');
    Route::post('reset-password', 'AuthController@reset_password');
    Route::post('verify-otp', 'AuthController@verify_code');
    Route::post('change-password', 'AuthController@change_password');
});

Route::get('user-profile/{id}', 'Api\ProfileController@show');
Route::post('edit-profile/{id}', 'Api\ProfileController@update');
Route::get('users-imageget/{id}', 'Api\ProfileController@getImage');
Route::post('users-imageupdate/{id}', 'Api\ProfileController@updateImage');



Route::middleware(['auth:sanctum'])->group(function () {
    //edit-user-profile
    //user-documents
    // Route::get('document-index/{id}', 'Api\DocumentController@index');
    // Route::get('document-index/{id}', 'Api\DocumentController@index');
    // //user-vehicles
    // Route::get('driver-vehicle-index/{id}', 'Api\DriverVehicleController@index');
    // Route::get('driver-vehicle-create/{id}', 'Api\DriverVehicleController@create');
    // Route::post('driver-vehicle-add/{id}', 'Api\DriverVehicleController@store');
    // //owner-jobs
    // Route::post('owners-jobs/{id}', 'Api\JobController@jobStore');

    //get owners jobs
    // Route::get('owners-jobs-show/{id}', 'Api\OwnerJobsController@index');
});
    //user-documents
Route::get('document-index/{id}', 'Api\DocumentController@index');
Route::post('document-store/{id}', 'Api\DocumentController@store');
//user-vehicles
Route::get('vehicles', 'Api\DriverVehicleController@getVehicles');
Route::get('driver-vehicle-index/{id}', 'Api\DriverVehicleController@index');
Route::get('driver-vehicle-show/{id}', 'Api\DriverVehicleController@show');
Route::post('driver-vehicle-add/{id}', 'Api\DriverVehicleController@store');
//owner-jobs
Route::post('owners-jobs/{id}', 'Api\JobController@jobStore');
Route::post('owners-reviews', 'Api\ReviewController@ownerReview');
Route::get('driver-reviews/{id}', 'Api\ReviewController@showFeedBackToDriver');
//users-queries
Route::post('user-query/{id}', 'Api\HelpAndSupportController@queryStore');
Route::post('logout', 'Api\AuthController@logout');
//driver bank account
Route::post('add-bank-account/{id}', 'Api\BankAccountController@store_account');




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
