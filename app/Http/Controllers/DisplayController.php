<?php

namespace App\Http\Controllers;

use App\Models\Window;
use App\Models\Queue;

class DisplayController extends Controller
{
    /**
     * Display board showing all windows
     */
    public function index()
    {
        $windows = Window::with('currentQueue')
                        ->orderBy('window_number')
                        ->get();

        $waitingQueues = Queue::where('status', 'waiting')
                             ->orderBy('created_at', 'asc')
                             ->take(10)
                             ->get();

        $statistics = Queue::getStatistics();

        return view('display.index', compact('windows', 'waitingQueues', 'statistics'));
    }
}
