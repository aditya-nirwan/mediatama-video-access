<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VideoStreamController;
use App\Http\Controllers\WatchController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', HomeController::class)->name('home');

    Route::get('videos/{video}/watch', WatchController::class)->name('videos.watch');
    Route::get('videos/{video}/stream', VideoStreamController::class)->name('videos.stream');

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function (): void {
            Route::get('dashboard', AdminDashboard::class)->name('dashboard');
            Route::resource('customers', CustomerController::class)->except('show');
            Route::resource('videos', VideoController::class)->except('show');
        });

    Route::middleware('role:customer')
        ->prefix('customer')
        ->name('customer.')
        ->group(function (): void {
            Route::get('dashboard', CustomerDashboard::class)->name('dashboard');
        });
        
});
