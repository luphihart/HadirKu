@props(['role' => 'murid'])

@php
    $user = auth()->user();
    $name = $user ? $user->name : 'User';
    $email = $user ? $user->email : '';
    $photoUrl = $user && $user->profile_photo 
        ? asset('storage/' . $user->profile_photo) 
        : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80';

    $adminMenu = [
        ['route' => 'admin.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z', 'label' => 'Dashboard', 'mobile' => true],
        ['route' => 'admin.kelas', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Kelas', 'mobile' => true],
        ['route' => 'admin.murid', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Murid', 'mobile' => true],
        ['route' => 'admin.rekap-presensi', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Rekap', 'mobile' => true],
        ['route' => 'admin.persetujuan-izin', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Izin', 'mobile' => true],
        ['route' => 'admin.kalender', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Kalender', 'mobile' => false],
        ['route' => 'admin.pengumuman', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'label' => 'Pengumuman', 'mobile' => false],
        ['route' => 'admin.pengaturan', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'label' => 'Pengaturan', 'mobile' => false],
        ['route' => 'admin.profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Profil', 'mobile' => false],
    ];

    $muridMenu = [
        ['route' => 'murid.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'mobile' => true],
        ['route' => 'murid.presensi', 'icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9zM15 13a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Presensi', 'mobile' => true],
        ['route' => 'murid.pengajuan-izin', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Izin', 'mobile' => true],
        ['route' => 'murid.profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Profil', 'mobile' => true],
    ];

    $menuItems = $role === 'admin' ? $adminMenu : $muridMenu;
@endphp

<!-- Desktop Sidebar -->
<aside class="hidden md:flex flex-col w-64 bg-slate-900 text-slate-100 min-h-screen fixed left-0 top-0 z-30 border-r border-slate-800">
    <!-- Brand / Logo -->
    <div class="h-20 flex items-center px-6 border-b border-slate-800">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-600/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <span class="text-xl font-bold tracking-tight text-white block">Hadirku</span>
                <span class="text-xs text-slate-400 block -mt-1">Aplikasi Presensi</span>
            </div>
        </div>
    </div>

    <!-- Menu Items -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        @foreach($menuItems as $item)
            @php $isActive = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}" wire:navigate
               class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ $isActive ? 'bg-indigo-600 text-white font-medium shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- User Section / Footer -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/40">
        <div class="flex items-center space-x-3 mb-4">
            <img class="w-10 h-10 rounded-xl object-cover ring-2 ring-slate-800" src="{{ $photoUrl }}" alt="Avatar">
            <div class="flex-1 min-w-0">
                <span class="block text-sm font-semibold text-white truncate">{{ $name }}</span>
                <span class="block text-xs text-slate-400 truncate">{{ $email }}</span>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 bg-slate-800 hover:bg-red-900/40 hover:text-red-200 text-slate-400 rounded-xl transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="text-sm font-medium">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-slate-900/95 backdrop-blur-md border-t border-slate-800 z-30 shadow-lg px-2 flex justify-around items-center h-16 pb-safe">
    @foreach(array_filter($menuItems, fn($i) => $i['mobile']) as $item)
        @php $isActive = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}" wire:navigate
           class="flex flex-col items-center justify-center w-16 h-12 rounded-xl transition-all duration-200 {{ $isActive ? 'text-indigo-400 font-medium' : 'text-slate-500' }}">
            <svg class="w-5 h-5 {{ $isActive ? 'text-indigo-400 scale-110' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
            </svg>
            <span class="text-[10px] mt-1">{{ $item['label'] }}</span>
        </a>
    @endforeach
    
    <!-- Profile / Settings for Mobile -->
    @if($role === 'admin')
        @php $isActive = request()->routeIs('admin.profil') || request()->routeIs('admin.pengaturan'); @endphp
        <a href="{{ route('admin.profil') }}" wire:navigate
           class="flex flex-col items-center justify-center w-16 h-12 rounded-xl transition-all duration-200 {{ $isActive ? 'text-indigo-400' : 'text-slate-500' }}">
            <svg class="w-5 h-5 {{ $isActive ? 'text-indigo-400 scale-110' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            </svg>
            <span class="text-[10px] mt-1">Admin</span>
        </a>
    @endif
    
    <form method="POST" action="{{ route('logout') }}" id="mobile-logout-form" class="hidden">
        @csrf
    </form>
    <a href="#" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();" 
       class="flex flex-col items-center justify-center w-16 h-12 text-slate-500 hover:text-red-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        <span class="text-[10px] mt-1">Keluar</span>
    </a>
</nav>
