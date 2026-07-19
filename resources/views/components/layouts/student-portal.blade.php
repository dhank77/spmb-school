<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal Calon Murid Hitech School' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:100,200,300,400,500,600,700,800,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
      }
      .exam-card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .exam-card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0px 10px 30px rgba(39, 111, 39, 0.1);
      }
    </style>
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-surface text-on-surface font-body-md text-body-md overflow-x-hidden antialiased" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         x-transition.opacity 
         class="fixed inset-0 bg-black/50 z-40 lg:hidden" 
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- Sidebar Navigation -->
    <aside class="fixed left-0 top-0 h-full p-6 flex flex-col gap-2 bg-surface-container-low border-r border-outline-variant w-[280px] z-50 transition-transform duration-300 -translate-x-full"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex items-center justify-between mb-10 px-2">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-on-primary" data-icon="school">school</span>
                </div>
                <div>
                    <h1 class="font-headline-sm text-headline-sm font-black text-secondary">Hitech School</h1>
                    <p class="text-label-sm font-label-sm text-outline">Portal Murid</p>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden text-on-surface-variant hover:bg-surface-container p-1 rounded-md">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <nav class="flex-1 flex flex-col gap-2 overflow-y-auto">
            <a class="flex items-center gap-6 px-6 py-4 {{ request()->routeIs('dashboard') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-lg font-label-md text-label-md transition-transform duration-200 hover:translate-x-1" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span>Dashboard</span>
            </a>
            @can('student.active_exams')
            <a class="flex items-center gap-6 px-6 py-4 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-label-md text-label-md transition-transform duration-200 hover:translate-x-1" href="#">
                <span class="material-symbols-outlined" data-icon="assignment">assignment</span>
                <span>Ujian Aktif</span>
            </a>
            @endcan
            @can('student.exam_history')
            <a class="flex items-center gap-6 px-6 py-4 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-label-md text-label-md transition-transform duration-200 hover:translate-x-1" href="#">
                <span class="material-symbols-outlined" data-icon="history">history</span>
                <span>Riwayat Ujian</span>
            </a>
            @endcan
        </nav>
        
        <div class="mt-auto pt-6 border-t border-outline-variant">
            <button class="w-full flex items-center justify-center gap-4 bg-primary text-on-primary py-4 rounded-xl font-label-md text-label-md active:opacity-80 transition-opacity">
                <span class="material-symbols-outlined" data-icon="chat">chat</span>
                <span>Hubungi Pengawas</span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-6 px-6 py-4 mt-4 text-error hover:bg-error-container/20 rounded-lg font-label-md text-label-md transition-transform duration-200 hover:translate-x-1">
                    <span class="material-symbols-outlined" data-icon="logout">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="min-h-screen pb-20 lg:pb-0 transition-[margin] duration-300" :class="sidebarOpen ? 'lg:ml-[280px]' : 'lg:ml-0'">
        <!-- Top Navigation Bar -->
        <header class="sticky top-0 w-full z-30 flex justify-between items-center px-6 py-4 bg-surface/90 backdrop-blur border-b border-outline-variant shadow-sm h-16">
            <div class="flex items-center gap-6">
                <!-- Toggle button for sidebar -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 rounded-md hover:bg-surface-container-low text-on-surface transition-colors">
                    <span class="material-symbols-outlined" data-icon="menu">menu</span>
                </button>
                <h2 class="font-headline-sm text-headline-sm font-bold text-primary">Portal Calon Murid</h2>
            </div>
            
            <div class="hidden md:flex items-center gap-10">
                <nav class="flex gap-6">
                    <a class="font-headline-sm text-headline-sm {{ request()->routeIs('dashboard') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="font-headline-sm text-headline-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Jadwal</a>
                    <a class="font-headline-sm text-headline-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Hasil</a>
                </nav>
            </div>
            
            <div class="flex items-center gap-4">
                <button class="p-2 rounded-full hover:bg-surface-container-low transition-colors hidden sm:block">
                    <span class="material-symbols-outlined text-outline" data-icon="notifications">notifications</span>
                </button>
                <button class="p-2 rounded-full hover:bg-surface-container-low transition-colors hidden sm:block">
                    <span class="material-symbols-outlined text-outline" data-icon="help_outline">help_outline</span>
                </button>
                <div class="w-10 h-10 rounded-full bg-primary/10 overflow-hidden border border-outline-variant flex items-center justify-center text-primary font-bold">
                    {{ Auth::user()->initials() ?? 'US' }}
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-6 lg:p-10 max-w-[1280px] mx-auto">
            {{ $slot }}
        </div>
    </main>

    <!-- Mobile Bottom Navigation (Optional: Can keep this as a bottom bar for extremely small devices, or remove it entirely in favor of the drawer) -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full bg-surface border-t border-outline-variant flex justify-around py-4 z-20">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
            <span class="text-xs">Home</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-on-surface-variant">
            <span class="material-symbols-outlined" data-icon="assignment">assignment</span>
            <span class="text-xs">Ujian</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-on-surface-variant">
            <span class="material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
            <span class="text-xs">Jadwal</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-on-surface-variant">
            <span class="material-symbols-outlined" data-icon="person">person</span>
            <span class="text-xs">Profil</span>
        </a>
    </nav>
    
    @stack('scripts')
    @livewireScripts
</body>
</html>
