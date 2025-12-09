<?php

namespace App\Http\Controllers;

use App\Models\Window;
use App\Models\Queue;
use Carbon\Carbon;

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

        $count = Queue::where('status', 'serving')
            ->where('updated_at', '>=', Carbon::now()->subSeconds(3))
            ->count();
        $ringBell = $count > 0 ? true : false;

        return view('display.index', compact('windows', 'waitingQueues', 'statistics', 'ringBell'));
    }
}
