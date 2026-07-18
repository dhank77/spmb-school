<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? 'Student Registration | Hitech SPMB' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:100,200,300,400,500,600,700,800,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .step-active { background-color: var(--color-primary-container); color: white; }
        .step-inactive { background-color: var(--color-surface-container); color: var(--color-on-surface-variant); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--color-outline-variant); border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body class="text-on-surface bg-background font-body-md antialiased">
    <!-- Top Navigation Bar -->
    <header class="bg-surface border-b border-outline-variant shadow-sm sticky top-0 z-50">
        <div class="flex justify-between items-center px-6 lg:px-12 w-full max-w-[1280px] mx-auto h-16">
            <div class="font-headline-sm text-headline-sm font-bold text-primary">
                Hitech SPMB
            </div>
            <nav class="hidden md:flex gap-8 items-center">
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Admission</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Programs</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">FAQ</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Contact</a>
            </nav>
            <div class="flex items-center gap-4">
                <span class="text-label-sm text-outline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">verified_user</span>
                    Secure Portal
                </span>
                <a href="{{ route('login') }}" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-label-md hover:opacity-90 transition-all">Sign In</a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-12">
        {{ $slot }}
    </main>

    <footer class="bg-surface-container-lowest border-t border-outline-variant mt-20">
        <div class="w-full py-12 px-6 lg:px-12 flex flex-col md:flex-row justify-between items-center max-w-[1280px] mx-auto gap-8">
            <div class="flex flex-col items-center md:items-start gap-4">
                <div class="font-label-md text-label-md font-bold text-primary">Hitech School SPMB</div>
                <p class="text-label-sm text-on-surface-variant">© 2024 Hitech School Academic Admission System</p>
            </div>
            <nav class="flex gap-8">
                <a class="text-label-sm font-label-sm text-on-surface-variant hover:text-primary hover:underline transition-colors" href="#">Privacy Policy</a>
                <a class="text-label-sm font-label-sm text-on-surface-variant hover:text-primary hover:underline transition-colors" href="#">Terms of Service</a>
                <a class="text-label-sm font-label-sm text-on-surface-variant hover:text-primary hover:underline transition-colors" href="#">Support</a>
            </nav>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>
