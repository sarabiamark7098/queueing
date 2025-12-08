<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\WindowController;
use App\Http\Controllers\DisplayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to queue generation
Route::get('/', function () {
    return redirect('/queue');
});

// Queue Generation
Route::get('/queue', [QueueController::class, 'index'])->name('queue.index');
Route::post('/queue/generate', [QueueController::class, 'generate'])->name('queue.generate');

// Main Display Board
Route::get('/display', [DisplayController::class, 'index'])->name('display.main');

// Window Control Pages
Route::get('/window/{number}/control', [WindowController::class, 'show'])->name('window.control');

// Window Display Pages (for customers)
Route::get('/window/{number}/display', [WindowController::class, 'display'])->name('window.display');

// Window Actions
Route::post('/window/{number}/call-next', [WindowController::class, 'callNext'])->name('window.callNext');
Route::post('/window/{number}/call-specific', [WindowController::class, 'callSpecific'])->name('window.callSpecific');
Route::post('/window/{number}/move-to-substep2', [WindowController::class, 'moveToSubstep2'])->name('window.moveToSubstep2');
Route::post('/window/{number}/move-to-substep3', [WindowController::class, 'moveToSubstep3'])->name('window.moveToSubstep3');
Route::post('/window/{number}/complete-substep3', [WindowController::class, 'completeSubstep3'])->name('window.completeSubstep3');

// API Routes for AJAX
Route::prefix('api')->group(function () {
    Route::get('/queues/statistics', [QueueController::class, 'getStatistics']);
    Route::get('/queues/recent', [QueueController::class, 'getRecentQueues']);
    Route::get('/queues/window/{number}/waiting', [QueueController::class, 'getWaitingQueues']);
    Route::get('/queues/window/{number}/statistics', [QueueController::class, 'getWindowStatistics']);
    Route::get('/window/{number}/data', [WindowController::class, 'getData']);
    Route::get('/display/data', [DisplayController::class, 'getData']);
});
