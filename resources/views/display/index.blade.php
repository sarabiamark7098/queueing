@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-5xl font-bold text-white mb-4">Now Serving</h1>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8">
            @foreach($windows as $window)
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <div class="text-center">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Window {{ $window->window_number }}</h2>

                    @if($window->currentQueue)
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border-4 border-green-200">
                        <div class="text-sm text-gray-600 mb-2">Now Serving</div>
                        <div class="text-5xl font-bold text-green-600 mb-3">{{ $window->currentQueue->queue_number }}</div>
                        <div class="mt-4 inline-block px-4 py-2 bg-green-500 text-white rounded-full font-semibold animate-pulse">
                            IN SERVICE
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-100 rounded-2xl p-6 border-4 border-gray-300">
                        <div class="text-2xl text-gray-500 font-semibold">Available</div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-3 gap-6">
            <div class="bg-yellow-500 rounded-2xl p-6 text-center shadow-xl">
                <div class="text-5xl font-bold text-white mb-2">{{ $statistics['waiting'] }}</div>
                <div class="text-xl text-yellow-100 font-semibold">Waiting</div>
            </div>
            <div class="bg-blue-500 rounded-2xl p-6 text-center shadow-xl">
                <div class="text-5xl font-bold text-white mb-2">{{ $statistics['serving'] }}</div>
                <div class="text-xl text-blue-100 font-semibold">Being Served</div>
            </div>
            <div class="bg-green-500 rounded-2xl p-6 text-center shadow-xl">
                <div class="text-5xl font-bold text-white mb-2">{{ $statistics['completed'] }}</div>
                <div class="text-xl text-green-100 font-semibold">Completed Today</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh every 5 seconds for display board
setInterval(() => location.reload(), 5000);
</script>
@endpush
