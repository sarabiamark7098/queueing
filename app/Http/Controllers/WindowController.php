<?php

namespace App\Http\Controllers;

use App\Models\Window;
use App\Models\Queue;
use Illuminate\Http\Request;

class WindowController extends Controller
{
    /**
     * Display window page
     */
    public function show($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)
                       ->with('currentQueue')
                       ->firstOrFail();

        $waitingQueues = Queue::getWaiting($window->id);

        return view('window.show', compact('window', 'waitingQueues'));
    }

    /**
     * Call next queue in line
     */
    public function callNext($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)->firstOrFail();

        if (!$window->isAvailable()) {
            return response()->json([
                'error' => 'Window is currently busy'
            ], 400);
        }

        $queue = $window->callNext();

        if (!$queue) {
            return response()->json([
                'error' => 'No waiting queues available'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

    /**
     * Call specific queue number
     */
    public function callSpecific(Request $request, $windowNumber)
    {
        $request->validate([
            'queue_id' => 'required|exists:queues,id'
        ]);

        $window = Window::where('window_number', $windowNumber)->firstOrFail();

        if (!$window->isAvailable()) {
            return response()->json([
                'error' => 'Window is currently busy'
            ], 400);
        }

        $queue = Queue::findOrFail($request->queue_id);

        if ($queue->status !== 'waiting') {
            return response()->json([
                'error' => 'Queue is not available'
            ], 400);
        }

        $window->serveQueue($queue);

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

    /**
     * Complete current service
     */
    public function complete($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)->firstOrFail();

        $currentQueue = $window->currentQueue()->first();

        if (!$currentQueue) {
            return response()->json([
                'error' => 'No queue to complete'
            ], 400);
        }

        $window->completeService();

        return response()->json(['success' => true]);
    }

    public function currentWindows()
{
    $windows = Window::with('currentQueue')->get();

    // Map the data if needed
    $windowsData = $windows->map(function($window) {
        return [
            'window_number' => $window->window_number,
            'status' => $window->status,
            'currentQueue' => $window->currentQueue ? [
                'id' => $window->currentQueue->id,
                'queue_number' => $window->currentQueue->queue_number,
            ] : null
        ];
    });

    return response()->json($windowsData);
}
}
