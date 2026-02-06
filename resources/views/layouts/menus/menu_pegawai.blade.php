<!-- Pegawai Menu -->
<ul class="space-y-2">
    <li>
        <a href="{{ route('pegawai.dashboard') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('pegawai.dashboard')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-tachometer-alt w-5 text-purple-600"></i>
            <span class="ml-3">Dashboard</span>
            @if(request()->routeIs('pegawai.dashboard'))
            <i class="fas fa-chevron-right ml-auto text-purple-600"></i>
            @else
            <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
            @endif
        </a>
    </li>
    <li>
        <a href="{{ route('pegawai.profil') }}"
            class="sidebar-gradient-hover @if(request()->routeIs('pegawai.profil')) active @endif flex items-center p-3 rounded-lg text-gray-700 font-medium relative">
            <i class="fas fa-user w-5 text-purple-600"></i>
            <span class="ml-3">Profil Saya</span>
            @if(request()->routeIs('pegawai.profil'))
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