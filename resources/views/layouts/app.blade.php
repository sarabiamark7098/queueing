<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <title>{{ config('app.name', 'Queue System') }}</title>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-gray-800 text-white p-3 shadow-lg">
        <div class="max-w-full mx-auto">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-xl font-bold">Queueing</h1>
                <div class="flex gap-2">
                    <a href="{{ route('queue.index') }}"
                       class="px-4 py-2 rounded-lg font-semibold bg-orange-600 hover:bg-orange-700 transition">
                        Queue Generation
                    </a>
                    <a href="{{ route('display.main') }}"
                       class="px-4 py-2 rounded-lg font-semibold bg-purple-600 hover:bg-purple-700 transition">
                        Main Display
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-2">
                @for($i = 1; $i <= 4; $i++)
                <div class="flex gap-1">
                    <a href="{{ route('window.control', $i) }}"
                       class="flex-1 px-2 py-1 rounded text-xs font-semibold bg-blue-600 hover:bg-blue-700 transition text-center">
                        W{{ $i }} Control
                    </a>
                </div>
                @endfor
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Setup AJAX CSRF Token -->
    <script>
        window.BASE_URL = "{{ url('/') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showToast(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };

            const toast = $(`
                <div class="fixed top-5 right-5 z-50 px-4 py-3 rounded-lg shadow-lg text-white ${colors[type]} animate-fade-in">
                    ${message}
                </div>
            `);

            $('body').append(toast);

            setTimeout(() => {
                toast.addClass('opacity-0 translate-x-10');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>

    @stack('scripts')
</body>
</html>
