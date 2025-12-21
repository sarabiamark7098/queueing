<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Window;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    /**
     * Display queue generation page
     */
    public function index()
    {
        $statistics = Queue::getOverallStatistics();
        $recentQueues = Queue::getRecentQueues(10);

        $windowStats = [];
        $windowConfigs = [];

        for ($i = 1; $i <= 4; $i++) {
            $window = Window::where('window_number', $i)->first();
            $windowStats[$i] = Queue::getWindowStatistics($i);
            $windowConfigs[$i] = [
                'prefix' => $window->prefix,
                'format' => $window->getQueueFormat(),
                'last_reset' => $window->last_reset_date,
                'current_sequence' => $window->last_queue_number
            ];
        }

        return view('queue.index', compact('statistics', 'recentQueues', 'windowStats', 'windowConfigs'));
    }

    /**
     * Generate automatic queue number
     */
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

    /**
     * Get waiting queues for a window (API)
     */
    public function getWaitingQueues($windowNumber)
    {
        $queues = Queue::getWaitingForWindow($windowNumber);
        return response()->json($queues);
    }

    /**
     * Get statistics (API)
     */
    public function getStatistics()
    {
        return response()->json(Queue::getOverallStatistics());
    }

    /**
     * Get recent queues (API)
     */
    public function getRecentQueues()
    {
        return response()->json(Queue::getRecentQueues(10));
    }

    /**
     * Get window statistics (API)
     */
    public function getWindowStatistics($windowNumber)
    {
        return response()->json(Queue::getWindowStatistics($windowNumber));
    }
}
