@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Queue Generation</h1>
                <p class="text-gray-600">Click window buttons for automatic generation or enter manual queue numbers</p>
            </div>

            <!-- Window Buttons Grid -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                @for($i = 1; $i <= 4; $i++)
                <div class="space-y-4">
                    <!-- Automatic Generation Button -->
                    <button onclick="generateQueue({{ $i }})"
                            id="window-btn-{{ $i }}"
                            class="window-btn w-full bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-2xl p-8 transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <div class="btn-content">
                            <div class="text-5xl font-bold mb-3">Window {{ $i }}</div>
                            <div class="text-xl mb-4">Click to Auto-Generate</div>
                            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                <div class="text-sm opacity-90">
                                    <span class="window-{{ $i }}-waiting">{{ $windowStats[$i]['waiting'] }}</span> waiting •
                                    <span class="window-{{ $i }}-serving">{{ $windowStats[$i]['serving'] }}</span> in process
                                </div>
                            </div>
                            <div class="text-xs mt-2 opacity-75">Format: W{{ $i }}-0001</div>
                        </div>
                        <div class="btn-loading hidden">
                            <svg class="animate-spin h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div class="text-xl font-semibold">Generating...</div>
                        </div>
                    </button>

                    <!-- Manual Queue Input -->
                    <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <label class="text-sm font-bold text-purple-800">Manual Queue Number</label>
                        </div>
                        <div class="flex space-x-2">
                            <div class="flex-1 relative">
                                <input type="text"
                                       id="manual-input-{{ $i }}"
                                       placeholder="e.g., 11-6995-0001"
                                       class="w-full px-3 py-2 border-2 border-purple-300 rounded-lg focus:border-purple-500 focus:outline-none text-sm font-mono uppercase"
                                       maxlength="50"
                                       oninput="validateManualInput({{ $i }})">
                                <div id="manual-validation-{{ $i }}" class="absolute right-2 top-2 text-lg hidden"></div>
                            </div>
                            <button onclick="generateManualQueue({{ $i }})"
                                    id="manual-btn-{{ $i }}"
                                    disabled
                                    class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold px-4 py-2 rounded-lg transition-colors text-sm whitespace-nowrap">
                                Submit
                            </button>
                        </div>
                        <div id="manual-warning-{{ $i }}" class="mt-2 text-xs hidden"></div>
                        <div class="mt-2 text-xs text-gray-600">
                            Enter any unique queue number (letters, numbers, hyphens only)
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <!-- Statistics -->
            <div class="p-6 bg-orange-50 rounded-lg mb-6">
                <h3 class="font-semibold text-gray-700 mb-3 text-lg">Overall Statistics</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-3xl font-bold text-orange-600 stat-waiting">{{ $statistics['waiting'] }}</div>
                        <div class="text-sm text-gray-600">Waiting</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-blue-600 stat-serving">{{ $statistics['serving'] }}</div>
                        <div class="text-sm text-gray-600">In Process</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-green-600 stat-completed">{{ $statistics['completed'] }}</div>
                        <div class="text-sm text-gray-600">Completed</div>
                    </div>
                </div>
            </div>

            <!-- Recent Queues -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">Recent Queue Numbers</h3>
                <div id="recent-queues" class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($recentQueues as $queue)
                    <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                        <div>
                            <div class="flex items-center space-x-2">
                                <div class="font-bold text-orange-600 text-xl">{{ $queue->queue_number }}</div>
                                @if($queue->is_manual)
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded">MANUAL</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $queue->created_at->format('h:i A') }} • Window {{ $queue->window_number }}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $queue->status === 'waiting' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ in_array($queue->status, ['substep1', 'substep2', 'substep3', 'waiting_substep2', 'waiting_substep3']) ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $queue->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst(str_replace(['substep', 'waiting_'], ['Step ', 'Wait Step '], $queue->status)) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let isGenerating = false;
let refreshInterval = null;
let lastDataTimestamp = 0;
let manualValidationTimers = {};

// Automatic Queue Generation
function generateQueue(windowNumber) {
    if (isGenerating) return;

    isGenerating = true;
    const btn = $(`#window-btn-${windowNumber}`);

    $('.window-btn').prop('disabled', true);
    btn.find('.btn-content').addClass('hidden');
    btn.find('.btn-loading').removeClass('hidden');

    $.post('/queue/generate', { window_number: windowNumber })
        .done(function(response) {
            showNotification('Queue generated: ' + response.queue.queue_number, 'success');
            refreshData();
            setTimeout(resetButtons, 1000);
        })
        .fail(function(xhr) {
            showNotification('Error generating queue', 'error');
            resetButtons();
        });
}

function resetButtons() {
    isGenerating = false;
    $('.window-btn').prop('disabled', false);
    $('.btn-loading').addClass('hidden');
    $('.btn-content').removeClass('hidden');
}

