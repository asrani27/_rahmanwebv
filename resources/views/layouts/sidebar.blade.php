<!-- Sidebar -->
<aside id="sidebar"
    class="w-64 bg-white shadow-xl min-h-screen transition-all duration-300 transform lg:translate-x-0 -translate-x-full fixed lg:relative lg:block hidden border-r border-gray-200">
    <div class="p-6">
        <!-- User Profile Section -->
        <div class="mb-8 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center">
                    <span class="text-white font-bold text-lg">{{ strtoupper(substr(auth()->user()->name, 0, 1))
                        }}</span>
                </div>
                <div class="ml-3">
                    <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu - Dynamic based on role -->
        @if(auth()->user()->role === 'admin')
            @include('layouts.menus.menu_admin')
        @elseif(auth()->user()->role === 'skpd')
            @include('layouts.menus.menu_skpd')
        @elseif(auth()->user()->role === 'pegawai')
            @include('layouts.menus.menu_pegawai')
        @endif

    </div>
</aside>