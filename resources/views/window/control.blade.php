@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold text-gray-800 mb-2">Window {{ $window->window_number }} Control</h1>
            </div>

            <!-- Substeps Display -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <!-- Substep 1 -->
                <div class="bg-blue-50 rounded-xl p-6 border-4 border-blue-200">
                    <h3 class="text-center font-bold text-blue-800 mb-4">STEP 1</h3>
                    <div id="substep1-content">
                        @if($window->substep1Queue)
                        <div class="text-center">
                            <div class="text-4xl font-bold text-blue-600 mb-4">{{ $window->substep1Queue->queue_number }}</div>
                            <button onclick="moveToSubstep2()"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center space-x-2">
                                <span>Move to Step 2</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-8">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Empty</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Substep 2 -->
                <div class="bg-purple-50 rounded-xl p-6 border-4 border-purple-200">
                    <h3 class="text-center font-bold text-purple-800 mb-4">STEP 2</h3>
                    <div id="substep2-content">
                        @if($window->substep2Queue)
                        <div class="text-center">
                            <div class="text-4xl font-bold text-purple-600 mb-4">{{ $window->substep2Queue->queue_number }}</div>
                            <button onclick="moveToSubstep3()"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center space-x-2">
                                <span>Move to Step 3</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-8">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Empty</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Substep 3 -->
                <div class="bg-green-50 rounded-xl p-6 border-4 border-green-200">
                    <h3 class="text-center font-bold text-green-800 mb-4">STEP 3</h3>
                    <div id="substep3-content">
                        @if($window->substep3Queue)
                        <div class="text-center">
                            <div class="text-4xl font-bold text-green-600 mb-4">{{ $window->substep3Queue->queue_number }}</div>
                            <button onclick="completeSubstep3()"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Complete</span>
                            </button>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-8">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Empty</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <button onclick="callNext()" id="btn-call-next"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Call Next</span>
                </button>

                <button onclick="openSelectModal()" id="btn-select-queue"
                        class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <span>Select Queue</span>
                </button>
            </div>

            <!-- Waiting Queue -->
            <div class="p-6 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-4">
                    Waiting Queue (<span id="waiting-count">{{ $waitingQueues->count() }}</span>)
                </h3>
                <div id="waiting-list" class="space-y-2 max-h-60 overflow-y-auto">
                    @forelse($waitingQueues->take(5) as $index => $queue)
                    <div class="p-3 bg-white rounded-lg flex items-center space-x-4 border">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center font-bold text-orange-600">
                            {{ $index + 1 }}
                        </div>
                        <div class="font-bold text-gray-800 text-lg">{{ $queue->queue_number }}</div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>No customers waiting</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Select Queue Modal -->
    <div id="selectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="bg-purple-600 text-white p-6">
                <h2 class="text-3xl font-bold">Select Queue Number</h2>
                <p class="text-purple-100 mt-2">Choose which customer to call to Step 1</p>
            </div>

            <div id="modal-queue-list" class="p-6 max-h-[60vh] overflow-y-auto">
                <!-- Queue list will be loaded here -->
            </div>

            <div class="bg-gray-50 p-4 border-t">
                <button onclick="closeSelectModal()"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
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
    $('#btn-call-next').prop('disabled', true);
    $.post(`/window/${windowNumber}/call-next`)
        .done(function() {
            showNotification('Queue called successfully', 'success');
            refreshWindowData();
        })
        .fail(function(xhr) {
            showNotification(xhr.responseJSON?.error || 'Error calling queue', 'error');
            $('#btn-call-next').prop('disabled', false);
        });
}

function moveToSubstep2() {
    $.post(`/window/${windowNumber}/move-to-substep2`)
        .done(function() {
            showNotification('Moved to Step 2', 'success');
            refreshWindowData();
        })
        .fail(function(xhr) {
            showNotification(xhr.responseJSON?.error || 'Error moving to step 2', 'error');
        });
}

function moveToSubstep3() {
    $.post(`/window/${windowNumber}/move-to-substep3`)
        .done(function() {
            showNotification('Moved to Step 3', 'success');
            refreshWindowData();
        })
        .fail(function(xhr) {
            showNotification(xhr.responseJSON?.error || 'Error moving to step 3', 'error');
        });
}

function completeSubstep3() {
    if (!confirm('Complete this service?')) return;

    $.post(`/window/${windowNumber}/complete-substep3`)
        .done(function() {
            showNotification('Service completed!', 'success');
            refreshWindowData();
        })
        .fail(function(xhr) {
            showNotification(xhr.responseJSON?.error || 'Error completing service', 'error');
        });
}

