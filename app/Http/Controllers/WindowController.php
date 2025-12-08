<?php

namespace App\Http\Controllers;

use App\Models\Window;
use App\Models\Queue;
use Illuminate\Http\Request;

class WindowController extends Controller
{
    public function show($windowNumber)
    {
        $window = Window::getWithSubsteps($windowNumber);

        if (!$window) {
            abort(404, 'Window not found');
        }

        $waitingQueues = Queue::getWaitingForWindow($windowNumber);

        return view('window.control', compact('window', 'waitingQueues'));
    }

    public function display($windowNumber)
    {
        $window = Window::getWithSubsteps($windowNumber);

        if (!$window) {
            abort(404, 'Window not found');
        }

        return view('window.display', compact('window'));
    }

    public function callNext($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $queue = $window->callNextToSubstep1();

        if (!$queue) {
            return response()->json(['error' => 'No waiting queues or substep 1 is occupied'], 400);
        }

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

    public function callSpecific(Request $request, $windowNumber)
    {
        $request->validate([
            'queue_id' => 'required|exists:queues,id'
        ]);

        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $queue = $window->callSpecificToSubstep1($request->queue_id);

        if (!$queue) {
            return response()->json(['error' => 'Cannot call this queue'], 400);
        }

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

    public function moveToSubstep2($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $success = $window->moveToSubstep2();

        if (!$success) {
            return response()->json(['error' => 'Cannot move to substep 2'], 400);
        }

        return response()->json(['success' => true]);
    }

    public function moveToSubstep3($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $success = $window->moveToSubstep3();

        if (!$success) {
            return response()->json(['error' => 'Cannot move to substep 3'], 400);
        }

        return response()->json(['success' => true]);
    }

    public function completeSubstep3($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $success = $window->completeSubstep3();

        if (!$success) {
            return response()->json(['error' => 'No queue in substep 3'], 400);
        }

        return response()->json(['success' => true]);
    }

    public function getData($windowNumber)
    {
        $window = Window::getWithSubsteps($windowNumber);
        $waitingQueues = Queue::getWaitingForWindow($windowNumber);

        return response()->json([
            'window' => $window,
            'waiting_queues' => $waitingQueues
        ]);
    }
}
