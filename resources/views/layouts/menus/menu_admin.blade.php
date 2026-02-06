<!-- Admin Menu -->
<ul class="space-y-2">
    <li>
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('admin.dashboard')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-tachometer-alt w-5 text-purple-600"></i>
            <span class="ml-3">Dashboard</span>
            @if(request()->routeIs('admin.dashboard'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('admin.users.*')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-user w-5 text-purple-600"></i>
            <span class="ml-3">User</span>
            @if(request()->routeIs('admin.users.*'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>
    <li>
        <a href="{{ route('admin.skpd.index') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('admin.skpd.*')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-building w-5 text-purple-600"></i>
            <span class="ml-3">SKPD</span>
            @if(request()->routeIs('admin.skpd.*'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>
    <li>
        <a href="{{ route('admin.pegawai.index') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('admin.pegawai.*')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-users w-5 text-purple-600"></i>
            <span class="ml-3">Pegawai</span>
            @if(request()->routeIs('admin.pegawai.*'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>
    <li>
        <a href="{{ route('admin.lokasi.index') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('admin.lokasi.*')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-map-marker-alt w-5 text-purple-600"></i>
            <span class="ml-3">Lokasi Presensi</span>
            @if(request()->routeIs('admin.lokasi.*'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>
    
    <!-- Common Menu -->
    <li>
        <a href="{{ route('laporan.index') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('laporan.*')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-file-alt w-5 text-purple-600"></i>
            <span class="ml-3">Laporan</span>
            @if(request()->routeIs('laporan.*'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>
    <li>
        <a href="#" onclick="confirmLogout(event)"
            class="sidebar-gradient-hover flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-sign-out-alt w-5 text-purple-600"></i>
            <span class="ml-3">Keluar</span>
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
        </a>
    </li>
</ul>