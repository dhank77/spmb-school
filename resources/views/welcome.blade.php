<x-layouts.landing>
    @section('title', 'Hitech School SPMB | Admission Portal')
    @section('meta_description', 'Join an elite ecosystem of innovators, developers, and future leaders. Advanced curriculum, industry mentors, and state-of-the-art tech campus.')

    <!-- Hero Section -->
    <section class="relative overflow-hidden hero-gradient py-[var(--spacing-xl)] lg:py-48">
        <div class="max-w-[var(--spacing-max-width-content)] mx-auto px-[var(--spacing-gutter)] grid lg:grid-cols-2 items-center gap-[var(--spacing-xl)]">
            <div class="z-10">
                <span class="inline-block px-[var(--spacing-sm)] py-[var(--spacing-base)] bg-secondary-container text-on-secondary-container rounded-full font-label-md text-label-md mb-[var(--spacing-md)]">
                    Enrollment Academic Year 2024/2025
                </span>
                <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary mb-[var(--spacing-md)] leading-tight">
                    Shape Your Future at <span class="text-secondary">Indonesia's Digital Hub</span>
                </h1>
                <p class="text-body-lg font-body-lg text-on-surface-variant mb-[var(--spacing-lg)] max-w-lg">
                    Join an elite ecosystem of innovators, developers, and future leaders. Advanced curriculum, industry mentors, and state-of-the-art tech campus.
                </p>
                <div class="flex flex-wrap gap-[var(--spacing-md)]">
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-primary text-on-primary px-[var(--spacing-xl)] py-[var(--spacing-md)] rounded-xl font-headline-sm text-headline-sm shadow-lg hover:shadow-xl transition-all active:scale-95">
                            Register Now
                        </a>
                    @endif
                    <button class="border-2 border-primary text-primary px-[var(--spacing-xl)] py-[var(--spacing-md)] rounded-xl font-headline-sm text-headline-sm hover:bg-primary-fixed transition-all">
                        Virtual Tour
                    </button>
                </div>
            </div>
            <div class="relative">
                <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl border-8 border-white/50">
                    <img
                        class="w-full aspect-video object-cover"
                        alt="A modern high-tech school building featuring glass walls, lush green campus gardens, and students working on laptops in an outdoor atrium"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPQ-awIf1-EPC93XVrHxh8PznxTpf45i--geFIaPG4pjs3P0dQNOjGmakwkJExh9dZ4Dxrffzr7vVfGjXyUsoGwdKbJaXNtggPFC9ukd2-sfLRli_ipJk5UnvR9b6BEsBHhmDtx-gUzeR-n44dVt64y0uxoFK5XCx0ukDiYqkcZKHqvhMNl-gLq2CtKXrsorFFCwyFFg_wuj5cPGKoSy0fFawIv4i5k3qBybXY1LT4eDMCJDtMXs1c"
                    />
                </div>
                <!-- Abstract Accents -->
                <div class="absolute -top-12 -right-12 w-64 h-64 bg-secondary-container/30 rounded-full blur-3xl -z-0"></div>
                <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-primary-fixed/40 rounded-full blur-3xl -z-0"></div>
            </div>
        </div>
    </section>

    <!-- Admission Waves Section -->
    <section id="admission-waves" class="py-[var(--spacing-xl)] bg-surface">
        <div class="max-w-[var(--spacing-max-width-content)] mx-auto px-[var(--spacing-gutter)]">
            <div class="text-center mb-[var(--spacing-xl)]">
                <h2 class="font-headline-md text-headline-md text-primary mb-[var(--spacing-xs)]">Active Admission Waves</h2>
                <p class="text-on-surface-variant">Secure your spot early to receive exclusive scholarships and benefits.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-[var(--spacing-lg)]">
                @forelse($waves as $wave)
                    <x-wave-card :wave="$wave" />
                @empty
                    <div class="col-span-3 text-center py-12 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-4 block">event_busy</span>
                        <p>No admission waves available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Stats Bento Grid -->
    <section id="stats" class="py-[var(--spacing-xl)] bg-surface-container-low">
        <div class="max-w-[var(--spacing-max-width-content)] mx-auto px-[var(--spacing-gutter)]">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-[var(--spacing-md)]">
                <div class="md:col-span-2 bg-primary p-[var(--spacing-lg)] rounded-3xl text-on-primary flex flex-col justify-between">
                    <div>
                        <span class="material-symbols-outlined text-4xl mb-[var(--spacing-md)]">rocket_launch</span>
                        <h2 class="font-headline-md text-headline-md mb-[var(--spacing-sm)]">98% Graduate Success Rate</h2>
                        <p class="text-primary-fixed opacity-90">Our students transition directly into high-growth tech careers or top-tier universities worldwide.</p>
                    </div>
                    <div class="mt-[var(--spacing-lg)] flex -space-x-4">
                        <img class="w-12 h-12 rounded-full border-2 border-white object-cover" alt="Student portrait 1" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-vPnoF7ymi8cok63GC7_Map29xTBdah-45NIAT8Ykmbm267lUO6HWd70SZKDPmUj7M9_hJwRvDgz1EQAaPTAtt9qwMHCjsvoV-0cg6vlK0RZTjyUjvc8yhZKWDwqojT87KkDx2Vsxw4tLKGqR36Dx6AWC_fzaNNVZK6ZQmww0ufQRVYyXeufQyY-2SPGVAK6vCjI6iUsNp5SHzYaFXsSgbEH0vR-xYo7DS5bl8zPDQVqULuT6ZyHI" />
                        <img class="w-12 h-12 rounded-full border-2 border-white object-cover" alt="Student portrait 2" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTDVSqWwpmMbV8b8nTHBiSIfzqMKq32yeXZNfOPWPv4JB3Vttp929CSjHm8Ld0gNFm0EVR5PYvEmQ0cPNR5sB4bpuHK4pvbjP16roGuiVEm_af_4Oe3_DYYBKE5EPV3rNn9wd9egN7yqR385cX206tJjNqlOWluk-MTulZ0YSh27YTlsgr_Q_JkMvEtiTGni9_Z8lrFnWuyToCPUpKbx3JEGv83MJUtfIuq0-wlBLpjFflhGVVK7LF" />
                        <img class="w-12 h-12 rounded-full border-2 border-white object-cover" alt="Student portrait 3" src="https://lh3.googleusercontent.com/aida-public/AB6AXuABG2TSOop69cZjM1gia0SX96TeEhgIEFbLXL7GKt29GlXRfHs9bwHCEp55N5qqClhc3Amao0NG7jEjPeQJMQmXrmQ9pLxKbh8vhx7Wo_J0FytZ3qoHo5aj8rplX5Qt7W9oMdnxZJvWhzBojeAKtU-gHiNI6blw_VHRU4Mjj6SNHpvENxoN1LcPv6OqnpJsV3mns5-lv28HMVg8_u5uL8yODlt6gproFmHIC6CBS78fVCGGnULBHfMF" />
                        <div class="w-12 h-12 rounded-full border-2 border-white bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-xs">+5k</div>
                    </div>
                </div>
                <div class="bg-white p-[var(--spacing-lg)] rounded-3xl shadow-sm border border-outline-variant">
                    <span class="material-symbols-outlined text-secondary text-4xl mb-[var(--spacing-md)]">laptop_mac</span>
                    <h4 class="text-4xl font-bold text-primary">50+</h4>
                    <p class="text-on-surface-variant">Global Industry Partners</p>
                </div>
                <div class="bg-white p-[var(--spacing-lg)] rounded-3xl shadow-sm border border-outline-variant">
                    <span class="material-symbols-outlined text-secondary text-4xl mb-[var(--spacing-md)]">emoji_events</span>
                    <h4 class="text-4xl font-bold text-primary">12</h4>
                    <p class="text-on-surface-variant">National Tech Awards</p>
                </div>
                <div class="md:col-span-4 glass-card p-[var(--spacing-lg)] rounded-3xl border border-white flex flex-col md:flex-row items-center gap-[var(--spacing-lg)] justify-between">
                    <div class="flex items-center gap-[var(--spacing-md)]">
                        <div class="w-16 h-16 rounded-full bg-secondary-container flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-3xl">verified</span>
                        </div>
                        <div>
                            <h4 class="font-headline-sm text-headline-sm text-on-surface">ISO 9001:2015 Certified</h4>
                            <p class="text-on-surface-variant">Commitment to international standards in educational management.</p>
                        </div>
                    </div>
                    <button class="bg-secondary text-on-secondary px-[var(--spacing-lg)] py-[var(--spacing-sm)] rounded-full font-bold shadow-md">Download Brochure</button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-[var(--spacing-xl)] max-w-[var(--spacing-max-width-content)] mx-auto px-[var(--spacing-gutter)]">
        <div class="flex flex-col lg:flex-row gap-[var(--spacing-xl)]">
            <div class="lg:w-1/3">
                <h2 class="font-display-lg text-display-lg-mobile text-primary mb-[var(--spacing-md)]">Got Questions?</h2>
                <p class="text-on-surface-variant mb-[var(--spacing-lg)]">We're here to help you navigate your journey. If you can't find what you're looking for, feel free to contact our support team.</p>
                <div class="p-[var(--spacing-md)] bg-primary-fixed/20 rounded-2xl border border-primary-fixed/30">
                    <h4 class="font-bold text-primary mb-[var(--spacing-xs)]">Admissions Hotline</h4>
                    <p class="text-body-md text-on-surface-variant mb-[var(--spacing-md)]">(021) 555-0123</p>
                    <button class="flex items-center gap-[var(--spacing-xs)] text-primary font-bold">
                        <span class="material-symbols-outlined">chat</span>
                        WhatsApp Support
                    </button>
                </div>
            </div>
            <div class="lg:w-2/3 space-y-[var(--spacing-md)]">
                @forelse($faqs as $faq)
                    <x-faq-item :question="$faq->question" :answer="$faq->answer" />
                @empty
                    <div class="text-center py-12 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-4 block">help_outline</span>
                        <p>No FAQs available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-[var(--spacing-xl)] px-[var(--spacing-gutter)] max-w-[var(--spacing-max-width-content)] mx-auto mb-[var(--spacing-xl)]">
        <div class="bg-primary-container rounded-[2rem] p-[var(--spacing-lg)] md:p-[var(--spacing-xl)] text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
            </div>
            <h2 class="font-display-lg text-display-lg-mobile md:text-display-lg text-on-primary-container mb-[var(--spacing-md)] relative z-10">Ready to Begin Your Journey?</h2>
            <p class="text-primary-fixed opacity-90 text-body-lg max-w-2xl mx-auto mb-[var(--spacing-xl)] relative z-10">Don't wait for the next wave. Join hundreds of students who have already secured their place in the next generation of leaders.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-[var(--spacing-md)] relative z-10">
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-secondary-container text-on-secondary-container px-[var(--spacing-xl)] py-[var(--spacing-md)] rounded-xl font-bold text-headline-sm hover:bg-secondary-fixed transition-all shadow-xl">Apply Now</a>
                @endif
                <button class="bg-white/10 text-white backdrop-blur-sm border border-white/20 px-[var(--spacing-xl)] py-[var(--spacing-md)] rounded-xl font-bold text-headline-sm hover:bg-white/20 transition-all">Schedule a Call</button>
            </div>
        </div>
    </section>
</x-layouts.landing>
