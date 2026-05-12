<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ZohoController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/zoho/connect', [ZohoController::class, 'connect'])->name('zoho.connect');
    Route::get('/zoho/callback', [ZohoController::class, 'callback'])->name('zoho.callback');
});

require __DIR__.'/settings.php';
