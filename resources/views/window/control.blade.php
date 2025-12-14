@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 p-8">
    <div class="max-w-7xl mx-auto">
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
                                <span>Send to Step 2 Queue</span>
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
                                <span>Send to Step 3 Queue</span>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Complete</span>
                            </button>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-8">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Empty</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-8">
                <!-- Step 1 Actions -->
                <div class="mb-6 p-4 bg-blue-50 rounded-xl">
                    <h3 class="font-bold text-blue-800 mb-3">Step 1 Actions</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <button onclick="callNext()" id="btn-call-next"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            Call Next to Step 1
                        </button>
                        <button onclick="openSelectModal(1)" id="btn-select-queue"
                            class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            Select Queue for Step 1
                        </button>
                    </div>
                    <div class="mt-4 p-3 bg-white rounded-lg">
                        <div class="text-sm font-semibold text-gray-600 mb-2">
                            Waiting for Step 1: <span id="waiting-count-1">{{ $waitingQueues->count() }}</span>
                        </div>
                        <div id="waiting-list-1" class="space-y-2 max-h-40 overflow-y-auto">
                            @forelse($waitingQueues->take(3) as $index => $queue)
                            <div class="p-2 bg-gray-50 rounded flex items-center space-x-3 text-sm">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600 text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <div class="font-bold text-gray-800">{{ $queue->queue_number }}</div>
                            </div>
                            @empty
                            <div class="text-center text-gray-400 text-sm py-2">No customers waiting</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Step 2 Actions -->
                <div class="mb-6 p-4 bg-purple-50 rounded-xl">
                    <h3 class="font-bold text-purple-800 mb-3">Step 2 Actions</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <button onclick="callNextToSubstep2()" id="btn-call-next-2"
                            class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            Call Next to Step 2
                        </button>
                        <button onclick="openSelectModal(2)" id="btn-select-queue-2"
                            class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            Select Queue for Step 2
                        </button>
                    </div>
                    <div class="mt-4 p-3 bg-white rounded-lg">
                        <div class="text-sm font-semibold text-gray-600 mb-2">
                            Waiting for Step 2: <span id="waiting-count-2">{{ $waitingSubstep2->count() }}</span>
                        </div>
                        <div id="waiting-list-2" class="space-y-2 max-h-40 overflow-y-auto">
                            @forelse($waitingSubstep2->take(3) as $index => $queue)
                            <div class="p-2 bg-gray-50 rounded flex items-center space-x-3 text-sm">
                                <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center font-bold text-purple-600 text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <div class="font-bold text-gray-800">{{ $queue->queue_number }}</div>
                            </div>
                            @empty
                            <div class="text-center text-gray-400 text-sm py-2">No waiting</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Step 3 Actions -->
                <div class="p-4 bg-green-50 rounded-xl">
                    <h3 class="font-bold text-green-800 mb-3">Step 3 Actions</h3>
                    <button onclick="callNextToSubstep3()" id="btn-call-next-3"
                        class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        Call Next to Step 3
                    </button>
                    <div class="mt-4 p-3 bg-white rounded-lg">
                        <div class="text-sm font-semibold text-gray-600 mb-2">
                            Waiting for Step 3: <span id="waiting-count-3">{{ $waitingSubstep3->count() }}</span>
                        </div>
                        <div id="waiting-list-3" class="space-y-2 max-h-40 overflow-y-auto">
                            @forelse($waitingSubstep3->take(3) as $index => $queue)
                            <div class="p-2 bg-gray-50 rounded flex items-center space-x-3 text-sm">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center font-bold text-green-600 text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <div class="font-bold text-gray-800">{{ $queue->queue_number }}</div>
                            </div>
                            @empty
                            <div class="text-center text-gray-400 text-sm py-2">No waiting</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Select Queue Modal -->
    <div id="selectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div id="modal-header" class="bg-purple-600 text-white p-6">
                <h2 class="text-3xl font-bold">Select Queue Number</h2>
                <p class="text-purple-100 mt-2">Choose which queue you want to call</p>
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
    let refreshInterval = null;
    let lastDataHash = '';
    let currentModalStep = 1;

    // Step 1 Functions
    function callNext() {
        $('#btn-call-next').prop('disabled', true);
        $.post(`/window/${windowNumber}/call-next`)
            .done(function() {
                showNotification('Queue called to Step 1', 'success');
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
                showNotification('Moved to Step 2 waiting queue', 'success');
                refreshWindowData();
            })
            .fail(function(xhr) {
                showNotification(xhr.responseJSON?.error || 'Error', 'error');
            });
    }

    // Step 2 Functions
    function callNextToSubstep2() {
        $('#btn-call-next-2').prop('disabled', true);
        $.post(`/window/${windowNumber}/call-next-substep2`)
            .done(function() {
                showNotification('Queue called to Step 2', 'success');
                refreshWindowData();
            })
            .fail(function(xhr) {
                showNotification(xhr.responseJSON?.error || 'Error calling queue', 'error');
                $('#btn-call-next-2').prop('disabled', false);
            });
    }

    function moveToSubstep3() {
        $.post(`/window/${windowNumber}/move-to-substep3`)
            .done(function() {
                showNotification('Moved to Step 3 waiting queue', 'success');
                refreshWindowData();
            })
            .fail(function(xhr) {
                showNotification(xhr.responseJSON?.error || 'Error', 'error');
            });
    }

    // Step 3 Functions
    function callNextToSubstep3() {
        $('#btn-call-next-3').prop('disabled', true);
        $.post(`/window/${windowNumber}/call-next-substep3`)
            .done(function() {
                showNotification('Queue called to Step 3', 'success');
                refreshWindowData();
            })
            .fail(function(xhr) {
                showNotification(xhr.responseJSON?.error || 'Error calling queue', 'error');
                $('#btn-call-next-3').prop('disabled', false);
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

    // Modal Functions
    function openSelectModal(step) {
        currentModalStep = step;
        $('#selectModal').removeClass('hidden');

        const colors = {
            1: {
                bg: 'bg-blue-600',
                text: 'Step 1'
            },
            2: {
                bg: 'bg-purple-600',
                text: 'Step 2'
            }
        };

        $('#modal-header').attr('class', `${colors[step].bg} text-white p-6`);
        $('#modal-header h2').text(`Select Queue for ${colors[step].text}`);

        const endpoint = step === 1 ?
            `/api/queues/window/${windowNumber}/waiting` :
            `/api/window/${windowNumber}/data`;

        $.get(endpoint)
            .done(function(data) {
                const queues = step === 1 ? data : data.waiting_substep2;

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

                    const color = step === 1 ? 'blue' : 'purple';
                    html += `
                <button onclick="callSpecific(${queue.id}, ${step})"
                        class="w-full p-4 bg-gradient-to-r from-${color}-50 to-indigo-50 hover:from-${color}-100 hover:to-indigo-100 rounded-xl border-2 border-${color}-200 hover:border-${color}-400 transition-all flex items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-${color}-500 group-hover:bg-${color}-600 rounded-full flex items-center justify-center font-bold text-white text-lg transition-colors">
                            ${index + 1}
                        </div>
                        <div class="text-left">
                            <div class="font-bold text-${color}-600 text-xl">${queue.queue_number}</div>
                            <div class="text-xs text-gray-500">${time}</div>
                        </div>
                    </div>
                    <div class="text-${color}-600 font-semibold group-hover:translate-x-1 transition-transform">
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

    function callSpecific(queueId, step) {
        closeSelectModal();
        showNotification('Calling queue...', 'info');
        const endpoint = step === 1 ?
            `/window/${windowNumber}/call-specific` :
            `/window/${windowNumber}/call-specific-substep2`;

        $.post(endpoint, {
                queue_id: queueId
            })
            .done(function() {
                showNotification(`Queue called to Step ${step} successfully`, 'success');
                refreshWindowData();
            })
            .fail(function(xhr) {
                showNotification(xhr.responseJSON?.error || 'Error calling queue', 'error');
            });
    }

    function refreshWindowData() {
        $.get(`/api/window/${windowNumber}/data`)
            .done(function(data) {
                const dataHash = JSON.stringify(data);
                if (dataHash === lastDataHash) return;
                lastDataHash = dataHash;
                updateSubstepDisplay(data);
                updateWaitingQueues(data);
            });
        }

        function updateSubstepDisplay(data) {
            // Update substep 1
            $('#substep1-content').html(data.window.substep1_queue ? `
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-4">${data.window.substep1_queue.queue_number}</div>
                    <button onclick="moveToSubstep2()"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center space-x-2">
                        <span>Send to Step 2 Queue</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            ` : `
                <div class="text-center text-gray-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Empty</p>
                </div>
            `);
            // Update substep 2
            $('#substep2-content').html(data.window.substep2_queue ? `
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-4">${data.window.substep2_queue.queue_number}</div>
                    <button onclick="moveToSubstep3()"
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center space-x-2">
                        <span>Send to Step 3 Queue</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            ` : `
                <div class="text-center text-gray-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Empty</p>
                </div>
            `);

            // Update substep 3
            $('#substep3-content').html(data.window.substep3_queue ? `
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
            ` : `
                <div class="text-center text-gray-400 py-8">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>Empty</p>
                </div>
            `);
            }

            function updateWaitingQueues(data) {
                // Update Step 1 waiting
                $('#waiting-count-1').text(data.waiting_queues.length);
                $('#waiting-list-1').html(renderWaitingList(data.waiting_queues, 'blue'));
                // Update Step 2 waiting
                $('#waiting-count-2').text(data.waiting_substep2.length);
                $('#waiting-list-2').html(renderWaitingList(data.waiting_substep2, 'purple'));

                // Update Step 3 waiting
                $('#waiting-count-3').text(data.waiting_substep3.length);
                $('#waiting-list-3').html(renderWaitingList(data.waiting_substep3, 'green'));

                // Enable/disable buttons
                $('#btn-call-next').prop('disabled', data.waiting_queues.length === 0 || data.window.substep1_queue !== null);
                $('#btn-select-queue').prop('disabled', data.waiting_queues.length === 0 || data.window.substep1_queue !== null);
                $('#btn-call-next-2').prop('disabled', data.waiting_substep2.length === 0 || data.window.substep2_queue !== null);
                $('#btn-select-queue-2').prop('disabled', data.waiting_substep2.length === 0 || data.window.substep2_queue !== null);
                $('#btn-call-next-3').prop('disabled', data.waiting_substep3.length === 0 || data.window.substep3_queue !== null);
            }

            function renderWaitingList(queues, color) {
                if (queues.length === 0) {
                    return '<div class="text-center text-gray-400 text-sm py-2">No customers waiting</div>';
                }
                return queues.slice(0, 3).map((queue, index) => `
                    <div class="p-2 bg-gray-50 rounded flex items-center space-x-3 text-sm">
                        <div class="w-6 h-6 bg-${color}-100 rounded-full flex items-center justify-center font-bold text-${color}-600 text-xs">
                            ${index + 1}
                        </div>
                        <div class="font-bold text-gray-800">${queue.queue_number}</div>
                    </div>
                `).join('');
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
                setTimeout(() => notification.fadeOut(300, function() {
                    $(this).remove();
                }), 3000);
            }
            // Auto-refresh every 5 seconds
            refreshInterval = setInterval(refreshWindowData, 5000);
            // Stop refresh when tab is hidden
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    clearInterval(refreshInterval);
                } else {
                    refreshWindowData();
                    refreshInterval = setInterval(refreshWindowData, 5000);
                }
            });
</script>
@endpush
