@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-50 p-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2"> Queue</h1>
                <p class="text-gray-600">Get your queue number</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                <p class="font-bold">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Generate Queue Button -->
            <div class="flex p-2 gap-2">
                <div class="flex-1">
                    <form action="{{ route('queue.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="window_id" value="1">
                        <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-6 px-6 rounded-lg flex items-center justify-center space-x-3 transition-colors text-2xl shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Generate Queue
                            <br>Windows 1</span>
                        </button>
                    </form>
                </div>
                <div class="flex-1">
                    <form action="{{ route('queue.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="window_id" value="2">
                        <button type="submit"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-6 px-6 rounded-lg flex items-center justify-center space-x-3 transition-colors text-2xl shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Generate Queue
                            <br>Windows 2
                            </span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="flex p-2 gap-2">
                <div class="flex-1">
                    <form action="{{ route('queue.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="window_id" value="3">
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-6 px-6 rounded-lg flex items-center justify-center space-x-3 transition-colors text-2xl shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Generate Queue
                            <br>Windows 3</span>
                        </button>
                    </form>
                </div>
                <div class="flex-1">
                    <form action="{{ route('queue.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="window_id" value="4">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-6 px-6 rounded-lg flex items-center justify-center space-x-3 transition-colors text-2xl shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Generate Queue
                            <br>Windows 4</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mt-8 p-6 bg-orange-50 rounded-lg">
                <h3 class="font-semibold text-gray-700 mb-3 text-lg">Queue Statistics</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-3xl font-bold text-orange-600 waiting-count">{{ $statistics['waiting'] }}</div>
                        <div class="text-sm text-gray-600">Waiting</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-blue-600 serving-count">{{ $statistics['serving'] }}</div>
                        <div class="text-sm text-gray-600">Serving</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-green-600 completed-count">{{ $statistics['completed'] }}</div>
                        <div class="text-sm text-gray-600">Completed</div>
                    </div>
                </div>
            </div>

            <!-- Recent Queues -->
            @if($queues->count() > 0)
            <div class="mt-6">
                <h3 class="font-semibold text-gray-700 mb-3">Recent Queues</h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach($queues as $queue)
                    <div class="p-3 bg-gray-50 rounded-lg flex justify-between items-center">
                        <div class="font-bold text-orange-600 text-xl">{{ $queue->queue_number }}</div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $queue->status === 'waiting' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $queue->status === 'serving' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $queue->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($queue->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
setInterval(function() {
    $.get('/api/queues/statistics').done(function(stats) {
        $('.waiting-count').text(stats.waiting);
        $('.serving-count').text(stats.serving);
        $('.completed-count').text(stats.completed);
    });
}, 5000);
</script>
@endpush
