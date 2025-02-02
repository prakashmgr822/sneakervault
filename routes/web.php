<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('admin/home', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.index');

//Route::get('home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');


