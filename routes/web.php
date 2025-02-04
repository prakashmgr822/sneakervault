<?php

use App\Helpers\GuardHelper;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('home');

Route::get('/redirect', function () {
    if (GuardHelper::check() === "vendor") {
        return redirect()->route('vendor.home');
    } else {
        return redirect()->route('admin.home');
    }
})->name('redirect');

Auth::routes();

Route::post('/login/user', [LoginController::class, 'userLogin'])->name('user.login-redirect');
Route::post('/login/vendor', [LoginController::class, 'vendorLogin'])->name('vendor.login-redirect');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('home', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.home');
});

Route::group(['prefix' => 'vendor', 'middleware' => 'auth:vendor'], function () {
    Route::get('home', [App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('vendor.home');
    Route::resource('products', \App\Http\Controllers\Vendor\ProductController::class);
});

Route::resource('uploader', \App\Http\Controllers\UploadController::class);


