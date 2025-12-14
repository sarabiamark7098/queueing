@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Queue Generation</h1>
                <p class="text-gray-600">Click a window to generate queue number instantly</p>
            </div>

            <!-- Window Buttons -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                @for($i = 1; $i <= 4; $i++)
                <div class="space-y-3">
                    <!-- Customize Prefix Section -->
                    <div class="bg-gray-50 rounded-lg p-3 border-2 border-gray-200">
                        <div class="flex items-center space-x-2 mb-2">
                            <label class="text-xs font-semibold text-gray-600">Custom Prefix (Optional):</label>
                            <button onclick="togglePrefixEdit({{ $i }})" class="text-blue-600 hover:text-blue-700 text-xs font-semibold">
                                ✏️ Edit
                            </button>
                        </div>
                        <div id="prefix-display-{{ $i }}" class="text-sm font-mono font-bold text-gray-800">
                            {{ $windowPrefixes[$i]['prefix'] }}-0001
                        </div>
                        <div id="prefix-edit-{{ $i }}" class="hidden space-y-2">
                            <input type="text"
                                   id="prefix-input-{{ $i }}"
                                   value="{{ $windowPrefixes[$i]['custom_prefix'] }}"
                                   placeholder="e.g., 11-6995 or ABC-123"
                                   class="w-full px-3 py-2 border rounded text-sm"
                                   maxlength="50">
                            <div class="flex space-x-2">
                                <button onclick="savePrefix({{ $i }})"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1 px-3 rounded">
                                    Save
                                </button>
                                <button onclick="clearPrefix({{ $i }})"
                                        class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-3 rounded">
                                    Reset Default
                                </button>
                                <button onclick="cancelPrefixEdit({{ $i }})"
                                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-bold py-1 px-3 rounded">
                                    Cancel
                                </button>
                            </div>
                            <div class="text-xs text-gray-500">
                                Default: W{{ $i }}-0001 | Custom example: 11-6995-0001
                            </div>
                        </div>
                    </div>

                    <!-- Generate Button -->
                    <button onclick="generateQueue({{ $i }})"
                            id="window-btn-{{ $i }}"
                            class="window-btn w-full bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-2xl p-6 transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <div class="btn-content">
                            <div class="text-4xl font-bold mb-2">Window {{ $i }}</div>
                            <div class="text-lg mb-3">Click to Generate</div>
                            <div class="bg-white bg-opacity-20 rounded-lg p-2">
                                <div class="text-sm opacity-90">
                                    <span class="window-{{ $i }}-waiting">{{ $windowStats[$i]['waiting'] }}</span> waiting •
                                    <span class="window-{{ $i }}-serving">{{ $windowStats[$i]['serving'] }}</span> in process
                                </div>
                            </div>
                        </div>
                        <div class="btn-loading hidden">
                            <svg class="animate-spin h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div class="text-xl font-semibold">Generating...</div>
                        </div>
                    </button>
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
                            <div class="font-bold text-orange-600 text-xl">{{ $queue->queue_number }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $queue->created_at->format('h:i A') }}
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

// Prefix Management Functions
function togglePrefixEdit(windowNumber) {
    $(`#prefix-display-${windowNumber}`).addClass('hidden');
    $(`#prefix-edit-${windowNumber}`).removeClass('hidden');
}

function cancelPrefixEdit(windowNumber) {
    $(`#prefix-display-${windowNumber}`).removeClass('hidden');
    $(`#prefix-edit-${windowNumber}`).addClass('hidden');
}

function savePrefix(windowNumber) {
    const prefix = $(`#prefix-input-${windowNumber}`).val().trim();

    // Validate prefix format
    if (prefix && !/^[A-Za-z0-9\-]+$/.test(prefix)) {
        showNotification('Invalid prefix format. Use only letters, numbers, and hyphens.', 'error');
        return;
    }

    $.post(`/queue/window/${windowNumber}/update-prefix`, {
        custom_prefix: prefix
    })
    .done(function(response) {
        $(`#prefix-display-${windowNumber}`).text(response.prefix + '-0001');
        cancelPrefixEdit(windowNumber);
        showNotification('Prefix updated successfully!', 'success');
    })
    .fail(function(xhr) {
        showNotification('Error updating prefix', 'error');
    });
}

function clearPrefix(windowNumber) {
    if (!confirm('Reset to default prefix (W' + windowNumber + ')?')) return;

    $.post(`/queue/window/${windowNumber}/update-prefix`, {
        custom_prefix: ''
    })
    .done(function(response) {
        $(`#prefix-input-${windowNumber}`).val('');
        $(`#prefix-display-${windowNumber}`).text(response.prefix + '-0001');
        cancelPrefixEdit(windowNumber);
        showNotification('Prefix reset to default!', 'success');
    })
    .fail(function(xhr) {
        showNotification('Error resetting prefix', 'error');
    });
}

// Queue Generation
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

        html += `
            <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                <div>
                    <div class="font-bold text-orange-600 text-xl">${queue.queue_number}</div>
                    <div class="text-sm text-gray-500">${time}</div>
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

refreshInterval = setInterval(refreshData, 5000);

document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(refreshInterval);
    } else {
        refreshData();
        refreshInterval = setInterval(refreshData, 5000);
    }
});
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
