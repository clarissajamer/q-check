<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Q-CHECK')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-gray-50">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col md:flex-row">
        
        @auth
        <!-- Mobile Header -->
        <div class="md:hidden flex items-center justify-between bg-primary-700 text-white px-4 py-3 shadow-md">
            <span class="text-xl font-bold tracking-tight">Q-CHECK</span>
            <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-lg md:relative md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col"
        >
            <div class="p-6 border-b border-gray-100 flex items-center justify-center">
                <h1 class="text-2xl font-bold text-primary-700 tracking-wider">Q-CHECK</h1>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-2">
                
                @if(auth()->user()->role === 'admin' || request()->is('admin*'))
                    <div class="px-2 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>

                    <!-- User Management Dropdown -->
                    <div x-data="{ expanded: {{ request()->routeIs('admin.users*') ? 'true' : 'false' }} }">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-primary-600 focus:outline-none transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span>Manajemen User</span>
                            </div>
                            <svg :class="{'rotate-180': expanded}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="expanded" x-collapse style="display: none;" class="pl-12 pr-2 space-y-1 mt-1">
                             <a href="{{ route('admin.users.index', 'admin') }}" class="block px-3 py-2 rounded-md {{ request()->is('admin/users/admin*') ? 'text-primary-600 font-medium bg-primary-50' : 'text-sm text-gray-500 hover:text-primary-600 hover:bg-gray-50' }}">
                                Data Admin
                            </a>
                             <a href="{{ route('admin.users.index', 'guru') }}" class="block px-3 py-2 rounded-md {{ request()->is('admin/users/guru*') ? 'text-primary-600 font-medium bg-primary-50' : 'text-sm text-gray-500 hover:text-primary-600 hover:bg-gray-50' }}">
                                Data Guru
                            </a>
                             <a href="{{ route('admin.users.index', 'siswa') }}" class="block px-3 py-2 rounded-md {{ request()->is('admin/users/siswa*') ? 'text-primary-600 font-medium bg-primary-50' : 'text-sm text-gray-500 hover:text-primary-600 hover:bg-gray-50' }}">
                                Data Siswa
                            </a>
                        </div>
                    </div>
                    <div x-data="{ expanded: {{ (request()->routeIs('admin.eskul*') || request()->routeIs('admin.jadwal-eskul*') || request()->routeIs('admin.kategori-eskul*')) ? 'true' : 'false' }} }">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-primary-600 focus:outline-none transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span>Kegiatan</span>
                            </div>
                            <svg :class="{'rotate-180': expanded}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="expanded" x-collapse style="display: none;" class="pl-12 pr-2 space-y-1 mt-1">
                             <a href="{{ route('admin.eskul.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('admin.eskul*') ? 'text-primary-600 font-medium bg-primary-50' : 'text-sm text-gray-500 hover:text-primary-600 hover:bg-gray-50' }}">
                                Data Eskul
                            </a>
                             <a href="{{ route('admin.kategori-eskul.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('admin.kategori-eskul*') ? 'text-primary-600 font-medium bg-primary-50' : 'text-sm text-gray-500 hover:text-primary-600 hover:bg-gray-50' }}">
                                Kategori Eskul
                            </a>
                             <a href="{{ route('admin.jadwal-eskul.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('admin.jadwal-eskul*') ? 'text-primary-600 font-medium bg-primary-50' : 'text-sm text-gray-500 hover:text-primary-600 hover:bg-gray-50' }}">
                                Jadwal Eskul
                            </a>
                        </div>
                    </div>

                    <!-- Tahun Ajaran Link -->
                    <a href="{{ route('admin.tahun-ajaran.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.tahun-ajaran*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                         Tahun Ajaran
                    </a>
                @endif

                @if(auth()->user()->role === 'guru' || request()->is('guru*'))
                    <div class="px-2 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Guru</div>
                    <a href="{{ route('guru.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('guru.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Dashboard
                    </a>
                @endif

                @if(auth()->user()->role === 'siswa' || request()->is('siswa*'))
                    <div class="px-2 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Siswa</div>
                    <a href="{{ route('siswa.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('siswa.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-primary-600' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                @endif
                
            </nav>

            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="flex w-full items-center px-4 py-3 text-sm font-medium text-red-600 transition-colors duration-200 rounded-lg hover:bg-red-50">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 h-screen">
            <div class="p-6 md:p-10 container mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
                        <p class="font-medium">Success</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
                        <p class="font-medium">Error</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
        
        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black opacity-50 md:hidden" style="display: none;"></div>

    </div>

</body>
</html>
