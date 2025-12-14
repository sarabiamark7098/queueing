<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\QueueSequence;
use App\Models\Window;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index()
    {
        $statistics = Queue::getOverallStatistics();
        $recentQueues = Queue::getRecentQueues(10);

        // Get waiting counts per window with custom prefixes
        $windowStats = [];
        $windowPrefixes = [];
        $prefixesInUse = QueueSequence::getAllPrefixesInUse();

        for ($i = 1; $i <= 4; $i++) {
            $window = Window::where('window_number', $i)->first();
            $windowStats[$i] = Queue::getWindowStatistics($i);
            $windowPrefixes[$i] = [
                'prefix' => $window->getQueuePrefix(),
                'custom_prefix' => $window->custom_prefix,
                'use_custom' => $window->use_custom_prefix
            ];
        }

        return view('queue.index', compact('statistics', 'recentQueues', 'windowStats', 'windowPrefixes', 'prefixesInUse'));
    }

    public function generateManual(Request $request)
    {
        $request->validate([
            'window_number' => 'required|integer|between:1,4',
            'queue_number' => 'required|string|max:50',
            'mode' => 'required|in:prefix,special'
        ]);

        $result = Queue::createManualQueue(
            $request->window_number,
            $request->queue_number,
            $request->mode
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json([
            'success' => true,
            'queue' => $result['queue'],
            'message' => $result['message'],
            'prefix' => $result['prefix'] ?? null
        ]);
    }

    public function checkQueueNumber(Request $request)
    {
        $queueNumber = strtoupper(trim($request->input('queue_number')));

        $exists = Queue::where('queue_number', $queueNumber)->exists();

        return response()->json([
            'available' => !$exists,
            'queue_number' => $queueNumber
        ]);
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

        $result = $window->updateCustomPrefix($request->custom_prefix);

        if (!$result['success']) {
            return response()->json([
                'error' => $result['error'],
                'conflict_window' => $result['conflict_window'] ?? null
            ], 400);
        }

        return response()->json([
            'success' => true,
            'prefix' => $window->getQueuePrefix(),
            'use_custom' => $window->use_custom_prefix,
            'message' => $result['message']
        ]);
    }

    public function checkPrefix(Request $request, $windowNumber)
    {
        $prefix = $request->input('prefix');

        if (!$prefix) {
            return response()->json(['available' => true]);
        }

        $isUsed = QueueSequence::isPrefixInUse($prefix, $windowNumber);

        return response()->json([
            'available' => !$isUsed,
            'prefix' => $prefix
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
