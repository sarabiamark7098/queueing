<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Queue System') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-gray-800 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Queue System</h1>
            <div class="flex space-x-2">
                <a href="{{ route('display.index') }}"
                   class="px-4 py-2 rounded-lg font-semibold bg-purple-600 hover:bg-purple-700 transition">
                    Display Board
                </a>
                <a href="{{ route('queue.index') }}"
                   class="px-4 py-2 rounded-lg font-semibold bg-orange-600 hover:bg-orange-700 transition">
                    Generate Queue
                </a>
                @for($i = 1; $i <= 4; $i++)
                <a href="{{ route('window.show', $i) }}"
                   class="px-4 py-2 rounded-lg font-semibold bg-blue-600 hover:bg-blue-700 transition">
                    Window {{ $i }}
                </a>
                @endfor
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Additional Scripts -->
    <script>
        // Setup AJAX CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
