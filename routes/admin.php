<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

// user (members)
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->name('users.index');
    Route::post('/users', 'store')->name('users.store');
    Route::put('/users/{user}', 'update')->name('users.update');
    Route::delete('/users/{user}', 'destroy')->name('users.destroy');
});

// package
Route::controller(PackageController::class)->group(function () {
    Route::get('/packages', 'index')->name('packages.index');
    Route::post('/packages', 'store')->name('packages.store');
    Route::put('/packages/{package}', 'update')->name('packages.update');
    Route::delete('/packages/{package}', 'destroy')->name('packages.destroy');
});