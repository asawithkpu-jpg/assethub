<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('dark') === 'true', sidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AssetHub - KPU Kabupaten Pasuruan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assethub-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4680ff',
                        secondary: '#5b6b79',
                        darkBg: '#111827',
                        darkCard: '#1f2937'
                    },
                    fontFamily: { 'sans': ['Public Sans', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-item-active { background: rgba(70, 128, 255, 0.1); border-right: 3px solid #4680ff; color: #4680ff; }
        
        /* FIX DATATABLES GIGANTIS (Gambar 1) - Memaksa ukuran compact */
        .dataTables_wrapper { font-size: 11px !important; }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            padding: 3px 8px !important;
            font-size: 11px !important;
            border-radius: 4px !important;
            border: 1px solid #e5e7eb !important;
            height: auto !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 2px 8px !important;
            font-size: 10px !important;
            margin-left: 2px !important;
            border-radius: 4px !important;
        }
        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #d1d5db !important;
        }

        /* Styling umum datatables dark mode */
        .dark .dataTables_wrapper .dataTables_length, 
        .dark .dataTables_wrapper .dataTables_filter, 
        .dark .dataTables_wrapper .dataTables_info, 
        .dark .dataTables_wrapper .dataTables_paginate { color: #9ca3af !important; }
        .dark table.dataTable border-bottom { border-bottom: 1px solid #374151 !important; }
    </style>
</head>
<body class="bg-[#f4f7fa] dark:bg-darkBg text-[#2f3944] dark:text-gray-200 font-sans text-sm">

    <div class="flex h-screen overflow-hidden">
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition ease-in-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in-out duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm md:hidden"
            x-cloak>
        </div>

        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-darkCard border-r dark:border-gray-700 transition-transform duration-300 md:relative md:translate-x-0">
            
            <div class="p-6 flex items-center justify-center">
                <img src="{{ asset('images/assethub-horizontal.png') }}" alt="AssetHub" class="h-8 dark:brightness-200">
            </div>

            <nav class="mt-4 px-3 space-y-1 overflow-y-auto h-[calc(100vh-80px)] text-[12px]">
                <p class="text-[10px] font-bold text-gray-400 uppercase px-4 py-2 tracking-widest">MAIN</p>
                
                @can('dashboard')
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 py-2 px-4 rounded transition hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                @endcan

                @canany(['master-asset', 'peminjaman-internal', 'peminjaman-eksternal'])
                <p class="text-[10px] font-bold text-gray-400 uppercase px-4 py-2 mt-4 tracking-widest">Inventaris & Layanan</p>
                
                @can('master-asset')
                <a href="{{ route('assets.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('assets.index') ? 'sidebar-item-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span class="font-medium">Master Asset</span>
                </a>
                @endcan

                @can('peminjaman-internal')
                <a href="{{ route('peminjaman-internal.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('peminjaman-internal.*') ? 'sidebar-item-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span class="font-medium">Peminjaman Internal</span>
                </a>
                @endcan

                @can('peminjaman-eksternal')
                <a href="{{ route('peminjaman-eksternal.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('peminjaman-eksternal.*') ? 'sidebar-item-active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>
                    <span class="font-medium">Peminjaman Eksternal</span>
                </a>
                @endcan
                @endcanany

                @canany(['history-barang', 'history-peminjam'])
                <p class="text-[10px] font-bold text-gray-400 uppercase px-4 py-2 mt-4 tracking-widest">Laporan</p>
                
                @can('history-barang')
                <a href="{{ route('history-barang.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('history-barang.*') ? 'bg-gray-100 dark:bg-gray-800 text-primary' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span class="font-medium">History Barang</span>
                </a>
                @endcan

                @can('history-peminjam')
                <a href="{{ route('history-peminjam.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('history-peminjam.*') ? 'bg-gray-100 dark:bg-gray-800 text-primary' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span class="font-medium text-xs">History Peminjam</span>
                </a>
                @endcan
                @endcanany

                @canany(['user-management', 'role-akses'])
                <p class="text-[10px] font-bold text-gray-400 uppercase px-4 py-2 mt-4 tracking-widest">Pengaturan</p>
                
                @can('user-management')
                <a href="{{ route('users.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('users.*') ? 'text-primary bg-blue-50 dark:bg-blue-900/20 border-r-4 border-primary' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span class="font-medium">Manajemen User</span>
                </a>
                @endcan

                @can('role-akses')
                <a href="{{ route('roles.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('roles.*') ? 'text-primary bg-blue-50 dark:bg-blue-900/20 border-r-4 border-primary' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    <span class="font-medium">Role & Akses</span>
                </a>
                @endcan

                @can('informasi')
                <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('settings.*') ? 'text-primary bg-blue-50 dark:bg-blue-900/20 border-r-4 border-primary' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">Informasi Instansi</span>
                </a>
                @endcan
                @endcanany

                <a href="{{ route('manual.book') }}" target="_blank" 
                class="flex items-center space-x-3 py-2 px-4 rounded hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('manual.book') ? 'text-primary bg-blue-50 dark:bg-blue-900/20 border-r-4 border-primary' : 'text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="font-medium">Manual Book</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-16 bg-white dark:bg-darkCard border-b dark:border-gray-700 flex items-center justify-between px-4 md:px-8 z-30 shadow-sm">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2"></path></svg>
                </button>

                <div class="flex items-center space-x-4 ml-auto">
                    <!-- <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition relative shadow-inner">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2"></path></svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full border-2 border-white dark:border-darkCard"></span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 mt-3 w-80 bg-white dark:bg-darkCard border dark:border-gray-700 rounded-xl shadow-2xl z-50 overflow-hidden">
                            
                            <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-700 dark:text-gray-300">Notifikasi</h3>
                                <button class="text-[10px] font-bold text-blue-600 hover:underline">Mark all read</button>
                            </div>

                            <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    @php
                                        $type = $notification->data['type'] ?? 'internal';
                                        $dotColor = $type == 'eksternal' ? 'bg-blue-500' : ($type == 'internal' ? 'bg-green-500' : 'bg-red-500');
                                    @endphp
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="block p-4 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition relative group">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1.5 h-2 w-2 rounded-full {{ $dotColor }} shrink-0 shadow-sm group-hover:scale-125 transition-transform"></div>
                                            
                                            <div class="space-y-1">
                                                <p class="text-[11px] font-bold text-gray-800 dark:text-gray-200 leading-tight">
                                                    {{ $notification->data['title'] }}
                                                </p>
                                                <p class="text-[10px] text-gray-500 dark:text-gray-400 leading-snug">
                                                    {{ $notification->data['message'] }}
                                                </p>
                                                <p class="text-[9px] text-gray-400 font-medium uppercase tracking-tighter">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-10 text-center">
                                        <p class="text-xs text-gray-400">Belum ada notifikasi baru</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div> -->

                    <button @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode)" class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition shadow-inner">
                        <template x-if="!darkMode">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" stroke-width="2"></path></svg>
                        </template>
                        <template x-if="darkMode">
                            <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" stroke-width="2"></path></svg>
                        </template>
                    </button>

                    <div class="flex items-center space-x-3 border-l pl-4 dark:border-gray-700 h-8">
                        <div class="text-right hidden sm:block leading-tight">
                            <p class="text-xs font-bold">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-500 uppercase font-medium">{{ auth()->user()->getRoleNames()->first() }}</p>
                        </div>
                        
                        <a href="{{ route('profile.edit') }}" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full transition tooltip" title="Ubah Profil">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition" title="Keluar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-[#f8f9fa] dark:bg-darkBg">
                <div class="p-4 md:p-6 min-h-[calc(100vh-120px)]">
                    @yield('content')
                </div>

                <footer class="h-12 bg-white dark:bg-darkCard border-t dark:border-gray-700 flex items-center justify-between px-6 md:px-8 text-[11px] text-gray-500 shrink-0">
                    <div>2026 &copy; <span class="font-bold text-primary italic">AssetHub</span> - KPU Kabupaten Pasuruan</div>
                    <div class="hidden sm:block uppercase font-bold text-[10px] space-x-4 tracking-wider">
                        <a href="#" target="_blank" class="hover:text-primary transition">Feedback</a>
                        <a href="{{ route('manual.book') }}" target="_blank" class="hover:text-primary transition">Documentation</a>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>