// Manual Queue Generation
function validateManualInput(windowNumber) {
    clearTimeout(manualValidationTimers[windowNumber]);

    const input = $(`#manual-input-${windowNumber}`);
    const queueNumber = input.val().trim().toUpperCase();
    const validationIcon = $(`#manual-validation-${windowNumber}`);
    const warningBox = $(`#manual-warning-${windowNumber}`);
    const submitBtn = $(`#manual-btn-${windowNumber}`);

    // Update input to uppercase
    input.val(queueNumber);

    if (!queueNumber) {
        validationIcon.addClass('hidden');
        warningBox.addClass('hidden');
        submitBtn.prop('disabled', true);
        return;
    }

    // Validate format
    if (!/^[A-Z0-9\-]+$/.test(queueNumber)) {
        validationIcon.removeClass('hidden').text('❌');
        warningBox.removeClass('hidden')
            .removeClass('text-green-600 bg-green-50 border-green-200')
            .addClass('text-red-600 bg-red-50 border-red-200 border rounded p-2')
            .text('Invalid format. Use only letters, numbers, and hyphens.');
        submitBtn.prop('disabled', true);
        return;
    }

    // Check availability after 500ms delay
    manualValidationTimers[windowNumber] = setTimeout(() => {
        $.post('/queue/check-queue-number', {
            queue_number: queueNumber
        })
        .done(function(response) {
            if (response.available) {
                validationIcon.removeClass('hidden').text('✅');
                warningBox.removeClass('hidden')
                    .removeClass('text-red-600 bg-red-50 border-red-200')
                    .addClass('text-green-600 bg-green-50 border-green-200 border rounded p-2')
                    .text(`"${response.queue_number}" is available!`);
                submitBtn.prop('disabled', false);
            } else {
                validationIcon.removeClass('hidden').text('❌');
                warningBox.removeClass('hidden')
                    .removeClass('text-green-600 bg-green-50 border-green-200')
                    .addClass('text-red-600 bg-red-50 border-red-200 border rounded p-2')
                    .text(`"${response.queue_number}" already exists!`);
                submitBtn.prop('disabled', true);
            }
        });
    }, 500);
}

function generateManualQueue(windowNumber) {
    const queueNumber = $(`#manual-input-${windowNumber}`).val().trim().toUpperCase();

    if (!queueNumber) {
        showNotification('Please enter a queue number', 'error');
        return;
    }

    const submitBtn = $(`#manual-btn-${windowNumber}`);
    submitBtn.prop('disabled', true).text('Wait...');

    $.post('/queue/generate-manual', {
        window_number: windowNumber,
        queue_number: queueNumber
    })
    .done(function(response) {
        showNotification(response.message + ': ' + response.queue.queue_number, 'success');

        // Reset form
        $(`#manual-input-${windowNumber}`).val('');
        $(`#manual-validation-${windowNumber}`).addClass('hidden');
        $(`#manual-warning-${windowNumber}`).addClass('hidden');
        submitBtn.prop('disabled', true).text('Submit');

        refreshData();
    })
    .fail(function(xhr) {
        const error = xhr.responseJSON?.error || 'Error generating queue';
        showNotification(error, 'error');
        submitBtn.prop('disabled', false).text('Submit');
    });
}

// Data Refresh
function refreshData() {
    $.get('/api/system/all-data')
        .done(function(data) {
            if (data.timestamp === lastDataTimestamp) return;
            lastDataTimestamp = data.timestamp;

            $('.stat-waiting').text(data.statistics.waiting);
            $('.stat-serving').text(data.statistics.serving);
            $('.stat-completed').text(data.statistics.completed);

            for (let i = 1; i <= 4; i++) {
                $(`.window-${i}-waiting`).text(data.window_stats[i].waiting);
                $(`.window-${i}-serving`).text(data.window_stats[i].serving);
            }

            updateRecentQueues(data.recent_queues);
        });
}

function updateRecentQueues(queues) {
    let html = '';
    queues.forEach(function(queue) {
        let statusClass = queue.status === 'waiting' ? 'bg-yellow-100 text-yellow-800' :
                        (queue.status.includes('substep') || queue.status.includes('waiting_')) ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
        let statusText = queue.status.replace('substep', 'Step ').replace('waiting_', 'Wait Step ');
        let time = new Date(queue.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

        let badge = queue.is_manual ? '<span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded">MANUAL</span>' : '';

        html += `
            <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                <div>
                    <div class="flex items-center space-x-2">
                        <div class="font-bold text-orange-600 text-xl">${queue.queue_number}</div>
                        ${badge}
                    </div>
                    <div class="text-sm text-gray-500">${time} • Window ${queue.window_number}</div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">
                        ${statusText.charAt(0).toUpperCase() + statusText.slice(1)}
                    </span>
                </div>
            </div>
        `;
    });
    $('#recent-queues').html(html);
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
    setTimeout(() => notification.fadeOut(300, function() { $(this).remove(); }), 3000);
}

// Auto-refresh every 5 seconds
refreshInterval = setInterval(refreshData, 5000);

// Stop refresh when tab is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(refreshInterval);
    } else {
        refreshData();
        refreshInterval = setInterval(refreshData, 5000);
    }
});

// Allow Enter key to submit manual queue
for (let i = 1; i <= 4; i++) {
    $(`#manual-input-${i}`).on('keypress', function(e) {
        if (e.which === 13 && !$(`#manual-btn-${i}`).prop('disabled')) {
            generateManualQueue(i);
        }
    });
}
</script>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endpush
