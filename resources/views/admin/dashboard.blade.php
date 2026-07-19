<x-layouts.admin-portal :title="__('Dashboard')">
    <x-slot:header>
        <h2 class="font-headline-md text-headline-md text-on-surface">Dashboard</h2>
    </x-slot:header>

    <div class="p-6 lg:p-10 max-w-[1280px] mx-auto space-y-6">
        <!-- Hero Welcome Section -->
        <section class="relative overflow-hidden rounded-3xl bg-secondary px-6 py-10 md:px-16 text-on-secondary shadow-lg">
            <div class="relative z-10 grid md:grid-cols-2 items-center gap-10">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-1 bg-on-secondary-container text-secondary rounded-full mb-4">
                        <span class="material-symbols-outlined text-sm text-white" style="font-variation-settings: 'FILL' 1;">shield</span>
                        <span class="font-label-sm text-label-sm text-white">Sistem Administrasi</span>
                    </div>
                    <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="font-body-lg text-body-lg opacity-90">
                        Anda masuk sebagai administrator. Gunakan menu navigasi di sebelah kiri untuk mengelola sistem pendaftaran siswa baru Hitech School.
                    </p>
                </div>
            </div>
            <!-- Abstract Background Shapes -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-tertiary/30 rounded-full blur-3xl -ml-20 -mb-20"></div>
        </section>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Applicants Card -->
            <div class="p-6 rounded-3xl border border-outline-variant bg-surface-container-lowest flex items-center gap-6 shadow-sm">
                <div class="w-12 h-12 flex-shrink-0 bg-primary/15 text-primary rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">groups</span>
                </div>
                <div>
                    <h5 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Total Pendaftar</h5>
                    <p class="font-display-md text-headline-lg font-black text-on-surface mt-1">{{ $stats['total'] }}</p>
                </div>
            </div>

            <!-- Pending Verification Card -->
            <div class="p-6 rounded-3xl border border-outline-variant bg-surface-container-lowest flex items-center gap-6 shadow-sm">
                <div class="w-12 h-12 flex-shrink-0 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">pending_actions</span>
                </div>
                <div>
                    <h5 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Menunggu Verifikasi</h5>
                    <p class="font-display-md text-headline-lg font-black text-on-surface mt-1">{{ $stats['pending'] }}</p>
                </div>
            </div>

            <!-- Verified Card -->
            <div class="p-6 rounded-3xl border border-outline-variant bg-surface-container-lowest flex items-center gap-6 shadow-sm">
                <div class="w-12 h-12 flex-shrink-0 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">verified</span>
                </div>
                <div>
                    <h5 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Terverifikasi</h5>
                    <p class="font-display-md text-headline-lg font-black text-on-surface mt-1">{{ $stats['verified'] }}</p>
                </div>
            </div>

            <!-- Rejected Card -->
            <div class="p-6 rounded-3xl border border-outline-variant bg-surface-container-lowest flex items-center gap-6 shadow-sm">
                <div class="w-12 h-12 flex-shrink-0 bg-error/10 text-error rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">cancel</span>
                </div>
                <div>
                    <h5 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Ditolak</h5>
                    <p class="font-display-md text-headline-lg font-black text-on-surface mt-1">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Access Section -->
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Quick Actions -->
            <div class="lg:col-span-2 bg-surface-container-high border border-outline-variant p-6 rounded-3xl">
                <h4 class="font-headline-sm text-headline-sm mb-4">Akses Cepat Modul</h4>
                <div class="grid sm:grid-cols-2 gap-4">
                    @can('admissions.view')
                    <a href="{{ route('admin.pipeline') }}" class="p-4 bg-surface-container-lowest border border-outline-variant/60 rounded-2xl flex items-center gap-4 hover:border-primary transition-all">
                        <div class="p-2 bg-primary/10 rounded-xl text-primary">
                            <span class="material-symbols-outlined">account_tree</span>
                        </div>
                        <div>
                            <h5 class="font-label-md text-label-md font-bold">Application Pipeline</h5>
                            <p class="text-xs text-on-surface-variant">Kelola berkas calon siswa</p>
                        </div>
                    </a>
                    @endcan

                    @can('users.manage_roles')
                    <a href="{{ route('admin.roles') }}" class="p-4 bg-surface-container-lowest border border-outline-variant/60 rounded-2xl flex items-center gap-4 hover:border-primary transition-all">
                        <div class="p-2 bg-primary/10 rounded-xl text-primary">
                            <span class="material-symbols-outlined">settings</span>
                        </div>
                        <div>
                            <h5 class="font-label-md text-label-md font-bold">Settings & Roles</h5>
                            <p class="text-xs text-on-surface-variant">Hak akses & peran staf</p>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-surface-container-lowest border border-outline-variant p-6 rounded-3xl flex flex-col justify-between shadow-sm">
                <div>
                    <h4 class="font-label-md text-label-md font-bold mb-2">Informasi Sistem</h4>
                    <p class="text-on-surface-variant text-label-md mb-4">Aplikasi Penerimaan Siswa Baru aktif.</p>
                    <ul class="space-y-2 text-label-sm text-on-surface-variant">
                        <li class="flex justify-between"><span>Versi Laravel:</span> <span class="font-bold">v13</span></li>
                        <li class="flex justify-between"><span>Versi Livewire:</span> <span class="font-bold">v4</span></li>
                        <li class="flex justify-between"><span>Status Sinkronisasi:</span> <span class="text-primary font-bold">Terhubung</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-portal>
