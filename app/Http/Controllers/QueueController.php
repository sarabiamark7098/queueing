<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    /**
     * Display queue generation page
     */
    public function index()
    {
        $queues = Queue::orderBy('created_at', 'desc')->take(10)->get();
        $statistics = Queue::getStatistics();

        return view('queue.index', compact('queues', 'statistics'));
    }

    /**
     * Generate new queue number
     */
    public function store(Request $request)
    {
        $request->validate([
            'window_id' => 'required|exists:windows,id',
        ]);

         $queue = Queue::create([
            'queue_number' => Queue::generateQueueNumber($request->window_id),
            'status' => 'waiting',
            'window_id' => $request->window_id
        ]);

        return redirect()
            ->back()
            ->with('success', 'Queue generated: ' . $queue->queue_number);
    }

    /**
     * Get waiting queues (API endpoint)
     */
    public function getWaitingQueues(Request $request)
    {
        $request->validate([
            'window_id' => 'required|exists:windows,id',
        ]);

        $waiting = Queue::getWaiting($request->window_id);

        return response()->json($waiting);
    }

    public function getStatistics()
    {
        return response()->json(Queue::getStatistics());
    }
}
