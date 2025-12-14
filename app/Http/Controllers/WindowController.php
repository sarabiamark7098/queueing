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
        $waitingSubstep2 = Queue::getWaitingForSubstep2($windowNumber);
        $waitingSubstep3 = Queue::getWaitingForSubstep3($windowNumber);

        return view('window.control', compact('window', 'waitingQueues', 'waitingSubstep2', 'waitingSubstep3'));
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

    public function callNextToSubstep2($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $queue = $window->callNextToSubstep2();

        if (!$queue) {
            return response()->json(['error' => 'No waiting queues or substep 2 is occupied'], 400);
        }

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

    public function callSpecificToSubstep2(Request $request, $windowNumber)
    {
        $request->validate([
            'queue_id' => 'required|exists:queues,id'
        ]);

        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $queue = $window->callSpecificToSubstep2($request->queue_id);

        if (!$queue) {
            return response()->json(['error' => 'Cannot call this queue'], 400);
        }

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

   public function callNextToSubstep3($windowNumber)
    {
        $window = Window::where('window_number', $windowNumber)->first();

        if (!$window) {
            return response()->json(['error' => 'Window not found'], 404);
        }

        $queue = $window->callNextToSubstep3();

        if (!$queue) {
            return response()->json(['error' => 'No waiting queues or substep 3 is occupied'], 400);
        }

        return response()->json([
            'success' => true,
            'queue' => $queue
        ]);
    }

    public function getData($windowNumber)
    {
        $window = Window::getWithSubsteps($windowNumber);
        $waitingQueues = Queue::getWaitingForWindow($windowNumber);
        $waitingSubstep2 = Queue::getWaitingForSubstep2($windowNumber);
        $waitingSubstep3 = Queue::getWaitingForSubstep3($windowNumber);

        return response()->json([
            'window' => $window,
            'waiting_queues' => $waitingQueues,
            'waiting_substep2' => $waitingSubstep2,
            'waiting_substep3' => $waitingSubstep3
        ]);
    }
}
