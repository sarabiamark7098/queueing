@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-gray-800 mb-2">Window {{ $window->window_number }} Control</h1>
            </div>

            <!-- Substeps Display -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <!-- Substep 1 -->
                <div class="rounded-xl p-6 border-4 border-blue-200
                        {{ $window->window_number == 1 ? 'bg-blue-50' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-50' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-50' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-50' : '' }}">
                    <h3 class="text-center font-bold text-blue-800 mb-4">STEP 1</h3>
                    <div id="substep1-content">
                        @if($window->substep1Queue)
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-4
                        {{ $window->window_number == 1 ? 'text-blue-600' : '' }}
                        {{ $window->window_number == 2 ? 'text-red-600' : '' }}
                        {{ $window->window_number == 3 ? 'text-yellow-600' : '' }}
                        {{ $window->window_number == 4 ? 'text-orange-600' : '' }}">{{ $window->substep1Queue->queue_number }}</div>
                            <button onclick="moveToSubstep2()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Send to Step 2 Queue
                            </button>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-8">Empty</div>
                        @endif
                    </div>
                </div>

                <!-- Substep 2 -->
                <div class="rounded-xl p-6 border-4 border-purple-200
                        {{ $window->window_number == 1 ? 'bg-blue-50' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-50' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-50' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-50' : '' }}">
                    <h3 class="text-center font-bold text-purple-800 mb-4">STEP 2</h3>
                    <div id="substep2-content">
                        @if($window->substep2Queue)
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-4
                            {{ $window->window_number == 1 ? 'text-blue-600' : '' }}
                            {{ $window->window_number == 2 ? 'text-red-600' : '' }}
                            {{ $window->window_number == 3 ? 'text-yellow-600' : '' }}
                            {{ $window->window_number == 4 ? 'text-orange-600' : '' }}">{{ $window->substep2Queue->queue_number }}</div>
                            <button onclick="moveToSubstep3()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg">
                                Send to Step 3 Queue
                            </button>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-8">Empty</div>
                        @endif
                    </div>
                </div>

                <!-- Substep 3 -->
                <div class="rounded-xl p-6 border-4 border-green-200
                        {{ $window->window_number == 1 ? 'bg-blue-50' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-50' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-50' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-50' : '' }}">
                    <h3 class="text-center font-bold text-green-800 mb-4">STEP 3</h3>
                    <div id="substep3-content">
                        @if($window->substep3Queue)
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-4
                            {{ $window->window_number == 1 ? 'text-blue-600' : '' }}
                            {{ $window->window_number == 2 ? 'text-red-600' : '' }}
                            {{ $window->window_number == 3 ? 'text-yellow-600' : '' }}
                            {{ $window->window_number == 4 ? 'text-orange-600' : '' }}">{{ $window->substep3Queue->queue_number }}</div>
                            <button onclick="completeSubstep3()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                                Complete
                            </button>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-8">Empty</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Step Actions -->
            <div class="grid grid-cols-3 space-x-6">
                <!-- Step 1 Actions -->
                <div class="p-4 rounded-xl
                        {{ $window->window_number == 1 ? 'bg-blue-50 border-2 border-blue-200 ' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-50 border-2 border-red-200 ' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-50 border-2 border-yellow-200 ' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-50 border-2 border-orange-200 ' : '' }}">
                    <h3 class="font-bold text-blue-800 mb-3">Step 1 Actions</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <button onclick="callNext()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                            Call Next to Step 1
                        </button>
                        <button onclick="openSelectModal(1)" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg">
                            Select Queue for Step 1
                        </button>
                    </div>
                    <div class="text-sm font-semibold text-gray-600 mb-2">
                        Waiting for Step 1: <span id="waiting-count-1">{{ $waitingQueues->count() }}</span>
                    </div>
                    <div id="waiting-list-1" class="space-y-2">
                        @foreach($waitingQueues->take(3) as $queue)
                        <div class="p-3 border-2 border-blue-200 rounded-lg flex justify-between items-center
                        {{ $window->window_number == 1 ? 'bg-blue-100 border-blue-200' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-100 border-red-200' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-100 border-yellow-200' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-100 border-orange-200' : '' }}">
                            <div class="font-bold
                        {{ $window->window_number == 1 ? 'text-blue-600' : '' }}
                        {{ $window->window_number == 2 ? 'text-red-600' : '' }}
                        {{ $window->window_number == 3 ? 'text-yellow-600' : '' }}
                        {{ $window->window_number == 4 ? 'text-orange-600' : '' }}">{{ $queue->queue_number }}</div>
                            <div class="text-sm text-gray-500">{{ $queue->created_at->format('h:i A') }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step 2 Actions -->
                <div class="p-4 rounded-xl
                        {{ $window->window_number == 1 ? 'bg-blue-50 border-2 border-blue-200 ' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-50 border-2 border-red-200 ' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-50 border-2 border-yellow-200 ' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-50 border-2 border-orange-200 ' : '' }}">
                    <h3 class="font-bold text-purple-800 mb-3">Step 2 Actions</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <button onclick="callNextToSubstep2()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg">
                            Call Next to Step 2
                        </button>
                        <button onclick="openSelectModal(2)" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg">
                            Select Queue for Step 2
                        </button>
                    </div>
                    <div class="text-sm font-semibold text-gray-600 mb-2">
                        Waiting for Step 2: <span id="waiting-count-2">{{ $waitingSubstep2->count() }}</span>
                    </div>
                    <div id="waiting-list-2" class="space-y-2">
                        @foreach($waitingSubstep2->take(3) as $queue)
                        <div class="p-3 border-2 rounded-lg flex justify-between items-center
                        {{ $window->window_number == 1 ? 'bg-blue-100 border-blue-200' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-100 border-red-200' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-100 border-yellow-200' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-100 border-orange-200' : '' }}">
                            <div class="font-bold
                        {{ $window->window_number == 1 ? 'text-blue-600' : '' }}
                        {{ $window->window_number == 2 ? 'text-red-600' : '' }}
                        {{ $window->window_number == 3 ? 'text-yellow-600' : '' }}
                        {{ $window->window_number == 4 ? 'text-orange-600' : '' }}">{{ $queue->queue_number }}</div>
                            <div class="text-sm text-gray-500">{{ $queue->created_at->format('h:i A') }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step 3 Actions -->
                <div class="p-4 rounded-xl
                        {{ $window->window_number == 1 ? 'bg-blue-50 border-2 border-blue-200 ' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-50 border-2 border-red-200 ' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-50 border-2 border-yellow-200 ' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-50 border-2 border-orange-200 ' : '' }}">
                    <h3 class="font-bold text-green-800 mb-3">Step 3 Actions</h3>
                    <button onclick="callNextToSubstep3()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                        Call Next to Step 3
                    </button>
                    <div class="mt-4 text-sm font-semibold text-gray-600">
                        Waiting for Step 3: <span id="waiting-count-3">{{ $waitingSubstep3->count() }}</span>
                    </div>
                    <div id="waiting-list-3" class="space-y-2">
                        @foreach($waitingSubstep3->take(3) as $queue)
                        <div class="p-3 border-2 rounded-lg flex justify-between items-center
                        {{ $window->window_number == 1 ? 'bg-blue-100 border-blue-200' : '' }}
                        {{ $window->window_number == 2 ? 'bg-red-100 border-red-200' : '' }}
                        {{ $window->window_number == 3 ? 'bg-yellow-100 border-yellow-200' : '' }}
                        {{ $window->window_number == 4 ? 'bg-orange-100 border-orange-200' : '' }}">
                            <div class="font-bold
                        {{ $window->window_number == 1 ? 'text-blue-600' : '' }}
                        {{ $window->window_number == 2 ? 'text-red-600' : '' }}
                        {{ $window->window_number == 3 ? 'text-yellow-600' : '' }}
                        {{ $window->window_number == 4 ? 'text-orange-600' : '' }}">{{ $queue->queue_number }}</div>
                            <div class="text-sm text-gray-500">{{ $queue->created_at->format('h:i A') }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Select Queue Modal -->
    <div id="selectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="bg-purple-600 text-white p-6">
                <h2 class="text-3xl font-bold">Select Queue Number</h2>
            </div>
            <div id="modal-queue-list" class="p-6 max-h-[60vh] overflow-y-auto"></div>
            <div class="bg-gray-50 p-4 border-t">
                <button onclick="closeSelectModal()" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const windowNumber = {{ $window->window_number }};

function callNext() {
    $.post(`/window/${windowNumber}/call-next`)
        .done(() => { showToast('Queue called', 'success'); setTimeout(() => location.reload(), 500); })
        .fail(xhr => showToast(xhr.responseJSON?.error || 'Error', 'error'));
}

function moveToSubstep2() {
    $.post(`/window/${windowNumber}/move-to-substep2`)
        .done(() => { showToast('Moved to Step 2 queue', 'success'); setTimeout(() => location.reload(), 500); })
        .fail(xhr => showToast(xhr.responseJSON?.error || 'Error', 'error'));
}

function callNextToSubstep2() {
    $.post(`/window/${windowNumber}/call-next-substep2`)
        .done(() => { showToast('Queue called to Step 2', 'success'); setTimeout(() => location.reload(), 500); })
        .fail(xhr => showToast(xhr.responseJSON?.error || 'Error', 'error'));
}

function moveToSubstep3() {
    $.post(`/window/${windowNumber}/move-to-substep3`)
        .done(() => { showToast('Moved to Step 3 queue', 'success'); setTimeout(() => location.reload(), 500); })
        .fail(xhr => showToast(xhr.responseJSON?.error || 'Error', 'error'));
}

function callNextToSubstep3() {
    $.post(`/window/${windowNumber}/call-next-substep3`)
        .done(() => { showToast('Queue called to Step 3', 'success'); setTimeout(() => location.reload(), 500); })
        .fail(xhr => showToast(xhr.responseJSON?.error || 'Error', 'error'));
}

function completeSubstep3() {
    if (!confirm('Complete this service?')) return;
    $.post(`/window/${windowNumber}/complete`)
        .done(() => { showToast('Service completed!', 'success'); setTimeout(() => location.reload(), 500); })
        .fail(xhr => showToast(xhr.responseJSON?.error || 'Error', 'error'));
}

function openSelectModal(step) {
    $('#selectModal').removeClass('hidden');
    const endpoint = step === 1 ? `/api/queue/waiting/${windowNumber}` : `/api/window/${windowNumber}`;

    $.get(endpoint).done(data => {
        const queues = step === 1 ? data : data.waiting_substep2;
        if (!queues.length) {
            $('#modal-queue-list').html('<div class="text-center py-8 text-gray-400">No queues available</div>');
            return;
        }

        let html = '<div class="space-y-3">';
        queues.forEach((queue, i) => {
            html += `<button onclick="callSpecific(${queue.id}, ${step})"
                class="w-full p-4 bg-purple-50 hover:bg-purple-100 rounded-xl border-2 border-purple-200 flex items-center justify-between">
                <div><span class="font-bold text-xl text-purple-600">${queue.queue_number}</span></div>
                <div class="text-purple-600 font-semibold">Call →</div>
            </button>`;
        });
        $('#modal-queue-list').html(html + '</div>');
    });
}

function closeSelectModal() {
    $('#selectModal').addClass('hidden');
}

function callSpecific(queueId, step) {
    closeSelectModal();
    const endpoint = step === 1 ? 'call-specific' : 'call-specific-substep2';
    $.post(`/window/${windowNumber}/${endpoint}`, { queue_id: queueId })
        .done(() => { showToast('Queue called', 'success'); setTimeout(() => location.reload(), 500); })
        .fail(xhr => showToast(xhr.responseJSON?.error || 'Error', 'error'));
}

setInterval(() => location.reload(), 5000);
</script>
@endpush
