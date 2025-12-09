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
                <button onclick="generateQueue({{ $i }})"
                        id="window-btn-{{ $i }}"
                        class="window-btn bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-2xl p-8 transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <div class="btn-content">
                        <div class="text-5xl font-bold mb-3">Window {{ $i }}</div>
                        <div class="text-xl mb-4">Click to Generate</div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-3">
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
                                {{ in_array($queue->status, ['substep1', 'substep2', 'substep3']) ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $queue->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst(str_replace('substep', 'Step ', $queue->status)) }}
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
    // OPTIMIZED: Single API call for all data
    $.get('/api/system/all-data')
        .done(function(data) {
            // Only update if data changed
            if (data.timestamp === lastDataTimestamp) return;
            lastDataTimestamp = data.timestamp;

            // Update statistics
            $('.stat-waiting').text(data.statistics.waiting);
            $('.stat-serving').text(data.statistics.serving);
            $('.stat-completed').text(data.statistics.completed);

            // Update window stats
            for (let i = 1; i <= 4; i++) {
                $(`.window-${i}-waiting`).text(data.window_stats[i].waiting);
                $(`.window-${i}-serving`).text(data.window_stats[i].serving);
            }

            // Update recent queues
            updateRecentQueues(data.recent_queues);
        });
}

function updateRecentQueues(queues) {
    let html = '';
    queues.forEach(function(queue) {
        let statusClass = queue.status === 'waiting' ? 'bg-yellow-100 text-yellow-800' :
                        (queue.status.includes('substep') ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800');
        let statusText = queue.status.replace('substep', 'Step ');
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

// OPTIMIZED: Refresh every 5 seconds instead of 3
refreshInterval = setInterval(refreshData, 5000);

// Stop refresh when tab is hidden (saves resources)
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
