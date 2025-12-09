@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-6xl font-bold text-white mb-4">Window {{ $window->window_number }}</h1>
            <div class="text-2xl text-purple-200">Service Progress Display</div>
        </div>

        <!-- Three Steps Display -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <h2 class="text-3xl font-bold text-blue-800 text-center mb-6">STEP 1</h2>
                <div id="display-substep1">
                    @if($window->substep1Queue)
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 border-4 border-blue-300">
                        <div class="text-center">
                            <div class="text-sm text-gray-600 mb-2">Now Serving</div>
                            <div class="text-5xl font-bold text-blue-600">{{ $window->substep1Queue->queue_number }}</div>
                            <div class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded-full font-semibold animate-pulse">
                                IN PROGRESS
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-100 rounded-2xl p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xl text-gray-500">Waiting</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <h2 class="text-3xl font-bold text-purple-800 text-center mb-6">STEP 2</h2>
                <div id="display-substep2">
                    @if($window->substep2Queue)
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 border-4 border-purple-300">
                        <div class="text-center">
                            <div class="text-sm text-gray-600 mb-2">Now Serving</div>
                            <div class="text-5xl font-bold text-purple-600">{{ $window->substep2Queue->queue_number }}</div>
                            <div class="mt-4 inline-block px-4 py-2 bg-purple-500 text-white rounded-full font-semibold animate-pulse">
                                IN PROGRESS
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-100 rounded-2xl p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xl text-gray-500">Waiting</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <h2 class="text-3xl font-bold text-green-800 text-center mb-6">STEP 3</h2>
                <div id="display-substep3">
                    @if($window->substep3Queue)
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 border-4 border-green-300">
                        <div class="text-center">
                            <div class="text-sm text-gray-600 mb-2">Now Serving</div>
                            <div class="text-5xl font-bold text-green-600">{{ $window->substep3Queue->queue_number }}</div>
                            <div class="mt-4 inline-block px-4 py-2 bg-green-500 text-white rounded-full font-semibold animate-pulse">
                                IN PROGRESS
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-100 rounded-2xl p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xl text-gray-500">Waiting</div>
                    </div>
                    @endif
                </div>
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

function refreshDisplayData() {
    $.get(`/api/window/${windowNumber}/data`)
        .done(function(data) {
            const dataHash = JSON.stringify(data.window);
            if (dataHash === lastDataHash) return;
            lastDataHash = dataHash;

            updateDisplays(data.window);
        });
}

function updateDisplays(window) {
    const steps = ['substep1', 'substep2', 'substep3'];
    const colors = {
        substep1: { from: 'blue-50', to: 'blue-100', border: 'blue-300', text: 'blue-600', bg: 'blue-500' },
        substep2: { from: 'purple-50', to: 'purple-100', border: 'purple-300', text: 'purple-600', bg: 'purple-500' },
        substep3: { from: 'green-50', to: 'green-100', border: 'green-300', text: 'green-600', bg: 'green-500' }
    };

    steps.forEach(function(step) {
        const queue = window[step + '_queue'];
        const color = colors[step];

        $(`#display-${step}`).html(queue ? `
            <div class="bg-gradient-to-br from-${color.from} to-${color.to} rounded-2xl p-8 border-4 border-${color.border}">
                <div class="text-center">
                    <div class="text-sm text-gray-600 mb-2">Now Serving</div>
                    <div class="text-5xl font-bold text-${color.text}">${queue.queue_number}</div>
                    <div class="mt-4 inline-block px-4 py-2 bg-${color.bg} text-white rounded-full font-semibold animate-pulse">
                        IN PROGRESS
                    </div>
                </div>
            </div>
        ` : `
            <div class="bg-gray-100 rounded-2xl p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-xl text-gray-500">Waiting</div>
            </div>
        `);
    });
}

// OPTIMIZED: Refresh every 4 seconds
refreshInterval = setInterval(refreshDisplayData, 4000);

// Stop refresh when tab is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(refreshInterval);
    } else {
        refreshDisplayData();
        refreshInterval = setInterval(refreshDisplayData, 4000);
    }
});
</script>
@endpush
