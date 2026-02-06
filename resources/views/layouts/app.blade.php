<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Informasi Aplikasi Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #ffffff 100%);
        }

        .sidebar-gradient-hover {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar-gradient-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #ffffff 100%);
            transition: left 0.5s ease;
            opacity: 0.1;
        }

        .sidebar-gradient-hover:hover::before {
            left: 0;
        }

        .sidebar-gradient-hover:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 25%, rgba(240, 147, 251, 0.1) 50%, rgba(245, 87, 108, 0.1) 75%, rgba(255, 255, 255, 0.1) 100%);
            transform: translateX(5px);
        }

        .sidebar-gradient-hover.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 25%, rgba(240, 147, 251, 0.2) 50%, rgba(245, 87, 108, 0.2) 75%, rgba(255, 255, 255, 0.2) 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <button id="sidebarToggle"
                        class="text-white hover:text-white/80 focus:outline-none focus:text-white/90 lg:hidden transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center ml-4">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-md floating">
                            <i class="fas fa-clock text-purple-600"></i>
                        </div>
                        <h1 class="ml-3 text-xl font-bold text-white">Sistem Presensi</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button
                            class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 transition-all hover:scale-105">
                            <div
                                class="h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30 flex items-center justify-center">
                                <span class="text-white font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1))
                                    }}</span>
                            </div>
                            <span class="ml-3 text-white font-medium">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2 text-white/70"></i>
                        </button>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-white/80 hover:text-white focus:outline-none transition-colors hover:scale-110 transform">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    <script>
        // Mobile sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle && sidebar && sidebarOverlay) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        }

        // Logout confirmation function
        function confirmLogout(event) {
            event.preventDefault();
            
            if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
                // Create a form and submit it to logout route
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    
    @stack('scripts')
</body>

</html>
