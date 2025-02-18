<?php

use App\Helpers\GuardHelper;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Front\FrontController;
use Darryldecode\Cart\Cart;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('product', [FrontController::class, 'product'])->name('product.home');
Route::get('about', [FrontController::class, 'about'])->name('about');
Route::get('/search-products', [FrontController::class, 'searchProducts'])->name('search.products');

Route::get('product-details/{id}', [FrontController::class, 'productDetails'])->name('product-details');
Route::get('cart', [FrontController::class, 'cart'])->name('cart');
Route::get('/cart/count', [FrontController::class, 'getCartCount'])->name('cart.count');
Route::post('/cart/add/{productId}', [FrontController::class, 'addToCart'])->name('addToCart');
Route::get('/cart/clear', [FrontController::class, 'clearCart'])->name('clear.cart');
Route::get('/cart/remove/{id}', [FrontController::class, 'removeItem'])->name('remove.item');
Route::get('/cart/increase/{id}', [FrontController::class, 'increaseQuantity'])->name('add.quantity');
Route::get('/cart/decrease/{id}', [FrontController::class, 'decreaseQuantity'])->name('decrease.quantity');
Route::get('/payment', [FrontController::class, 'payment'])->name('payment');

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
    Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class);
//    Route::resource('admin/products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('admin/products/index', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
    Route::get('admin/products/show/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.products.show');
    Route::delete('admin/products/destroy/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/change-password', [\App\Http\Controllers\Admin\DashboardController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/change-password/save', [\App\Http\Controllers\Admin\DashboardController::class, 'changePasswordSave'])->name('admin.password.store');
});

Route::group(['prefix' => 'vendor', 'middleware' => 'auth:vendor'], function () {
    Route::get('home', [App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('vendor.home');
    Route::resource('products', \App\Http\Controllers\Vendor\ProductController::class);
    Route::get('/change-password', [\App\Http\Controllers\Vendor\DashboardController::class, 'changePassword'])->name('vendor.change-password');
    Route::post('/change-password/save', [\App\Http\Controllers\Vendor\DashboardController::class, 'changePasswordSave'])->name('vendor.password.store');

});

Route::resource('uploader', \App\Http\Controllers\UploadController::class);


