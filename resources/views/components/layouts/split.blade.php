<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? config('app.name', 'Hitech School SPMB') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:100,200,300,400,500,600,700,800,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .split-image-overlay {
            background: linear-gradient(rgba(5, 86, 16, 0.85), rgba(5, 86, 16, 0.7));
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-background min-h-screen flex items-center justify-center p-0 overflow-x-hidden font-body-md text-body-md antialiased">
    <div class="flex min-h-screen w-full flex-col md:flex-row">
        <!-- Left Section: Branding & Mission -->
        <div class="relative hidden md:flex md:w-1/2 lg:w-[60%] flex-col justify-between p-16 text-white overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAGvq8N51zDtLYCF54udInt4MLSgDcFSBFqT1DcKhFyVA1W3mHxtOdwhxvjKEyTwrHvhM2h3AP4RNsTA9Z0F0aMw_X8RuQJL-vWa6OEMOcU-ZsRtGPo0slj7OR2_oSY5GZvdMZCeKaCQzX8naFiGG-r2lQJrkP49T9Kk8th-ahBrX-yJBqoq8Tf-mLXd41lyjly2_F_TSblSiZkVX8SXJSR8q7acY7ISXSKQUaP0stP29mp83u6WteC')">
            </div>
            <!-- Overlay -->
            <div class="absolute inset-0 z-10 split-image-overlay"></div>
            
            <!-- Content -->
            <div class="relative z-20">
                <div class="flex items-center gap-3">
                    <div class="bg-white p-2 rounded-xl">
                        <span class="material-symbols-outlined text-primary text-3xl">school</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm font-bold tracking-tight">Hitech School SPMB</span>
                </div>
            </div>
            
            <div class="relative z-20 max-w-[36rem]">
                <h1 class="font-display-lg text-display-lg mb-6 leading-tight">Empowering the next generation of <span class="text-secondary-fixed">digital leaders</span>.</h1>
                <p class="font-body-lg text-body-lg text-white/90">Our mission is to bridge the gap between traditional education and the evolving technological landscape, fostering innovation, integrity, and excellence in every student.</p>
            </div>
            
            <div class="relative z-20">
                <div class="flex gap-8 items-center text-label-sm font-label-sm uppercase tracking-widest opacity-80">
                    <span>Inquiry</span>
                    <span>•</span>
                    <span>Innovation</span>
                    <span>•</span>
                    <span>Impact</span>
                </div>
            </div>
        </div>
        
        <!-- Right Section: Login Form -->
        <div class="w-full md:w-1/2 lg:w-[40%] flex items-center justify-center p-8 md:p-16 bg-surface relative">
            {{ $slot }}
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
