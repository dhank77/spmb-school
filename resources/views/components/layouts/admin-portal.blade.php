<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Portal - Hitech School' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:100,200,300,400,500,600,700,800,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .kanban-column { min-width: 320px; flex: 1; }
      .kanban-card { transition: transform 0.2s, box-shadow 0.2s; }
      .kanban-card:hover { transform: translateY(-2px); box-shadow: 0px 10px 30px rgba(39, 111, 39, 0.1); }
      .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
      .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
      .custom-scrollbar::-webkit-scrollbar-thumb { background: #c0c9ba; border-radius: 10px; }
    </style>
    @livewireStyles
    @fluxAppearance
    @stack('styles')
</head>
<body class="bg-background text-on-surface font-body-md text-body-md overflow-hidden antialiased" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- Admin Sidebar Navigation -->
    <aside class="fixed left-0 top-0 h-full py-md px-sm flex flex-col overflow-y-auto bg-surface-container-low border-r border-outline-variant w-[280px] z-50 transition-transform duration-300 -translate-x-full"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="mb-lg px-xs flex items-center justify-between">
            <div>
                <h1 class="font-headline-sm text-headline-sm font-black text-primary mb-1">Admin Portal</h1>
                <p class="font-label-md text-label-md text-on-surface-variant">Academic Year {{ date('Y') }}/{{ date('Y') + 1 }}</p>
            </div>
            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden text-on-surface-variant hover:bg-surface-container p-1 rounded-md">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <nav class="flex-grow space-y-1">
            <a class="flex items-center gap-sm p-sm {{ request()->routeIs('admin.dashboard') ? 'bg-secondary-container text-on-secondary-container rounded-lg font-bold' : 'text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg' }} font-label-md text-label-md" href="{{ route('admin.pipeline') }}">
                <span class="material-symbols-outlined text-primary">dashboard</span>
                <span>Dashboard</span>
            </a>
            @can('admissions.view')
            <a class="flex items-center gap-sm p-sm {{ request()->routeIs('admin.pipeline') ? 'bg-secondary-container text-on-secondary-container rounded-lg font-bold' : 'text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg' }} font-label-md text-label-md" href="{{ route('admin.pipeline') }}">
                <span class="material-symbols-outlined" @if(request()->routeIs('admin.pipeline')) style="font-variation-settings: 'FILL' 1;" @endif>account_tree</span>
                <span>Application Pipeline</span>
            </a>
            @endcan
            @canany(['cbt.create', 'cbt.monitor', 'cbt.grade'])
            <a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg font-label-md text-label-md group" href="#">
                <span class="material-symbols-outlined text-primary">quiz</span>
                <span>CBT Management</span>
            </a>
            @endcanany
            @can('cbt.grade')
            <a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg font-label-md text-label-md group" href="#">
                <span class="material-symbols-outlined text-primary">grade</span>
                <span>Scoring & Grading</span>
            </a>
            @endcan
            @canany(['finance.invoice', 'finance.reports', 'finance.discount'])
            <a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg font-label-md text-label-md group" href="#">
                <span class="material-symbols-outlined text-primary">payments</span>
                <span>Finance Reports</span>
            </a>
            @endcanany
            @can('users.manage_roles')
            <a class="flex items-center gap-sm p-sm {{ request()->routeIs('admin.roles') ? 'bg-secondary-container text-on-secondary-container rounded-lg font-bold' : 'text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg' }} font-label-md text-label-md group" href="{{ route('admin.roles') }}">
                <span class="material-symbols-outlined text-primary" @if(request()->routeIs('admin.roles')) style="font-variation-settings: 'FILL' 1;" @endif>settings</span>
                <span>Settings</span>
            </a>
            @endcan
        </nav>

        <div class="mt-auto border-t border-outline-variant pt-md">
            <a class="flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg font-label-md text-label-md" href="#">
                <span class="material-symbols-outlined text-primary">help</span>
                <span>Help Center</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-sm p-sm text-on-surface-variant hover:bg-surface-container-high transition-all rounded-lg font-label-md text-label-md">
                    <span class="material-symbols-outlined text-error">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="h-screen flex flex-col transition-[margin] duration-300 bg-surface-bright overflow-hidden"
          :class="sidebarOpen ? 'lg:ml-[280px]' : 'lg:ml-0'">
        <!-- Top Header Action Bar -->
        <header class="h-20 flex items-center justify-between px-gutter border-b border-outline-variant bg-surface-container-lowest flex-shrink-0">
            <div class="flex items-center gap-md">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 rounded-md hover:bg-surface-container-low text-on-surface transition-colors lg:hidden">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                {{ $header ?? '' }}
            </div>
            <div class="flex items-center gap-sm">
                {{ $actions ?? '' }}
                <div class="w-10 h-10 rounded-full border-2 border-primary-fixed bg-surface-container-high overflow-hidden cursor-pointer flex items-center justify-center text-primary font-bold text-label-md">
                    {{ auth()->user()->initials() }}
                </div>
            </div>
        </header>

        <!-- Page Content -->
        {{ $slot }}
    </main>

    @stack('scripts')
    @livewireScripts
    @fluxScripts
</body>
</html>
