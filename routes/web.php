<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\WindowController;
use App\Http\Controllers\DisplayController;

Route::get('/', function () {
    return redirect()->route('queue.index');
});

Route::get('/queue', [QueueController::class, 'index'])->name('queue.index');
Route::post('/queue/generate', [QueueController::class, 'generate'])->name('queue.generate');

Route::get('/window/{windowNumber}/control', [WindowController::class, 'show'])->name('window.control');
Route::post('/window/{windowNumber}/call-next', [WindowController::class, 'callNext'])->name('window.callNext');
Route::post('/window/{windowNumber}/call-specific', [WindowController::class, 'callSpecific'])->name('window.callSpecific');
Route::post('/window/{windowNumber}/move-to-substep2', [WindowController::class, 'moveToSubstep2'])->name('window.moveToSubstep2');
Route::post('/window/{windowNumber}/call-next-substep2', [WindowController::class, 'callNextToSubstep2'])->name('window.callNextToSubstep2');
Route::post('/window/{windowNumber}/call-specific-substep2', [WindowController::class, 'callSpecificToSubstep2'])->name('window.callSpecificToSubstep2');
Route::post('/window/{windowNumber}/move-to-substep3', [WindowController::class, 'moveToSubstep3'])->name('window.moveToSubstep3');
Route::post('/window/{windowNumber}/call-next-substep3', [WindowController::class, 'callNextToSubstep3'])->name('window.callNextToSubstep3');
Route::post('/window/{windowNumber}/complete', [WindowController::class, 'completeSubstep3'])->name('window.complete');

Route::get('/window/{windowNumber}/display', [WindowController::class, 'display'])->name('window.display');

Route::get('/display', [DisplayController::class, 'index'])->name('display.main');

Route::prefix('api')->group(function () {
    Route::get('/queue/statistics', [QueueController::class, 'getStatistics']);
    Route::get('/queue/recent', [QueueController::class, 'getRecentQueues']);
    Route::get('/queue/waiting/{windowNumber}', [QueueController::class, 'getWaitingQueues']);
    Route::get('/queue/window-stats/{windowNumber}', [QueueController::class, 'getWindowStatistics']);
    Route::get('/window/{windowNumber}', [WindowController::class, 'getData']);
    Route::get('/display/data', [DisplayController::class, 'getData']);
    Route::get('/system/all-data', [DisplayController::class, 'getAllData']);
});
