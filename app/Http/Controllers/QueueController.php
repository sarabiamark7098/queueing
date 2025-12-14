<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Window;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index()
    {
        $statistics = Queue::getOverallStatistics();
        $recentQueues = Queue::getRecentQueues(10);

        $windowStats = [];
        $windowPrefixes = [];
        for ($i = 1; $i <= 4; $i++) {
            $window = Window::where('window_number', $i)->first();
            $windowStats[$i] = Queue::getWindowStatistics($i);
            $windowPrefixes[$i] = [
                'prefix' => $window->getQueuePrefix(),
                'custom_prefix' => $window->custom_prefix,
                'use_custom' => $window->use_custom_prefix
            ];
        }

        return view('queue.index', compact('statistics', 'recentQueues', 'windowStats', 'windowPrefixes'));
    }

    public function updatePrefix(Request $request, $windowNumber)
    {
        $request->validate([
            'custom_prefix' => 'nullable|string|max:50|regex:/^[A-Za-z0-9\-]+$/'
        ]);

        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $window->updateCustomPrefix($request->custom_prefix);

        return response()->json([
            'success' => true,
            'prefix' => $window->getQueuePrefix(),
            'use_custom' => $window->use_custom_prefix
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'window_number' => 'required|integer|between:1,4'
        ]);

        $queueNumber = Queue::generateQueueNumber($request->window_number);

        $queue = Queue::create([
            'queue_number' => $queueNumber,
            'window_number' => $request->window_number,
            'status' => 'waiting'
        ]);

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

    public function getWaitingQueues($windowNumber)
    {
        $queues = Queue::getWaitingForWindow($windowNumber);
        return response()->json($queues);
    }

    public function getStatistics()
    {
        return response()->json(Queue::getOverallStatistics());
    }

    public function getRecentQueues()
    {
        return response()->json(Queue::getRecentQueues(10));
    }

    public function getWindowStatistics($windowNumber)
    {
        return response()->json(Queue::getWindowStatistics($windowNumber));
    }
}
