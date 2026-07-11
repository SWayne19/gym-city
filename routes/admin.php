<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// admin dashboard
Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

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

// membership
Route::controller(MembershipController::class)->group(function () {
    Route::get('/memberships', 'index')->name('memberships.index');
    Route::post('/memberships', 'store')->name('memberships.store');
    Route::put('/memberships/{membership}', 'update')->name('memberships.update');
    Route::delete('/memberships/{membership}', 'destroy')->name('memberships.destroy');
});

// payment
Route::controller(PaymentController::class)->group(function () {
    Route::get('/payments', 'index')->name('payments.index');
    Route::post('/payments', 'store')->name('payments.store');
    Route::delete('/payments/{payment}', 'destroy')->name('payments.destroy');
});
