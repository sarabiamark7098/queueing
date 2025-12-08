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
}
