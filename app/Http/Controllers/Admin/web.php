<?php

use App\Http\Controllers\Admin\AboutusController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\OfficerController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\BusinessOwnerController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\DriverController;
use Illuminate\Support\Facades\Route;

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
/*
Admin routes
 * */
Route::get('/admin', [AuthController::class, 'getLoginPage']);
Route::post('admin/login', [AuthController::class, 'Login']);
Route::get('/admin-forgot-password', [AdminController::class, 'forgetPassword']);
Route::post('/admin-reset-password-link', [AdminController::class, 'adminResetPasswordLink']);
Route::get('/change_password/{id}', [AdminController::class, 'change_password']);
Route::post('/admin-reset-password', [AdminController::class, 'ResetPassword']);

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'getdashboard']);
    Route::get('profile', [AdminController::class, 'getProfile']);
    Route::post('update-profile', [AdminController::class, 'update_profile']);
    Route::get('logout', [AdminController::class, 'logout']);
    /**driver */
    Route::get('driver/status/{id}', [DriverController::class, 'status'])->name('driver.status');
    /**owner */
    Route::get('owner/status/{id}', [BusinessOwnerController::class, 'status'])->name('owner.status');

    /** resource controller */
    Route::resource('officer', OfficerController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('about', AboutusController::class);
    Route::resource('policy', PolicyController::class);
    Route::resource('terms', TermConditionController::class);
    Route::resource('faq', FaqController::class);

    Route::resource('vehicle', VehicleController::class);
    Route::resource('businessOwner', BusinessOwnerController::class);
    Route::resource('driver', DriverController::class);
    // Route::resource('document', DocumentController::class);
    Route::get('document/{id}', [DocumentController::class, 'index'])->name('document.index');
    Route::get('document-create/{id}', [DocumentController::class, 'create'])->name('document.create');
    Route::post('document-store/{id}', [DocumentController::class, 'store'])->name('document.store');
    Route::get('document-edit/{id}', [DocumentController::class, 'edit'])->name('document.edit');
    Route::post('document-update/{id}', [DocumentController::class, 'update'])->name('document.update');
    Route::delete('document-destroy/{id}', [DocumentController::class, 'destroy'])->name('document.destroy');

});
