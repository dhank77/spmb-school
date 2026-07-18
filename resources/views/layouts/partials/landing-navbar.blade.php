<!-- TopNavBar -->
<header class="sticky top-0 z-50 w-full bg-surface-container-lowest border-b border-outline-variant shadow-sm">
    <div class="flex justify-between items-center w-full px-gutter max-w-7xl mx-auto h-20">
        <div class="flex items-center gap-xs">
            <a href="{{ route('home') }}" class="font-headline-md text-headline-md font-bold text-primary">Hitech School SPMB</a>
        </div>
        <nav class="hidden md:flex items-center gap-lg">
            <a class="text-primary font-bold border-b-2 border-primary pb-1 font-label-md text-label-md transition-all duration-200 active:scale-95" href="#admission-waves">Admission Info</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors font-label-md text-label-md" href="#stats">Program Finder</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors font-label-md text-label-md" href="#faq">Contact Us</a>
        </nav>
        <div class="flex items-center gap-sm">
            @auth
                <a href="{{ route('dashboard') }}" class="hidden sm:block text-primary font-label-md text-label-md hover:underline">Dashboard</a>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="hidden sm:block text-primary font-label-md text-label-md hover:underline">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-primary text-on-primary px-md py-xs rounded-lg font-label-md text-label-md transition-all duration-200 active:scale-95 hover:bg-primary-container">Register Now</a>
                @endif
            @endauth
        </div>
    </div>
</header>
