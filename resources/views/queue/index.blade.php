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
                <p class="text-gray-600">Click window buttons to generate queue numbers</p>
            </div>

            <!-- Daily Reset Info Banner -->
            <div class="mb-6 p-4 border-2 border-blue-200 rounded-xl flex justify-center items-center">
    <div class="text-center">
        <label class="text-xl font-bold text-gray-800 block mb-2">Generated Queue:</label>
        <span class="text-2xl font-bold text-blue-600" id="generated-queue-number"></span>
    </div>
</div>

            <!-- Window Buttons Grid -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <!-- Window 1 - BLUE -->
                <button onclick="generateQueue(1)" id="window-btn-1"
                        class="window-btn bg-gradient-to-br from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white rounded-2xl p-10 transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <div class="btn-content">
                        <div class="text-6xl font-bold mb-4">Window 1</div>
                        <div class="text-2xl mb-4">Click to Generate</div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-3">
                            <div class="text-base opacity-90">
                                <span class="window-1-waiting">{{ $windowStats[1]['waiting'] }}</span> waiting •
                                <span class="window-1-serving">{{ $windowStats[1]['serving'] }}</span> in process
                            </div>
                        </div>
                    </div>
                    <div class="btn-loading hidden">
                        <svg class="animate-spin h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div class="text-2xl font-semibold">Generating...</div>
                    </div>
                </button>

                <!-- Window 2 - RED -->
                <button onclick="generateQueue(2)" id="window-btn-2"
                        class="window-btn bg-gradient-to-br from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white rounded-2xl p-10 transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <div class="btn-content">
                        <div class="text-6xl font-bold mb-4">Window 2</div>
                        <div class="text-2xl mb-4">Click to Generate</div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-3">
                            <div class="text-base opacity-90">
                                <span class="window-2-waiting">{{ $windowStats[2]['waiting'] }}</span> waiting •
                                <span class="window-2-serving">{{ $windowStats[2]['serving'] }}</span> in process
                            </div>
                        </div>
                    </div>
                    <div class="btn-loading hidden">
                        <svg class="animate-spin h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div class="text-2xl font-semibold">Generating...</div>
                    </div>
                </button>

                <!-- Window 3 - YELLOW -->
                <button onclick="generateQueue(3)" id="window-btn-3"
                        class="window-btn bg-gradient-to-br from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-2xl p-10 transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <div class="btn-content">
                        <div class="text-6xl font-bold mb-4">Window 3</div>
                        <div class="text-2xl mb-4">Click to Generate</div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-3">
                            <div class="text-base opacity-90">
                                <span class="window-3-waiting">{{ $windowStats[3]['waiting'] }}</span> waiting •
                                <span class="window-3-serving">{{ $windowStats[3]['serving'] }}</span> in process
                            </div>
                        </div>
                    </div>
                    <div class="btn-loading hidden">
                        <svg class="animate-spin h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div class="text-2xl font-semibold">Generating...</div>
                    </div>
                </button>

                <!-- Window 4 - ORANGE -->
                <button onclick="generateQueue(4)" id="window-btn-4"
                        class="window-btn bg-gradient-to-br from-orange-500 to-orange-700 hover:from-orange-600 hover:to-orange-800 text-white rounded-2xl p-10 transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <div class="btn-content">
                        <div class="text-6xl font-bold mb-4">Window 4</div>
                        <div class="text-2xl mb-4">Click to Generate</div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-3">
                            <div class="text-base opacity-90">
                                <span class="window-4-waiting">{{ $windowStats[4]['waiting'] }}</span> waiting •
                                <span class="window-4-serving">{{ $windowStats[4]['serving'] }}</span> in process
                            </div>
                        </div>
                    </div>
                    <div class="btn-loading hidden">
                        <svg class="animate-spin h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div class="text-2xl font-semibold">Generating...</div>
                    </div>
                </button>
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
                    <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center border-l-4
                        {{ $queue->window_number == 1 ? 'border-blue-500' : '' }}
                        {{ $queue->window_number == 2 ? 'border-red-500' : '' }}
                        {{ $queue->window_number == 3 ? 'border-yellow-500' : '' }}
                        {{ $queue->window_number == 4 ? 'border-orange-500' : '' }}">
                        <div>
                            <div class="flex items-center space-x-2">
                                <div class="font-bold text-xl
                                    {{ $queue->window_number == 1 ? 'text-blue-600' : '' }}
                                    {{ $queue->window_number == 2 ? 'text-red-600' : '' }}
                                    {{ $queue->window_number == 3 ? 'text-yellow-600' : '' }}
                                    {{ $queue->window_number == 4 ? 'text-orange-600' : '' }}">
                                    {{ $queue->queue_number }}
                                </div>
                                <span class="px-2 py-1 rounded text-xs font-bold
                                    {{ $queue->window_number == 1 ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $queue->window_number == 2 ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $queue->window_number == 3 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $queue->window_number == 4 ? 'bg-orange-100 text-orange-800' : '' }}">
                                    Window {{ $queue->window_number }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500">{{ $queue->created_at->format('h:i A') }}</div>
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

const windowColors = {
    1: { name: 'Blue', textClass: 'text-blue-600', bgClass: 'bg-blue-100', borderClass: 'border-blue-500' },
    2: { name: 'Red', textClass: 'text-red-600', bgClass: 'bg-red-100', borderClass: 'border-red-500' },
    3: { name: 'Yellow', textClass: 'text-yellow-600', bgClass: 'bg-yellow-100', borderClass: 'border-yellow-500' },
    4: { name: 'Orange', textClass: 'text-orange-600', bgClass: 'bg-orange-100', borderClass: 'border-orange-500' }
};

function generateQueue(windowNumber) {
    const windowColors = {
        1: { name: 'Blue', textClass: 'text-blue-600', bgClass: 'bg-blue-100', borderClass: 'border-blue-500' },
        2: { name: 'Red', textClass: 'text-red-600', bgClass: 'bg-red-100', borderClass: 'border-red-500' },
        3: { name: 'Yellow', textClass: 'text-yellow-600', bgClass: 'bg-yellow-100', borderClass: 'border-yellow-500' },
        4: { name: 'Orange', textClass: 'text-orange-600', bgClass: 'bg-orange-100', borderClass: 'border-orange-500' }
    };
    if (isGenerating) return;

    isGenerating = true;
    const btn = $(`#window-btn-${windowNumber}`);

    $('.window-btn').prop('disabled', true);
    btn.find('.btn-content').addClass('hidden');
    btn.find('.btn-loading').removeClass('hidden');

    $.post('/queue/generate', { window_number: windowNumber })
        .done(function(response) {
            showToast('Queue generated: ' + response.queue.queue_number, 'success');
            const windowNum = response.queue.window_number;
            const colors = windowColors[windowNum];

            // Set the generated queue number with correct color
            $('#generated-queue-number')
                .text(response.queue.queue_number)
                .removeClass('text-blue-600 text-red-600 text-yellow-600 text-orange-600') // remove previous classes
                .addClass(colors.textClass);
            refreshData();
            setTimeout(resetButtons, 1000);
        })
        .fail(function(xhr) {
            showToast('Error generating queue', 'error');
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
        const colors = windowColors[queue.window_number];
        let statusClass = queue.status === 'waiting' ? 'bg-yellow-100 text-yellow-800' :
                        (queue.status.includes('substep') || queue.status.includes('waiting_')) ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
        let statusText = queue.status.replace('substep', 'Step ').replace('waiting_', 'Wait Step ');
        let time = new Date(queue.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

        html += `
            <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center border-l-4 ${colors.borderClass}">
                <div>
                    <div class="flex items-center space-x-2">
                        <div class="font-bold text-xl ${colors.textClass}">${queue.queue_number}</div>
                        <span class="px-2 py-1 rounded text-xs font-bold ${colors.bgClass} ${colors.textClass}">W${queue.window_number}</span>
                    </div>
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
