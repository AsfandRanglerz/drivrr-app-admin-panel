<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubadminController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamAController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\BusinessOwnerController;
use App\Http\Controllers\Admin\DriverVehicleController;
use App\Http\Controllers\Admin\HelpAndSupportController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\HandelBusinessOwnerPayments;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TwilioController;

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

Route::get('/admin', [AuthController::class, 'getLoginPage']);
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/admin-forgot-password', [AdminController::class, 'forgetPassword']);
Route::post('/admin-reset-password-link', [AdminController::class, 'adminResetPasswordLink']);
Route::get('/change_password/{id}', [AdminController::class, 'change_password']);
Route::post('/admin-reset-password', [AdminController::class, 'ResetPassword']);
Route::get('/privacy-policy-drivrr', [SecurityController::class, 'webViewPrivacyPolicy']);
Route::get('/about-us-drivrr', [SecurityController::class, 'webViewAboutUs']);
Route::get('/terms-conditions-drivrr', [SecurityController::class, 'webViewTermCondition']);


Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'getdashboard'])->middleware('permission:Dashboard');
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
    Route::get('/sub-admin', [SubadminController::class, 'index'])->name('subadmin.index')->middleware('permission:SubAdmin');
    Route::get('/sub-admin/create', [SubadminController::class, 'create'])->name('subAdmin.create');
    Route::post('/sub-admin/store', [SubadminController::class, 'store'])->name('subAdmin.store');
    Route::get('/sub-admin/edit/{id}', [SubadminController::class, 'edit'])->name('subAdmin.edit');
    Route::post('/sub-admin/update/{id}', [SubadminController::class, 'update'])->name('subAdmin.update');
    Route::delete('/sub-admin/destroy/{id}', [SubadminController::class, 'destroy'])->name('subAdmin.destroy');
    Route::get('/sub-admin/status/{id}', [SubadminController::class, 'status'])->name('subAdmin.status');
    Route::post('/assign-permissions/{user}', [SubadminController::class, 'storePermissions'])->name('user.assign.permissions');
    Route::put('/update-permissions/{user}', [SubadminController::class, 'updatePermissions'])->name('user.update.permissions');

    Route::resource('vehicle', VehicleController::class)->middleware('permission:Vehicles');
    Route::resource('businessOwner', BusinessOwnerController::class)->middleware('permission:Business Owner');
    Route::resource('driver', DriverController::class)->middleware('permission:Driver');
    // Route::resource('document', DocumentController::class);
    //Document Controller
    Route::get('document/{id}', [DocumentController::class, 'index'])->name('document.index');
    Route::get('document-create/{id}', [DocumentController::class, 'create'])->name('document.create');
    Route::post('document-store/{id}', [DocumentController::class, 'store'])->name('document.store');
    Route::get('document-edit/{id}', [DocumentController::class, 'edit'])->name('document.edit');
    Route::post('document-update/{id}', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('document-destroy/{id}', [DocumentController::class, 'destroy'])->name('document.destroy');
    //Vehicles Controller
    Route::get('driver-vehicle/{id}', [DriverVehicleController::class, 'index'])->name('driver-vehicle.index');
    Route::get('driver-vehicle-create/{id}', [DriverVehicleController::class, 'create'])->name('driver-vehicle.create');
    Route::post('driver-vehicle-store/{id}', [DriverVehicleController::class, 'store'])->name('driver-vehicle.store');
    Route::get('driver-vehicle-edit/{id}', [DriverVehicleController::class, 'edit'])->name('driver-vehicle.edit');
    Route::post('driver-vehicle-update/{id}', [DriverVehicleController::class, 'update'])->name('driver-vehicle.update');
    Route::delete('driver-vehicle-destroy/{id}', [DriverVehicleController::class, 'destroy'])->name('driver-vehicle.destroy');
    // Route::delete('driver-vehicle-show/{id}', [DriverVehicleController::class, 'show'])->name('driver-vehicle.show');

    //owner-jobs
    Route::get('owner-job/{id}', [JobController::class, 'index'])->name('owner-job.index');
    Route::get('owner-job-edit/{id}', [JobController::class, 'edit'])->name('owner-job.edit');
    // Route::get('owner-job-show/{id}', [JobController::class, 'show'])->name('owner-job.show');
    Route::get('/active-job', [JobController::class, 'show']);
    Route::post('owner-job-update/{id}', [JobController::class, 'update'])->name('owner-job.update');
    Route::delete('owner-job-destroy/{id}', [JobController::class, 'destroy'])->name('owner-job.destroy');
    ######## Roles&Permission######
    Route::post('/add-role', [RoleController::class, 'store'])->name('addRole');
    Route::post('/roles/assign/permissions/{role}', [RoleController::class, 'assignPermissions'])->name('roles.assign.permissions');
    Route::put('/roles/update/permissions/{role}', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions');
    ######## Help And Sppurt Controler #####
    Route::get('help-and-support', [HelpAndSupportController::class, 'index'])->name('help-and-support.index')->middleware('permission:Help & Support');
    Route::get('send-response/{id}/{q_id}', [HelpAndSupportController::class, 'send'])->name('send-response.send');
    //Wallet Controller
    Route::get('show-wallets', [WalletController::class, 'index'])->name('show-wallets')->middleware('permission:DriverWallets');
    // Route::get('request-counter', [WalletController::class, 'request_counter'])->name('request-counter');
    Route::get('show-withdrawal-requests', [WalletController::class, 'show_withdrawal_requests'])->middleware('permission:WithdrawRequest')
        ->name('show-withdrawal-requests');
    Route::post('action-on-request/{id}/{amount}', [WalletController::class, 'send_money'])->name('action-on-request');
    Route::delete('delete-approve-request/{id}', [WalletController::class, 'delete_request'])->name('delete-approve-request');
    Route::get('show-withdrawals-receipts/{id}', [WalletController::class, 'show_receipts'])->name('show-withdrawals-receipts');
    // ############## Push Notification ##############
    Route::get('notification', [PushNotificationController::class, 'notificationIndex'])->name('notifications.index')->middleware('permission:Notification');
    Route::post('notifications/store', [PushNotificationController::class, 'notificationStore'])->name('notifications.store');
    // ############# Driver Reviews#########
    Route::get('/driverReviews', [ReviewController::class, 'reviewIndex'])->name('driverreview.index');
    // ############# OTP #####
    Route::get('/otp', [TwilioController::class, 'indexOtp'])->name('otp.index');
    Route::post('/sendOtp', [TwilioController::class, 'Otp'])->name('otp.store');
     // ############# Complete Job #####
     Route::get('/completeJobs', [HandelBusinessOwnerPayments::class, 'completeJobs'])->name('completedjobs.index');
});
