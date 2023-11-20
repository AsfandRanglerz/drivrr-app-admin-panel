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
use App\Controllers\DriverWalletController;
use App\Http\Controllers\Api\DriverShowJobsController;
use App\Http\Controllers\Api\DriverJobRequestController;
use App\Http\Controllers\Api\OwnerGetJobREquests;

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
Route::put('/driver-vehicle/{id}/update-status', 'Api\DriverVehicleController@updateStatus');

//owner-jobs
Route::get('owners-jobs-data/{userId}', 'Api\JobController@getJobsByUserId');
Route::post('owners-jobs/{id}', 'Api\JobController@jobStore');
Route::post('owners-jobs-update/{userId}/{jobId}', 'Api\JobController@jobUpdate');

Route::post('owners-reviews', 'Api\ReviewController@ownerReview');
Route::get('driver-reviews/{id}', 'Api\ReviewController@showFeedBackToDriver');
//users-queries
Route::post('user-query/{id}', 'Api\HelpAndSupportController@queryStore');
Route::post('logout', 'Api\AuthController@logout');
//driver bank account
Route::get('bank-account/{id}', 'Api\BankAccountController@fetch');
Route::get('bank-account-data/{id}', 'Api\BankAccountController@getData');
Route::post('add-bank-account/{id}', 'Api\BankAccountController@store_account');
Route::post('update-bank-account/{id}', 'Api\BankAccountController@update_account');
Route::get('delete-bank-account/{accountId}', 'Api\BankAccountController@delete');
//############SHOW DRIVER JOBs########################
Route::get('driverjobs/{userid}', [DriverShowJobsController::class, 'get']);
Route::post('driverlocation/{userId}',[DriverShowJobsController::class,'location']);

//driver request for withdrawal
Route::post('add-withdrawal-request/{id}', 'Api\DriverWalletController@add_withdrawal_request');
//driver request for job
Route::post('add-job-request-without-counter/{owner_id}/{driver_id}/{job_id}', 'Api\DriverJobRequestController@add_job_request_without_counter');
Route::post('add-job-request-with-counter/{owner_id}/{driver_id}/{job_id}', 'Api\DriverJobRequestController@add_job_request_counter');
//Owner get job requests
Route::get('owner-get-job-requests/{owner_id}', 'Api\OwnerGetJobREquests@show_job_requests');
Route::post('owner-accept-job-request/{id}', 'Api\OwnerGetJobREquests@owner_accept_job_request');




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
