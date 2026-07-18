<x-layouts.landing>
    @section('title', 'Hitech School SPMB | Portal Penerimaan')
    @section('meta_description', 'Bergabunglah dengan ekosistem elit inovator, pengembang, dan pemimpin masa depan. Kurikulum tingkat lanjut, mentor industri, dan kampus teknologi terkini.')

    <!-- Hero Section -->
    <section class="relative overflow-hidden hero-gradient py-xl lg:py-48">
        <div class="max-w-7xl mx-auto px-gutter grid lg:grid-cols-2 items-center gap-xl">
            <div class="z-10">
                <span class="inline-block px-sm py-base bg-secondary-container text-on-secondary-container rounded-full font-label-md text-label-md mb-md">
                    Pendaftaran Tahun Ajaran 2024/2025
                </span>
                <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary mb-md leading-tight">
                    Bentuk Masa Depan Anda di <span class="text-secondary">Pusat Digital Indonesia</span>
                </h1>
                <p class="text-body-lg font-body-lg text-on-surface-variant mb-lg">
                    Bergabunglah dengan ekosistem elit inovator, pengembang, dan pemimpin masa depan. Kurikulum tingkat lanjut, mentor industri, dan kampus teknologi terkini.
                </p>
                <div class="flex flex-wrap gap-md">
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-primary text-on-primary px-xl py-md rounded-xl font-headline-sm text-headline-sm shadow-lg hover:shadow-xl transition-all active:scale-95">
                            Daftar Sekarang
                        </a>
                    @endif
                    <button class="border-2 border-primary text-primary px-xl py-md rounded-xl font-headline-sm text-headline-sm hover:bg-primary-fixed transition-all">
                        Tur Virtual
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
    <section id="admission-waves" class="py-xl bg-surface">
        <div class="max-w-7xl mx-auto px-gutter">
            <div class="text-center mb-xl">
                <h2 class="font-headline-md text-headline-md text-primary mb-xs">Gelombang Penerimaan Aktif</h2>
                <p class="text-on-surface-variant">Amankan tempat Anda lebih awal untuk mendapatkan beasiswa eksklusif dan keuntungan.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-lg">
                @forelse($waves as $wave)
                    <x-wave-card :wave="$wave" />
                @empty
                    <div class="col-span-3 text-center py-12 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-4 block">event_busy</span>
                        <p>Tidak ada gelombang penerimaan tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Stats Bento Grid -->
    <section id="stats" class="py-xl bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-gutter">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-md">
                <div class="md:col-span-2 bg-primary p-lg rounded-3xl text-on-primary flex flex-col justify-between">
                    <div>
                        <span class="material-symbols-outlined text-4xl mb-md">rocket_launch</span>
                        <h2 class="font-headline-md text-headline-md mb-sm">98% Tingkat Keberhasilan Lulusan</h2>
                        <p class="text-primary-fixed opacity-90">Siswa kami langsung beralih ke karir teknologi berpertumbuhan tinggi atau universitas terkemuka di seluruh dunia.</p>
                    </div>
                    <div class="mt-lg flex -space-x-4">
                        <img class="w-12 h-12 rounded-full border-2 border-white object-cover" alt="Student portrait 1" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-vPnoF7ymi8cok63GC7_Map29xTBdah-45NIAT8Ykmbm267lUO6HWd70SZKDPmUj7M9_hJwRvDgz1EQAaPTAtt9qwMHCjsvoV-0cg6vlK0RZTjyUjvc8yhZKWDwqojT87KkDx2Vsxw4tLKGqR36Dx6AWC_fzaNNVZK6ZQmww0ufQRVYyXeufQyY-2SPGVAK6vCjI6iUsNp5SHzYaFXsSgbEH0vR-xYo7DS5bl8zPDQVqULuT6ZyHI" />
                        <img class="w-12 h-12 rounded-full border-2 border-white object-cover" alt="Student portrait 2" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTDVSqWwpmMbV8b8nTHBiSIfzqMKq32yeXZNfOPWPv4JB3Vttp929CSjHm8Ld0gNFm0EVR5PYvEmQ0cPNR5sB4bpuHK4pvbjP16roGuiVEm_af_4Oe3_DYYBKE5EPV3rNn9wd9egN7yqR385cX206tJjNqlOWluk-MTulZ0YSh27YTlsgr_Q_JkMvEtiTGni9_Z8lrFnWuyToCPUpKbx3JEGv83MJUtfIuq0-wlBLpjFflhGVVK7LF" />
                        <img class="w-12 h-12 rounded-full border-2 border-white object-cover" alt="Student portrait 3" src="https://lh3.googleusercontent.com/aida-public/AB6AXuABG2TSOop69cZjM1gia0SX96TeEhgIEFbLXL7GKt29GlXRfHs9bwHCEp55N5qqClhc3Amao0NG7jEjPeQJMQmXrmQ9pLxKbh8vhx7Wo_J0FytZ3qoHo5aj8rplX5Qt7W9oMdnxZJvWhzBojeAKtU-gHiNI6blw_VHRU4Mjj6SNHpvENxoN1LcPv6OqnpJsV3mns5-lv28HMVg8_u5uL8yODlt6gproFmHIC6CBS78fVCGGnULBHfMF" />
                        <div class="w-12 h-12 rounded-full border-2 border-white bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-xs">+5k</div>
                    </div>
                </div>
                <div class="bg-white p-lg rounded-3xl shadow-sm border border-outline-variant">
                    <span class="material-symbols-outlined text-secondary text-4xl mb-md">laptop_mac</span>
                    <h4 class="text-4xl font-bold text-primary">50+</h4>
                    <p class="text-on-surface-variant">Mitra Industri Global</p>
                </div>
                <div class="bg-white p-lg rounded-3xl shadow-sm border border-outline-variant">
                    <span class="material-symbols-outlined text-secondary text-4xl mb-md">emoji_events</span>
                    <h4 class="text-4xl font-bold text-primary">12</h4>
                    <p class="text-on-surface-variant">Penghargaan Teknologi Nasional</p>
                </div>
                <div class="md:col-span-4 glass-card p-lg rounded-3xl border border-white flex flex-col md:flex-row items-center gap-lg justify-between">
                    <div class="flex items-center gap-md">
                        <div class="w-16 h-16 rounded-full bg-secondary-container flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-3xl">verified</span>
                        </div>
                        <div>
                            <h4 class="font-headline-sm text-headline-sm text-on-surface">Sertifikasi ISO 9001:2015</h4>
                            <p class="text-on-surface-variant">Komitmen terhadap standar internasional dalam manajemen pendidikan.</p>
                        </div>
                    </div>
                    <button class="bg-secondary text-on-secondary px-lg py-sm rounded-full font-bold shadow-md">Unduh Brosur</button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-xl max-w-7xl mx-auto px-gutter">
        <div class="flex flex-col lg:flex-row gap-xl">
            <div class="lg:w-1/3">
                <h2 class="font-display-lg text-display-lg-mobile text-primary mb-md">Punya Pertanyaan?</h2>
                <p class="text-on-surface-variant mb-lg">Kami di sini untuk membantu Anda menavigasi perjalanan Anda. Jika Anda tidak menemukan yang Anda cari, jangan ragu untuk menghubungi tim dukungan kami.</p>
                <div class="p-md bg-primary-fixed/20 rounded-2xl border border-primary-fixed/30">
                    <h4 class="font-bold text-primary mb-xs">Hotline Penerimaan</h4>
                    <p class="text-body-md text-on-surface-variant mb-md">(021) 555-0123</p>
                    <button class="flex items-center gap-xs text-primary font-bold">
                        <span class="material-symbols-outlined">chat</span>
                        Dukungan WhatsApp
                    </button>
                </div>
            </div>
            <div class="lg:w-2/3 space-y-md">
                @forelse($faqs as $faq)
                    <x-faq-item :question="$faq->question" :answer="$faq->answer" />
                @empty
                    <div class="text-center py-12 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-4 block">help_outline</span>
                        <p>Tidak ada FAQ tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-xl px-gutter max-w-7xl mx-auto mb-xl">
        <div class="bg-primary-container rounded-[2rem] p-lg md:p-xl text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
            </div>
            <h2 class="font-display-lg text-display-lg-mobile md:text-display-lg text-on-primary-container mb-md relative z-10">Siap Memulai Perjalanan Anda?</h2>
            <p class="text-primary-fixed opacity-90 text-body-lg max-w-2xl mx-auto mb-xl relative z-10">Jangan tunggu gelombang berikutnya. Bergabunglah dengan ratusan siswa yang telah mengamankan tempat mereka di generasi pemimpin berikutnya.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-md relative z-10">
                @if(Route::has('register'))
                     <a href="{{ route('register') }}" class="bg-secondary-container text-on-secondary-container px-xl py-md rounded-xl font-bold text-headline-sm hover:bg-secondary-fixed transition-all shadow-xl">Daftar Sekarang</a>
                @endif
                <button class="bg-white/10 text-white backdrop-blur-sm border border-white/20 px-xl py-md rounded-xl font-bold text-headline-sm hover:bg-white/20 transition-all">Jadwalkan Panggilan</button>
            </div>
        </div>
    </section>
</x-layouts.landing>
