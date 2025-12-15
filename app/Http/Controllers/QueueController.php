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
        for ($i = 1; $i <= 4; $i++) {
            $windowStats[$i] = Queue::getWindowStatistics($i);
        }

        return view('queue.index', compact('statistics', 'recentQueues', 'windowStats'));
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
            'status' => 'waiting',
            'is_manual' => false
        ]);

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

    public function generateManual(Request $request)
    {
        $request->validate([
            'window_number' => 'required|integer|between:1,4',
            'queue_number' => 'required|string|max:50'
        ]);

        $result = Queue::createManualQueue(
            $request->window_number,
            $request->queue_number
        );

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json([
            'success' => true,
            'queue' => $result['queue'],
            'message' => $result['message']
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
