@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-6xl font-bold text-white mb-4">Window {{ $window->window_number }}</h1>
            <div class="text-2xl text-purple-200">Service Progress Display</div>
        </div>

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
                        <div class="text-2xl text-gray-500 font-semibold">Waiting</div>
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
                        <div class="text-2xl text-gray-500 font-semibold">Waiting</div>
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
                        <div class="text-2xl text-gray-500 font-semibold">Waiting</div>
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
setInterval(() => location.reload(), 3000);
</script>
@endpush
