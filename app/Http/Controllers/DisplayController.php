<?php

namespace App\Http\Controllers;

use App\Models\Window;
use App\Models\Queue;

class DisplayController extends Controller
{
    public function index()
    {
        $windows = Window::with(['substep1Queue', 'substep2Queue', 'substep3Queue'])
                        ->orderBy('window_number')
                        ->get();

        $statistics = Queue::getOverallStatistics();

        return view('display.main', compact('windows', 'statistics'));
    }

    public function getData()
    {
        $windows = Window::with(['substep1Queue', 'substep2Queue', 'substep3Queue'])
                        ->orderBy('window_number')
                        ->get();

        $statistics = Queue::getOverallStatistics();

        return response()->json([
            'windows' => $windows,
            'statistics' => $statistics
        ]);
    }

    public function getAllData()
{
    $windows = Window::with(['substep1Queue', 'substep2Queue', 'substep3Queue'])
                    ->orderBy('window_number')
                    ->get();

    $statistics = Queue::getOverallStatistics();
    $recentQueues = Queue::getRecentQueues(10);

    // Get window-specific stats
    $windowStats = [];
    for ($i = 1; $i <= 4; $i++) {
        $windowStats[$i] = Queue::getWindowStatistics($i);
    }

    return response()->json([
        'windows' => $windows,
        'statistics' => $statistics,
        'recent_queues' => $recentQueues,
        'window_stats' => $windowStats,
        'timestamp' => now()->timestamp
    ]);
}
}
