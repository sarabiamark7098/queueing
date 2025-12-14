@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-[1920px] mx-auto">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-5xl font-bold text-white mb-2">Queue System</h1>
            <div class="text-xl text-purple-200">All Windows Display</div>
        </div>

        <!-- Windows Grid -->
        <div class="grid grid-cols-2 gap-4 mb-6">
        @php
        $windowBgColors = [
            1 => 'bg-green-300',
            2 => 'bg-red-300',
            3 => 'bg-yellow-300',
            4 => 'bg-blue-300',
        ];
        $windowColors = [
            1 => [
                'bg' => 'bg-green-50',
                'border' => 'border-green-200',
                'text' => 'text-green-800',
                'value' => 'text-green-600',
                'status' => 'text-green-500',
            ],
            2 => [
                'bg' => 'bg-red-50',
                'border' => 'border-red-200',
                'text' => 'text-red-800',
                'value' => 'text-red-600',
                'status' => 'text-red-500',
            ],
            3 => [
                'bg' => 'bg-yellow-50',
                'border' => 'border-yellow-200',
                'text' => 'text-yellow-800',
                'value' => 'text-yellow-600',
                'status' => 'text-yellow-500',
            ],
            4 => [
                'bg' => 'bg-blue-50',
                'border' => 'border-blue-200',
                'text' => 'text-blue-800',
                'value' => 'text-blue-600',
                'status' => 'text-blue-500',
            ],
        ];
        @endphp
        @foreach($windows as $window)
            @php
            $color = $windowColors[$window->window_number] ?? $windowColors[1];
            @endphp

            @php
$bgColor = $windowBgColors[$window->window_number] ?? 'bg-gray-100';
@endphp

<div
    class="{{ $bgColor }} rounded-2xl shadow-2xl p-6"
    id="window-{{ $window->window_number }}"
>
                <h2 class="text-3xl font-bold text-gray-800 text-center mb-4">
                    Window {{ $window->window_number }}
                </h2>

                <div class="grid grid-cols-3 gap-3">

                    <!-- STEP 1 -->
                    <div class="{{ $color['bg'] }} rounded-xl p-4 border-2 {{ $color['border'] }}">
                        <div class="text-xs font-bold {{ $color['text'] }} text-center mb-2">STEP 1</div>

                        @if($window->substep1Queue)
                            <div class="text-center">
                                <div class="text-2xl font-bold {{ $color['value'] }}">
                                    {{ $window->substep1Queue->queue_number }}
                                </div>
                                <div class="text-xs {{ $color['status'] }} mt-1">In Progress</div>
                            </div>
                        @else
                            <div class="text-center text-gray-400 py-2">
                                <div class="text-sm">Empty</div>
                            </div>
                        @endif
                    </div>

                    <!-- STEP 2 -->
                    <div class="{{ $color['bg'] }} rounded-xl p-4 border-2 {{ $color['border'] }}">
                        <div class="text-xs font-bold {{ $color['text'] }} text-center mb-2">STEP 2</div>

                        @if($window->substep2Queue)
                            <div class="text-center">
                                <div class="text-2xl font-bold {{ $color['value'] }}">
                                    {{ $window->substep2Queue->queue_number }}
                                </div>
                                <div class="text-xs {{ $color['status'] }} mt-1">In Progress</div>
                            </div>
                        @else
                            <div class="text-center text-gray-400 py-2">
                                <div class="text-sm">Empty</div>
                            </div>
                        @endif
                    </div>

                    <!-- STEP 3 -->
                    <div class="{{ $color['bg'] }} rounded-xl p-4 border-2 {{ $color['border'] }}">
                        <div class="text-xs font-bold {{ $color['text'] }} text-center mb-2">STEP 3</div>

                        @if($window->substep3Queue)
                            <div class="text-center">
                                <div class="text-2xl font-bold {{ $color['value'] }}">
                                    {{ $window->substep3Queue->queue_number }}
                                </div>
                                <div class="text-xs {{ $color['status'] }} mt-1">In Progress</div>
                            </div>
                        @else
                            <div class="text-center text-gray-400 py-2">
                                <div class="text-sm">Empty</div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-yellow-500 rounded-xl p-4 text-center">
                <div class="text-4xl font-bold text-white stat-waiting">{{ $statistics['waiting'] }}</div>
                <div class="text-lg text-yellow-100 font-semibold">Total Waiting</div>
            </div>
            <div class="bg-blue-500 rounded-xl p-4 text-center">
                <div class="text-4xl font-bold text-white stat-serving">{{ $statistics['serving'] }}</div>
                <div class="text-lg text-blue-100 font-semibold">In Process</div>
            </div>
            <div class="bg-green-500 rounded-xl p-4 text-center">
                <div class="text-4xl font-bold text-white stat-completed">{{ $statistics['completed'] }}</div>
                <div class="text-lg text-green-100 font-semibold">Completed</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshMainDisplay() {
    $.get('/api/display/data')
        .done(function(data) {
            // Update each window
            data.windows.forEach(function(window) {
                const windowId = `#window-${window.window_number}`;

                // Update Step 1
                if (window.substep1_queue) {
                    $(windowId + ' .substep1-content').html(`
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">${window.substep1_queue.queue_number}</div>
                            <div class="text-xs text-blue-500 mt-1">In Progress</div>
                        </div>
                    `);
                } else {
                    $(windowId + ' .substep1-content').html(`
                        <div class="text-center text-gray-400 py-2">
                            <div class="text-sm">Empty</div>
                        </div>
                    `);
                }

                // Update Step 2
                if (window.substep2_queue) {
                    $(windowId + ' .substep2-content').html(`
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">${window.substep2_queue.queue_number}</div>
                            <div class="text-xs text-purple-500 mt-1">In Progress</div>
                        </div>
                    `);
                } else {
                    $(windowId + ' .substep2-content').html(`
                        <div class="text-center text-gray-400 py-2">
                            <div class="text-sm">Empty</div>
                        </div>
                    `);
                }

                // Update Step 3
                if (window.substep3_queue) {
                    $(windowId + ' .substep3-content').html(`
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">${window.substep3_queue.queue_number}</div>
                            <div class="text-xs text-green-500 mt-1">In Progress</div>
                        </div>
                    `);
                } else {
                    $(windowId + ' .substep3-content').html(`
                        <div class="text-center text-gray-400 py-2">
                            <div class="text-sm">Empty</div>
                        </div>
                    `);
                }
            });

            // Update statistics
            $('.stat-waiting').text(data.statistics.waiting);
            $('.stat-serving').text(data.statistics.serving);
            $('.stat-completed').text(data.statistics.completed);
        });
}

// Auto-refresh every 2 seconds
setInterval(refreshMainDisplay, 2000);
</script>
@endpush
