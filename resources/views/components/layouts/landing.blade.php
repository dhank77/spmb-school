<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hitech School SPMB | Admission Portal')</title>
    <meta name="description" content="@yield('meta_description', 'Join Hitech School — an elite ecosystem of innovators, developers, and future leaders. Apply now for the upcoming academic year.')">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background text-on-background font-body-md antialiased selection:bg-primary-fixed selection:text-on-primary-fixed">
    <!-- Navbar -->
    @include('layouts.partials.landing-navbar')

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('layouts.partials.landing-footer')

    @stack('scripts')
</body>
</html>
