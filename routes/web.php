<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\WindowController;
use App\Http\Controllers\DisplayController;

// Redirect root to queue generation
Route::get('/', function () {
    return redirect('/queue');
});

// Queue Generation Routes
Route::get('/queue', [QueueController::class, 'index'])->name('queue.index');
Route::post('/queue', [QueueController::class, 'store'])->name('queue.store');

// Display Board Route
Route::get('/display', [DisplayController::class, 'index'])->name('display.index');

// Window Routes
Route::prefix('window/{number}')->group(function () {
    Route::get('/', [WindowController::class, 'show'])->name('window.show');
    Route::post('/call-next', [WindowController::class, 'callNext'])->name('window.callNext');
    Route::post('/call-specific', [WindowController::class, 'callSpecific'])->name('window.callSpecific');
    Route::post('/complete', [WindowController::class, 'complete'])->name('window.complete');
});

// API Routes for AJAX
Route::prefix('api')->group(function () {
    // Waiting queues per window (expects ?window_id=)
    Route::get('/queues/waiting', [QueueController::class, 'getWaitingQueues'])->name('api.queues.waiting');

    // Global statistics
    Route::get('/queues/statistics', [QueueController::class, 'getStatistics'])->name('api.queues.statistics');

    Route::get('/windows/current', [WindowController::class, 'currentWindows'])->name('api.windows.current');

});

