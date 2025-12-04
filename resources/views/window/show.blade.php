@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Window Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-blue-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold text-gray-800 mb-2">Window {{ $window->window_number }}</h1>
                <div class="inline-block px-6 py-2 rounded-full text-lg font-semibold
                    {{ $window->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($window->status) }}
                </div>
            </div>

            <!-- Current Queue Display -->
            <div id="current-queue-display" class="mb-8">
                @if($window->currentQueue)
                <div class="p-8 bg-blue-50 rounded-2xl border-4 border-blue-200">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Now Serving</div>
                        <div class="text-6xl font-bold text-blue-600">{{ $window->currentQueue->queue_number }}</div>
                    </div>
                </div>
                @else
                <div class="p-8 bg-gray-50 rounded-2xl text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-xl text-gray-500">No customer being served</div>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <button onclick="callNext()"
                        id="btn-call-next"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Call Next</span>
                </button>

                <button onclick="openSelectModal()"
                        id="btn-select-queue"
                        class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <span>Select Queue</span>
                </button>
            </div>

            <!-- Complete Service Button -->
            <button onclick="completeService()"
                    id="btn-complete"
                    style="display: {{ $window->currentQueue ? 'flex' : 'none' }}"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition-colors items-center justify-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Complete Service</span>
            </button>

            <!-- Waiting Queue List -->
            <div class="mt-8 p-6 bg-gray-50 rounded-lg">
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
</div>

<!-- Select Queue Modal -->
<div id="selectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-purple-600 text-white p-6">
            <h2 class="text-3xl font-bold">Select Queue Number</h2>
            <p class="text-purple-100 mt-2">Choose which customer to serve</p>
        </div>

        <!-- Modal Body - Queue List -->
        <div id="modal-queue-list" class="p-6 max-h-[60vh] overflow-y-auto">
            <!-- Queue list will be loaded here via AJAX -->
            <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <p>Loading queues...</p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 p-4 border-t">
            <button onclick="closeSelectModal()"
                    class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const windowNumber = {{ $window->window_number }};
const isWindowBusy = {{ $window->status === 'busy' ? 'true' : 'false' }};

// Disable buttons if window is busy
$(document).ready(function() {
    if (isWindowBusy) {
        $('#btn-call-next').prop('disabled', true);
        $('#btn-select-queue').prop('disabled', true);
    }
});

// Call next queue in line
function callNext() {
    // Disable button to prevent double clicks
    $('#btn-call-next').prop('disabled', true).text('Calling...');

    $.ajax({
        url: `/window/${windowNumber}/call-next`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Show success message briefly before reload
            showNotification('Queue called successfully!', 'success');
            setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
            $('#btn-call-next').prop('disabled', false).text('Call Next');
            const errorMsg = xhr.responseJSON?.error || 'Error calling next queue';
            showNotification(errorMsg, 'error');
        }
    });
}

// Complete current service
function completeService() {
    if (!confirm('Complete this service?')) return;

    // Disable button to prevent double clicks
    $('#btn-complete').prop('disabled', true).text('Completing...');

    $.ajax({
        url: `/window/${windowNumber}/complete`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showNotification('Service completed!', 'success');
            setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
            $('#btn-complete').prop('disabled', false).text('Complete Service');
            const errorMsg = xhr.responseJSON?.error || 'Error completing service';
            showNotification(errorMsg, 'error');
        }
    });
}

// Open queue selection modal
function openSelectModal() {
    $('#selectModal').removeClass('hidden');
    clearInterval(autoRefreshInterval);

    $.ajax({
        url: '/api/queues/waiting',
        method: 'GET',
        data: { window_id: windowNumber },
        success: function(queues) {
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
                const createdDate = new Date(queue.created_at);
                const timeString = createdDate.toLocaleTimeString('en-US', {
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
                                <div class="text-xs text-gray-500">
                                    Generated at ${timeString}
                                </div>
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
        },
        error: function() {
            $('#modal-queue-list').html(`
                <div class="text-center py-8 text-red-400">
                    <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xl">Error loading queue list</p>
                    <button onclick="openSelectModal()" class="mt-4 text-purple-600 hover:text-purple-700 font-semibold">
                        Try Again
                    </button>
                </div>
            `);
        }
    });
}

// Close modal
function closeSelectModal() {
    $('#selectModal').addClass('hidden');
    autoRefreshInterval = setInterval(() => location.reload(), 10000);
}

// Call specific queue
function callSpecific(queueId) {
    // Close modal and show loading
    closeSelectModal();
    showNotification('Calling queue...', 'info');

    $.ajax({
        url: `/window/${windowNumber}/call-specific`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { queue_id: queueId },
        success: function(response) {
            showNotification('Queue called successfully!', 'success');
            setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
            const errorMsg = xhr.responseJSON?.error || 'Error calling queue';
            showNotification(errorMsg, 'error');
        }
    });
}

// Show notification helper function
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

// Auto-refresh every 10 seconds
let autoRefreshInterval = setInterval(function() {
    location.reload();
}, 10000);

// Pause auto-refresh when modal is open
$('#selectModal').on('show', function() {
    clearInterval(autoRefreshInterval);
});

$('#selectModal').on('hide', function() {
    autoRefreshInterval = setInterval(function() {
        location.reload();
    }, 10000);
});

// Handle page visibility change (pause refresh when tab is not visible)
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(autoRefreshInterval);
    } else {
        autoRefreshInterval = setInterval(function() {
            location.reload();
        }, 10000);
    }
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endpush
