@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-[1920px] mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-5xl font-bold text-white mb-2">Catering Queue System</h1>
            <div class="text-xl text-purple-200">All Windows Display</div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            @foreach($windows as $window)
            <div class="bg-white rounded-2xl shadow-2xl p-6">
                <h2 class="text-3xl font-bold text-gray-800 text-center mb-4">Window {{ $window->window_number }}</h2>

                <div class="grid grid-cols-3 gap-3">
                    <!-- Step 1 -->
                    <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200">
                        <div class="text-xs font-bold text-blue-800 text-center mb-2">STEP 1</div>
                        @if($window->substep1Queue)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $window->substep1Queue->queue_number }}</div>
                            <div class="text-xs text-blue-500 mt-1">In Progress</div>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-2"><div class="text-sm">Empty</div></div>
                        @endif
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-purple-50 rounded-xl p-4 border-2 border-purple-200">
                        <div class="text-xs font-bold text-purple-800 text-center mb-2">STEP 2</div>
                        @if($window->substep2Queue)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $window->substep2Queue->queue_number }}</div>
                            <div class="text-xs text-purple-500 mt-1">In Progress</div>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-2"><div class="text-sm">Empty</div></div>
                        @endif
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                        <div class="text-xs font-bold text-green-800 text-center mb-2">STEP 3</div>
                        @if($window->substep3Queue)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $window->substep3Queue->queue_number }}</div>
                            <div class="text-xs text-green-500 mt-1">In Progress</div>
                        </div>
                        @else
                        <div class="text-center text-gray-400 py-2"><div class="text-sm">Empty</div></div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-yellow-500 rounded-xl p-4 text-center">
                <div class="text-4xl font-bold text-white">{{ $statistics['waiting'] }}</div>
                <div class="text-lg text-yellow-100 font-semibold">Total Waiting</div>
            </div>
            <div class="bg-blue-500 rounded-xl p-4 text-center">
                <div class="text-4xl font-bold text-white">{{ $statistics['serving'] }}</div>
                <div class="text-lg text-blue-100 font-semibold">In Process</div>
            </div>
            <div class="bg-green-500 rounded-xl p-4 text-center">
                <div class="text-4xl font-bold text-white">{{ $statistics['completed'] }}</div>
                <div class="text-lg text-green-100 font-semibold">Completed</div>
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
