<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTrackController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('track', [OrderTrackController::class, 'index'])->name('track');
Route::get('track/search', [OrderTrackController::class, 'show'])->name('track.search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show', 'update']);
});

require __DIR__.'/settings.php';
