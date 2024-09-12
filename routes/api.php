<?php

use Illuminate\Http\Request;
use App\Controllers\JobController;
use App\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Controllers\ProfileController;
use App\Controllers\DocumentController;
use App\Controllers\OwnerJobsController;
use App\Controllers\BankAccountController;
use App\Controllers\DriverWalletController;
use App\Controllers\DriverVehicleController;
use App\Controllers\HelpAndSupportController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\TwilioController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Api\MyBookingContoller;
use App\Http\Controllers\Api\OwnerGetJobREquests;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\DriverShowJobsController;
use App\Http\Controllers\Api\DriverJobRequestController;
use App\Http\Controllers\admin\PushNotificationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PushNotificationController as ApiPushNotificationController;

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

Route::middleware('jwt.auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('driver-vehicle-index/{id}', 'Api\DriverVehicleController@index');
});


Route::group(['namespace' => 'Api'], function () {
    Route::post('login', 'AuthController@login')->name('login');
    // Route::post('register/{id}', 'AuthController@register');
    // Social LogIn
    Route::post('socialLogin/{id}', 'AuthController@socialLogin');
    Route::post('appleLogin/{id}', 'AuthController@appleLogin');
    Route::get('/checkEmailExists', 'AuthController@checkEmailExists');
    Route::get('get-user-id', 'AuthController@getUserID');
    Route::post('login-otp-send', 'AuthController@user_otp_login_send')->name('login-otp-send');
    Route::post('login-otp-verify', 'AuthController@user_otp_login_verify')->name('login-otp-verify');
    Route::post('resendOtp', 'AuthController@resendOtp');

    Route::post('reset-password', 'AuthController@reset_password');
    Route::post('verify-otp', 'AuthController@verify_code');
    Route::post('change-password', 'AuthController@change_password');
});
Route::post('getLocation/{id}', 'Api\AuthController@getLocation');
Route::get('getLocations/{id}', 'Api\AuthController@getLocations');

Route::get('user-profile/{id}', 'Api\ProfileController@show');
Route::post('edit-profile/{id}', 'Api\ProfileController@update');
Route::get('users-imageget/{id}', 'Api\ProfileController@getImage');
Route::post('users-imageupdate/{id}', 'Api\ProfileController@updateImage');




//user-documents
Route::get('document-index/{id}', 'Api\DocumentController@index');
Route::post('document-store/{id}', 'Api\DocumentController@store');
//user-vehicles
Route::get('vehicles', 'Api\DriverVehicleController@getVehicles');
Route::get('driver-vehicle-show/{id}', 'Api\DriverVehicleController@show');
Route::post('driver-vehicle-add/{id}', 'Api\DriverVehicleController@store');
Route::put('/driver-vehicle/{id}/update-status', 'Api\DriverVehicleController@updateStatus');

//owner-jobs
Route::get('owners-jobs-data/{userId}', 'Api\JobController@getJobsByUserId');
Route::post('jobPayment/{id}', 'Api\JobController@jobPayment');
Route::post('job-store/{id}', 'Api\JobController@jobStore');

Route::post('owners-jobs-update/{userId}/{jobId}', 'Api\JobController@jobUpdate');

Route::post('owners-reviews', 'Api\ReviewController@ownerReview');
Route::get('driver-reviews/{driver_id}', 'Api\ReviewController@showFeedBackToDriver');
//users-queries
Route::post('user-query/{id}', 'Api\HelpAndSupportController@queryStore');
Route::get('getuserquery/{id}', 'Api\HelpAndSupportController@get');
Route::get('getAnswer/{id}/{userId}', 'Api\HelpAndSupportController@get_query');

Route::post('logout', 'Api\AuthController@logout');
//driver bank account
Route::get('bank-account/{id}', 'Api\BankAccountController@fetch');
Route::get('bank-account-data/{id}', 'Api\BankAccountController@getData');
Route::post('add-bank-account/{id}', 'Api\BankAccountController@store_account');
Route::post('update-bank-account/{id}', 'Api\BankAccountController@update_account');
Route::get('delete-bank-account/{accountId}', 'Api\BankAccountController@delete');
//############SHOW DRIVER JOBs########################
Route::get('driverjobs/{userid}', [DriverShowJobsController::class, 'get']);
Route::post('driverlocation/{userId}', [DriverShowJobsController::class, 'location']);
Route::get('getdriverlocation/{userId}', [DriverShowJobsController::class, 'getlocation']);
Route::get('getOwnerDetails/{jobId}', [DriverShowJobsController::class, 'getOwnerDetails']);
//driver request for withdrawal
Route::post('add-withdrawal-request/{id}', 'Api\DriverWalletController@add_withdrawal_request');
Route::get('getDriverWalletData/{driverId}', 'Api\DriverWalletController@getWalletDetails');
//driver request for job
Route::post('add-job-request-without-counter/{owner_id}/{driver_id}/{job_id}', 'Api\DriverJobRequestController@add_job_request_without_counter');
Route::post('add-job-request-with-counter/{owner_id}/{driver_id}/{job_id}', 'Api\DriverJobRequestController@add_job_request_counter');
Route::get('/job-requests/{job_id}', 'Api\DriverJobRequestController@getJobRequestsByJob');
Route::get('/job-requestsbyOwner/{job_id}', 'Api\DriverJobRequestController@getJobRequestsByOwner');
Route::delete('/cancel-job/{id}', 'Api\DriverJobRequestController@cancelJob');
Route::put('/updateRequestStatus/{id}', 'Api\DriverJobRequestController@updateStatus');
Route::get('driverCompleteJobCount/{id}', 'Api\DriverJobRequestController@getDriverCompletedStatus');
//Owner get job requests
Route::get('owner-get-job-requests/{driver_id}', 'Api\OwnerGetJobREquests@show_driverActiveAccountDetails');
Route::post('/owner-pay-driver/{id}', 'Api\OwnerGetJobREquests@ownerPayDriver');
Route::post('owner-accept-job-request/{id}', 'Api\OwnerGetJobREquests@owner_accept_job_request');
Route::post('ownerCompleteJob/{id}', 'Api\OwnerGetJobREquests@completeJob');
Route::post('/owner-cancle-request/{id}', 'Api\OwnerGetJobREquests@owner_cancle_request');
Route::delete('/owner-cancel-job/{id}', 'Api\OwnerJobsController@owner_cancelJob');
Route::delete('/cancelJob/{id}', 'Api\OwnerJobsController@cancelJob');

// ##################MY Booking###################
Route::get('myBooking/{ownerId}', [MyBookingContoller::class, 'get']);
// ############ PERMISIONS ############
Route::post('addPermission', [PermissionController::class, 'store']);
Route::post('updatePermission', [PermissionController::class, 'update']);
//  ############### Push Notifications #############
Route::get('getNotificationCount/{userId}', [ApiPushNotificationController::class, 'getNotificationCount']);
Route::get('/readNotifications/{userId}', [ApiPushNotificationController::class, 'userRecevied']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
