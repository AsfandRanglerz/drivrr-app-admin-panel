<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamAController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TwilioController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\BusinessOwnerController;
use App\Http\Controllers\Admin\DriverVehicleController;
use App\Http\Controllers\Admin\HelpAndSupportController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\HandelBusinessOwnerPayments;
use App\Http\Controllers\Admin\LisenceApprovelController;
use App\Http\Controllers\Admin\SubadminController;
use App\Http\Controllers\Admin\WithDrawalController;

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
/*Admin routes
 * */

Route::get('/cache_clear', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('route:clear');
    return 'Application cache cleared!';
});
Route::get('/admin-login', [AuthController::class, 'getLoginPage']);
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/admin-forgot-password', [AdminController::class, 'forgetPassword']);
Route::post('/admin-reset-password-link', [AdminController::class, 'adminResetPasswordLink']);
Route::get('/change_password/{id}', [AdminController::class, 'change_password']);
Route::post('/admin-reset-password', [AdminController::class, 'ResetPassword']);
Route::get('/privacy-policy-drivrr', [SecurityController::class, 'webViewPrivacyPolicy']);
Route::get('/about-us-drivrr', [SecurityController::class, 'webViewAboutUs']);
Route::get('/terms-conditions-drivrr', [SecurityController::class, 'webViewTermCondition']);

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'getdashboard']);
    Route::get('profile', [AdminController::class, 'getProfile']);
    Route::post('update-profile', [AdminController::class, 'update_profile']);
    Route::get('Privacy-policy', [SecurityController::class, 'PrivacyPolicy'])->middleware('permission:Privacy policies');
    Route::get('privacy-policy-edit', [SecurityController::class, 'PrivacyPolicyEdit']);
    Route::post('privacy-policy-update', [SecurityController::class, 'PrivacyPolicyUpdate']);
    Route::get('term-condition', [SecurityController::class, 'TermCondition'])->middleware('permission:Term&Conditions');
    Route::get('term-condition-edit', [SecurityController::class, 'TermConditionEdit']);
    Route::post('term-condition-update', [SecurityController::class, 'TermConditionUpdate']);

    Route::get('about-us', [SecurityController::class, 'AboutUs'])->middleware('permission:AboutUs');
    Route::get('about-us-edit', [SecurityController::class, 'AboutUsEdit']);
    Route::post('about-us-update', [SecurityController::class, 'AboutUsUpdate']);

    Route::get('logout', [AdminController::class, 'logout']);
    Route::get('notifications-seen', [AdminController::class, 'seen_notification'])->name('notifications-seen');

    // Owner Payments
    Route::get('business-owner-payments', [HandelBusinessOwnerPayments::class, 'show_owner_payments'])->middleware('permission:Payments')->name('business-owner-payments');
    /**driver */
    Route::get('driver/status/{id}', [DriverController::class, 'status'])->name('driver.status');
    /**owner */
    Route::get('owner/status/{id}', [BusinessOwnerController::class, 'status'])->name('owner.status');
    /**document */
    Route::get('document/status/{id}/{key}', [DocumentController::class, 'status'])->name('document.status');
    /**driver-vehicle */
    Route::get('driver-vehicle.status/{id}', [DriverVehicleController::class, 'status'])->name('driver-vehicle.status');
    /**owner-request */
    Route::get('owner-job-status/{id}/{key}', [JobController::class, 'status'])->name('owner-job.status');
    #######Roles&Permission#####
    Route::get('/role-permission', [RoleController::class, 'index'])->name('roles-permission.index')->middleware('permission:Roles & Permissions');
    #######SubAdmin#######
    Route::controller(SubadminController::class)->group(function () {
        Route::get('/subadmin',  'subadminIndex')->name('subadmin.index')->middleware('permission:SubAdmin');
        Route::post('/subadmin-create',  'subadminCreate')->name('subadmin.create')->middleware('permission:SubAdmin');
        Route::get('/subadminData',  'subadminData')->name('subadmin.get')->middleware('permission:SubAdmin');
        Route::get('/subadmin/{id}',  'showSubAdmin')->name('subadmin.show')->middleware('permission:SubAdmin');
        Route::post('/subadminUpdate/{id}',  'updateAdmin')->name('subadmin.update')->middleware('permission:SubAdmin');
        Route::get('/subadmin/delete/{id}',  'deleteSubadmin')->name('subadmin.delete')->middleware('permission:SubAdmin');
        Route::get('/get-permissions/{user}',  'fetchUserPermissions')->name('get.permissions')->middleware('permission:SubAdmin');
        Route::post('/update-permissions/{user}',  'updatePermissions')->name('update.user.permissions')->middleware('permission:SubAdmin');
    });

    Route::resource('vehicle', VehicleController::class)->middleware('permission:Vehicles');
    // ############## Busniess Owner ############
    Route::controller(BusinessOwnerController::class)->group(function () {
        Route::get('/busniessOwner',  'busniessOwnerIndex')->name('busniessOwner.index')->middleware('permission:Business Owner');
        Route::post('/busniessOwner-create',  'busniessOwnerCreate')->name('busniessOwner.create')->middleware('permission:Business Owner');
        Route::post('/busniessOwnerData',  'busniessOwnerData')->name('busniessOwner.get')->middleware('permission:Business Owner');
        Route::get('/busniessOwner/{id}',  'showBusniessOwner')->name('busniessOwner.show')->middleware('permission:Business Owner');
        Route::post('/busniessOwnerUpdate/{id}',  'updateBusniessOwner')->name('busniessOwner.update')->middleware('permission:Business Owner');
        Route::get('/busniessOwner/delete/{id}',  'deleteBusniessOwner')->name('busniessOwner.delete')->middleware('permission:Business Owner');
        //   User Blocking status
        Route::post('/update-owner-status/{id}',  'updateBlockStatus')->name('busniessOwnerBlock.update')->middleware('permission:Business Owner');
    });
    // ############## Drivers ############
    Route::controller(DriverController::class)->group(function () {
        Route::get('/drivers',  'driversIndex')->name('drivers.index')->middleware('permission:Driver');
        Route::post('/drivers-create',  'driversCreate')->name('drivers.create')->middleware('permission:Driver');
        Route::post('/driversData',  'driversData')->name('drivers.get')->middleware('permission:Driver');
        Route::get('/drivers/{id}',  'showDrivers')->name('drivers.show')->middleware('permission:Driver');
        Route::post('/driversUpdate/{id}',  'updateDrivers')->name('drivers.update')->middleware('permission:Driver');
        Route::get('/drivers/delete/{id}',  'deleteDrivers')->name('drivers.delete')->middleware('permission:Driver');
        //   User Blocking status
        Route::post('/update-driver-status/{id}',  'updateBlockStatus')->name('driversBlock.update')->middleware('permission:Driver');
    });
    // Route::resource('document', DocumentController::class);
    // ############## Documents ############
    Route::controller(DocumentController::class)->group(function () {
        Route::get('document/{id}',  'index')->name('document.index');
        Route::get('document-create/{id}',  'create')->name('document.create');
        Route::post('document-store/{id}',  'store')->name('document.store');
        Route::get('document-edit/{id}',  'edit')->name('document.edit');
        Route::post('document-update/{id}',  'update')->name('document.update');
        Route::delete('document-destroy/{id}',  'destroy')->name('document.destroy');
    });
    // ############## Drivers Vehicles ############
    Route::controller(DriverVehicleController::class)->group(function () {
        Route::get('driver-vehicle/{id}',  'index')->name('driver-vehicle.index');
        Route::get('driver-vehicle-create/{id}',  'create')->name('driver-vehicle.create');
        Route::post('driver-vehicle-store/{id}',  'store')->name('driver-vehicle.store');
        Route::get('driver-vehicle-edit/{id}',  'edit')->name('driver-vehicle.edit');
        Route::post('driver-vehicle-update/{id}',  'update')->name('driver-vehicle.update');
        Route::delete('driver-vehicle-destroy/{id}',  'destroy')->name('driver-vehicle.destroy');
        // Route::delete('driver-vehicle-show/{id}',  'show')->name('driver-vehicle.show');
    });
    // ############## Lisence Approvel  ############
    Route::controller(LisenceApprovelController::class)->group(function () {
        Route::get('/lisenceApprovel',  'lisenceApprovelIndex')->name('lisenceApprovel.index');
        Route::post('/lisenceApprovel-create',  'lisenceApprovelCreate')->name('lisenceApprovel.create');
        Route::post('/lisenceApprovelData',  'lisenceApprovelData')->name('lisenceApprovel.get');
        Route::get('/lisenceApprovel/{id}/status',  'getStatus')->name('lisenceApprovel.status');
        Route::post('/lisenceApprovel/{id}/update-is_active',  'updateStatus')->name('orders.update-is_active');
        Route::get('/lisenceApprovel/counter',  'getlisenceApprovelCount')->name('lisenceApprovel.count');
        // Route::delete('driver-vehicle-show/{id}',  'show')->name('driver-vehicle.show');
    });
    // ############## With Drawal Request  ############
    Route::controller(WithDrawalController::class)->group(function () {
        Route::post('/paymentRequestData',  'paymentRequestData')->name('paymentRequest.get')->middleware('permission:WithDrawal Request');
        Route::get('/paymentRequest',  'paymentRequestIndex')->name('paymentRequest.index')->middleware('permission:WithdrawRequest');
        Route::post('/paymentRequest-create',  'paymentRequestCreate')->name('paymentRequest.create')->middleware('permission:WithdrawRequest');
        Route::get('/paymentRequest/{id}',  'showPaymentRequest')->name('paymentRequest.show')->middleware('permission:WithdrawRequest');
        Route::post('/paymentRequestUpdate/{id}',  'updatePaymentRequest')->name('paymentRequest.update')->middleware('permission:WithdrawRequest');
        Route::get('/paymentRequest/delete/{id}',  'deletePaymentRequest')->name('paymentRequest.delete')->middleware('permission:WithdrawRequest');
        Route::get('/paymentRequestCounter',  'getPaymentRequestCount')->name('paymentRequest.count')->middleware('permission:WithdrawRequest');
        // User Account Details
        Route::get('/paymentRequest/bankInfo/{userId}',  'getAccountDetails')->name('paymentAccount.index')->middleware('permission:WithdrawRequest');
    });
    // ############## With Drawal Request  ############
    Route::controller(WalletController::class)->group(function () {
        Route::post('/paymentHistoryData',  'paymentHistoryData')->name('paymentHistory.get')->middleware('permission:DriverWallets');
        Route::get('/paymentHistory',  'paymentHistoryIndex')->name('paymentHistory.index')->middleware('permission:DriverWallets');
        Route::get('/paymentHistory/history/{id}',  'getPaymentHistory')->name('userPaymentHistory.index')->middleware('permission:DriverWallets');

    });
    // ############## Owner Jobs  ############
    Route::controller(JobController::class)->group(function () {
        Route::get('owner-job/{id}',  'index')->name('owner-job.index');
        Route::get('owner-job-edit/{id}',  'edit')->name('owner-job.edit');
        // Route::get('owner-job-show/{id}',  'show')->name('owner-job.show');
        Route::get('/active-job',  'show');
        Route::post('owner-job-update/{id}',  'update')->name('owner-job.update');
        Route::delete('owner-job-destroy/{id}',  'destroy')->name('owner-job.destroy');
    });
    ######## Roles&Permission######
    Route::post('/add-role', [RoleController::class, 'store'])->name('addRole');
    Route::post('/roles/assign/permissions/{role}', [RoleController::class, 'assignPermissions'])->name('roles.assign.permissions');
    Route::put('/roles/update/permissions/{role}', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions');
    ######## Help And Sppurt Controler #####
    Route::get('help-and-support', [HelpAndSupportController::class, 'index'])->name('help-and-support.index')->middleware('permission:Help & Support');
    Route::get('send-response/{id}/{q_id}', [HelpAndSupportController::class, 'send'])->name('send-response.send');

    // ############## Push Notification ##############
    Route::get('notification', [PushNotificationController::class, 'notificationIndex'])->name('notifications.index')->middleware('permission:Notification');
    Route::post('notifications/store', [PushNotificationController::class, 'notificationStore'])->name('notifications.store');
    // ############# Driver Reviews#########
    Route::get('/driverReviews', [ReviewController::class, 'reviewIndex'])->name('driverreview.index');
    // ############# OTP #####
    Route::get('/otp', [TwilioController::class, 'indexOtp'])->name('otp.index');
    Route::post('/sendOtp', [TwilioController::class, 'Otp'])->name('otp.store');
    // ############# Complete Job #####
    Route::get('/completeJobs', [HandelBusinessOwnerPayments::class, 'completeJobs'])->name('completedjobs.index')->middleware('permission:CompleteJob');
});