function openSelectModal() {
    $('#selectModal').removeClass('hidden');

    $.get(`/api/queues/window/${windowNumber}/waiting`)
        .done(function(queues) {
            if (queues.length === 0) {
                $('#modal-queue-list').html(`
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-xl">No waiting queues available</p>
                    </div>
                `);
                return;
            }

            let html = '<div class="space-y-3">';
            queues.forEach(function(queue, index) {
                const time = new Date(queue.created_at).toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                html += `
                    <button onclick="callSpecific(${queue.id})"
                            class="w-full p-4 bg-gradient-to-r from-purple-50 to-blue-50 hover:from-purple-100 hover:to-blue-100 rounded-xl border-2 border-purple-200 hover:border-purple-400 transition-all flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-purple-500 group-hover:bg-purple-600 rounded-full flex items-center justify-center font-bold text-white text-lg transition-colors">
                                ${index + 1}
                            </div>
                            <div class="text-left">
                                <div class="font-bold text-purple-600 text-xl">${queue.queue_number}</div>
                                <div class="text-xs text-gray-500">${time}</div>
                            </div>
                        </div>
                        <div class="text-purple-600 font-semibold group-hover:translate-x-1 transition-transform">
                            Call →
                        </div>
                    </button>
                `;
            });
            html += '</div>';

            $('#modal-queue-list').html(html);
        });
}

function closeSelectModal() {
    $('#selectModal').addClass('hidden');
}

function callSpecific(queueId) {
    closeSelectModal();
    showNotification('Calling queue...', 'info');

    $.post(`/window/${windowNumber}/call-specific`, { queue_id: queueId })
        .done(function() {
            showNotification('Queue called successfully', 'success');
            refreshWindowData();
        })
        .fail(function(xhr) {
            showNotification(xhr.responseJSON?.error || 'Error calling queue', 'error');
        });
}

function refreshWindowData() {
    $.get(`/api/window/${windowNumber}/data`)
        .done(function(data) {
            // Update substep 1
            if (data.window.substep1_queue) {
                $('#substep1-content').html(`
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-4">${data.window.substep1_queue.queue_number}</div>
                        <button onclick="moveToSubstep2()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center space-x-2">
                            <span>Move to Step 2</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                `);
            } else {
                $('#substep1-content').html(`
                    <div class="text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Empty</p>
                    </div>
                `);
            }

            // Update substep 2
            if (data.window.substep2_queue) {
                $('#substep2-content').html(`
                    <div class="text-center">
                        <div class="text-4xl font-bold text-purple-600 mb-4">${data.window.substep2_queue.queue_number}</div>
                        <button onclick="moveToSubstep3()"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center space-x-2">
                            <span>Move to Step 3</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                `);
            } else {
                $('#substep2-content').html(`
                    <div class="text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Empty</p>
                    </div>
                `);
            }

            // Update substep 3
            if (data.window.substep3_queue) {
                $('#substep3-content').html(`
                    <div class="text-center">
                        <div class="text-4xl font-bold text-green-600 mb-4">${data.window.substep3_queue.queue_number}</div>
                        <button onclick="completeSubstep3()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Complete</span>
                        </button>
                    </div>
                `);
            } else {
                $('#substep3-content').html(`
                    <div class="text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Empty</p>
                    </div>
                `);
            }

            // Update waiting queue
            $('#waiting-count').text(data.waiting_queues.length);
            let waitingHtml = '';
            if (data.waiting_queues.length > 0) {
                data.waiting_queues.slice(0, 5).forEach(function(queue, index) {
                    waitingHtml += `
                        <div class="p-3 bg-white rounded-lg flex items-center space-x-4 border">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center font-bold text-orange-600">
                                ${index + 1}
                            </div>
                            <div class="font-bold text-gray-800 text-lg">${queue.queue_number}</div>
                        </div>
                    `;
                });
            } else {
                waitingHtml = `
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>No customers waiting</p>
                    </div>
                `;
            }
            $('#waiting-list').html(waitingHtml);

            // Enable/disable buttons
            const hasWaiting = data.waiting_queues.length > 0;
            const hasSubstep1 = data.window.substep1_queue !== null;
            $('#btn-call-next').prop('disabled', !hasWaiting || hasSubstep1);
            $('#btn-select-queue').prop('disabled', !hasWaiting || hasSubstep1);
        });
}

function showNotification(message, type) {
    const colors = {
        success: 'bg-green-500',
         error: 'bg-red-500',
    info: 'bg-blue-500'
};

const notification = $(`
    <div class="fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
        ${message}
    </div>
`);

$('body').append(notification);

setTimeout(() => {
    notification.fadeOut(300, function() {
        $(this).remove();
    });
}, 3000);
}
// Auto-refresh every 3 seconds
setInterval(refreshWindowData, 3000);
</script>
@endpush
