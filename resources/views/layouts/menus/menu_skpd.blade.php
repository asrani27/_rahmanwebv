<!-- SKPD Menu -->
<ul class="space-y-2">
    <li>
        <a href="{{ route('skpd.dashboard') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('skpd.dashboard')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-tachometer-alt w-5 text-purple-600"></i>
            <span class="ml-3">Dashboard</span>
            @if(request()->routeIs('skpd.dashboard'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>

    <li>
        <a href="{{ route('skpd.profil') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('skpd.profil')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-user-circle w-5 text-purple-600"></i>
            <span class="ml-3">Profil</span>
            @if(request()->routeIs('skpd.profil'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>

    <li>
        <a href="{{ route('skpd.pegawai.index') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('skpd.pegawai.index')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-users w-5 text-purple-600"></i>
            <span class="ml-3">Pegawai</span>
            @if(request()->routeIs('skpd.pegawai.index'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>

    <li>
        <a href="{{ route('skpd.lokasi.index') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('skpd.lokasi.index')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-map-marker-alt w-5 text-purple-600"></i>
            <span class="ml-3">Lokasi</span>
            @if(request()->routeIs('skpd.lokasi.index'))
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