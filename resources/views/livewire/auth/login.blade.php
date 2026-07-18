<x-layouts.split :title="__('Log in')">
    <div class="w-full max-w-[28rem]">
        <!-- Mobile Branding -->
        <div class="flex md:hidden items-center gap-3 mb-10">
            <div class="bg-primary p-2 rounded-xl">
                <span class="material-symbols-outlined text-white text-2xl">school</span>
            </div>
            <span class="font-headline-sm text-headline-sm font-bold text-primary">Hitech School SPMB</span>
        </div>

        <div class="mb-10">
            <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Welcome Back</h2>
            <p class="text-on-surface-variant font-body-md text-body-md">Log in to manage your admission application and track your progress.</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif
        
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <div class="font-medium text-red-700">{{ __('Whoops! Something went wrong.') }}</div>
                <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.store') }}" class="space-y-6" method="POST">
            @csrf
            
            <x-passkey-verify />

            <!-- Username/NISN -->
            <div class="space-y-2">
                <label class="font-label-md text-label-md text-on-surface-variant block" for="email">Username or NISN</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">person</span>
                    <input class="w-full pl-10 pr-4 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all duration-200" id="email" name="email" placeholder="Enter your email" required autofocus type="email" value="{{ old('email') }}"/>
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label class="font-label-md text-label-md text-on-surface-variant block" for="password">Password</label>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                    <input class="w-full pl-10 pr-12 py-3 bg-white border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all duration-200" id="password" name="password" placeholder="••••••••" required type="password"/>
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors focus:outline-none" onclick="togglePasswordVisibility()" type="button">
                        <span class="material-symbols-outlined text-[20px]" id="password-toggle-icon">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Options -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary transition-all" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}/>
                    <span class="font-label-md text-label-md text-on-surface-variant group-hover:text-on-surface">Remember me</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a class="font-label-md text-label-md text-primary hover:underline transition-all" href="{{ route('password.request') }}" wire:navigate>Forgot Password?</a>
                @endif
            </div>

            <!-- Submit CTA -->
            <button class="w-full bg-primary-container text-white font-label-md text-label-md py-4 rounded-lg shadow-sm hover:shadow-md hover:bg-primary transition-all duration-200 active:scale-[0.98] flex items-center justify-center gap-2" type="submit" data-test="login-button">
                Sign In
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </form>

        <!-- Registration Divider -->
        <div class="relative my-10">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-outline-variant"></div>
            </div>
            <div class="relative flex justify-center text-label-sm uppercase tracking-wider">
                <span class="bg-surface px-4 text-on-surface-variant">New Applicant?</span>
            </div>
        </div>

        <!-- Registration CTA -->
        <div class="text-center">
            <p class="font-body-md text-body-md text-on-surface-variant mb-6">Start your journey with Hitech School today.</p>
            <a class="inline-flex w-full items-center justify-center gap-2 px-6 py-4 border border-primary text-primary font-label-md text-label-md rounded-lg hover:bg-primary/5 transition-all duration-200" href="{{ route('register') }}" wire:navigate>
                Create Admission Account
                <span class="material-symbols-outlined text-[18px]">person_add</span>
            </a>
        </div>

        <!-- Footer Links -->
        <div class="mt-12 pt-8 border-t border-outline-variant/30 flex flex-wrap justify-center gap-x-6 gap-y-2">
            <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Privacy Policy</a>
            <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Terms of Service</a>
            <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Support Center</a>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = 'visibility';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    const icon = input.parentElement.querySelector('.material-symbols-outlined');
                    if (icon) icon.style.color = '#276f27';
                });
                input.addEventListener('blur', () => {
                    const icon = input.parentElement.querySelector('.material-symbols-outlined');
                    if (icon) icon.style.color = '';
                });
            });
        });
    </script>
    @endpush
</x-layouts.split>
