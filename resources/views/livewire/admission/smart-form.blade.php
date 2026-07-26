<div>
    <!-- Application Header -->
    <div class="mb-10 text-center md:text-left flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="font-display-lg text-display-lg text-primary mb-2">Pendaftaran Siswa</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Lengkapi perjalanan pendaftaran Anda untuk bergabung dengan generasi pemimpin teknologi berikutnya di Hitech School.</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-surface-container-low border border-outline-variant rounded-full text-label-md text-secondary transition-opacity">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">cloud_done</span>
            <span>Draf baru saja disimpan</span>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
            <div class="font-medium text-red-700">Harap perbaiki kesalahan berikut untuk melanjutkan:</div>
            <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column: Stepper and Status -->
        <aside class="lg:col-span-4 flex flex-col gap-6">
            <div class="bg-white p-8 rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-slate-100 lg:sticky lg:top-28">
                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-6">Pelacak Kemajuan</h3>
                <div class="space-y-8 relative">
                    <!-- Progress Line -->
                    <div class="absolute left-[19px] top-4 bottom-4 w-0.5 bg-surface-container-highest -z-0"></div>
                    
                    <!-- Step 1: Personal Data -->
                    <div class="relative z-10 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $currentStep >= 1 ? 'step-active shadow-md' : 'step-inactive' }}">1</div>
                        <div>
                            <p class="font-label-md text-label-md {{ $currentStep >= 1 ? 'text-primary font-bold' : 'text-on-surface-variant' }}">Data Pribadi</p>
                            <p class="text-label-sm text-on-surface-variant">Identitas dan Info Kontak</p>
                        </div>
                    </div>
                    
                    <!-- Step 2: Academic Records -->
                    <div class="relative z-10 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $currentStep >= 2 ? 'step-active shadow-md' : 'step-inactive' }}">2</div>
                        <div>
                            <p class="font-label-md text-label-md {{ $currentStep >= 2 ? 'text-primary font-bold' : 'text-on-surface-variant' }}">Riwayat Akademik</p>
                            <p class="text-label-sm text-on-surface-variant">Riwayat Sekolah Sebelumnya</p>
                        </div>
                    </div>
                    
                    <!-- Step 3: Documents -->
                    <div class="relative z-10 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $currentStep >= 3 ? 'step-active shadow-md' : 'step-inactive' }}">3</div>
                        <div>
                            <p class="font-label-md text-label-md {{ $currentStep >= 3 ? 'text-primary font-bold' : 'text-on-surface-variant' }}">Dokumen</p>
                            <p class="text-label-sm text-on-surface-variant">Unggah Identitas & Ijazah</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 pt-8 border-t border-slate-100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-surface-container">
                            <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwPVudQG0pxF5OxOYuCv0r0Kb86wYGh3wDYoYehsO__qogoRNuPku5nsOr0TNdpPBWjn0O4V4t9Qq393iAx-KTkysmxud1NGR2ZvQrmwVkjoXz6Et_ncqyGRXJkoMFmdkzQKiXNy0SbtCq-CRdZeS1NmX4_hxjScFuoEzBB04SfPHlzjSI1CjzegOGnD8Jn7GPkQ96KBlzE5qL4H1bLbxX4uOl07B3VQYHWq_O3T2uNUzQT9ubDY7L" alt="Support" />
                        </div>
                        <div>
                            <p class="font-label-md text-label-md text-on-surface">Butuh bantuan?</p>
                            <p class="text-label-sm text-on-surface-variant">Hubungi Dukungan Pendaftaran</p>
                        </div>
                    </div>
                    <a href="https://wa.me/62882019679350" target="_blank" class="w-full border border-primary text-primary py-2.5 rounded-lg font-label-md hover:bg-primary-container hover:text-on-primary-container transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">chat_bubble</span>
                        Whatsapp Kami
                    </a>
                </div>
            </div>
        </aside>

        <!-- Right Column: Registration Form -->
        <section class="lg:col-span-8">
            <div class="bg-white p-8 md:p-12 rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-slate-100">
                <form class="space-y-8" wire:submit.prevent="{{ $currentStep === 3 ? 'submitForm' : 'nextStep' }}">
                    
                    @if ($currentStep === 1)
                        <!-- STEP 1: Personal Data -->
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <h2 class="font-headline-md text-headline-md text-on-surface">Identitas Pribadi</h2>
                            <p class="font-body-md text-body-md text-on-surface-variant">Pastikan semua informasi sesuai dengan kartu identitas resmi Anda.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="md:col-span-2">
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="name">Nama Lengkap (Sesuai Ijazah)</label>
                                <input wire:model="name" class="w-full px-4 py-3 border @error('name') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-300" id="name" placeholder="misal: Aditama Putra" type="text" />
                            </div>
                            
                            <!-- NISN -->
                            <div class="space-y-2">
                                <label class="block font-label-md text-label-md text-on-surface-variant" for="nisn">NISN (Nomor Induk Siswa Nasional)</label>
                                <input wire:model="nisn" class="w-full px-4 py-3 border @error('nisn') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-300" id="nisn" maxlength="10" placeholder="10 Digit" type="text" />
                            </div>
                            
                            <!-- NIK -->
                            <div class="space-y-2">
                                <label class="block font-label-md text-label-md text-on-surface-variant" for="nik">NIK (Nomor Induk Kependudukan)</label>
                                <input wire:model="nik" class="w-full px-4 py-3 border @error('nik') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-300" id="nik" maxlength="16" placeholder="16 Digit" type="text" />
                            </div>
                            
                            <!-- Place of Birth -->
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="birth_place">Tempat Lahir</label>
                                <input wire:model="birth_place" class="w-full px-4 py-3 border @error('birth_place') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-300" id="birth_place" placeholder="Kota" type="text" />
                            </div>
                            
                            <!-- Date of Birth -->
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="birth_date">Tanggal Lahir</label>
                                <input wire:model="birth_date" class="w-full px-4 py-3 border @error('birth_date') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all" id="birth_date" type="date" />
                            </div>
                            
                            <!-- Gender -->
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2">Jenis Kelamin</label>
                                <div class="flex gap-4">
                                    <label class="flex-1 border border-slate-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-surface-container-low transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                        <input wire:model="gender" value="male" class="w-4 h-4 text-primary focus:ring-primary border-slate-300" name="gender" type="radio" />
                                        <span class="font-label-md">Laki-laki</span>
                                    </label>
                                    <label class="flex-1 border border-slate-200 rounded-lg px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-surface-container-low transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                        <input wire:model="gender" value="female" class="w-4 h-4 text-primary focus:ring-primary border-slate-300" name="gender" type="radio" />
                                        <span class="font-label-md">Perempuan</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Program Selection -->
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="program">Program yang Diminati</label>
                                <select wire:model="program" class="w-full px-4 py-3 border @error('program') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all bg-white" id="program">
                                    <option disabled value="">Pilih Jurusan</option>
                                    <option value="cs">Rekayasa Perangkat Lunak</option>
                                    <option value="ds">Sains Data</option>
                                    <option value="cyber">Keamanan Siber</option>
                                    <option value="ai">Kecerdasan Buatan</option>
                                </select>
                            </div>
                            
                            <!-- Contact Info -->
                            <div class="md:col-span-2 mt-4 pt-4 border-t border-slate-100">
                                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-4">Keamanan Akun</h3>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="email">Alamat Email Pribadi</label>
                                <input wire:model="email" class="w-full px-4 py-3 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-300" id="email" placeholder="contoh@email.com" type="email" />
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="password">Kata Sandi</label>
                                <input wire:model="password" class="w-full px-4 py-3 border @error('password') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-300" id="password" type="password" />
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="password_confirmation">Konfirmasi Kata Sandi</label>
                                <input wire:model="password_confirmation" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-300" id="password_confirmation" type="password" />
                            </div>
                        </div>

                    @elseif ($currentStep === 2)
                        <!-- STEP 2: Academic Records -->
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <h2 class="font-headline-md text-headline-md text-on-surface">Riwayat Akademik</h2>
                            <p class="font-body-md text-body-md text-on-surface-variant">Harap berikan detail tentang institusi pendidikan Anda sebelumnya.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Previous School -->
                            <div class="md:col-span-2">
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="previous_school">Nama Sekolah Sebelumnya (Asal Sekolah)</label>
                                <input wire:model="previous_school" class="w-full px-4 py-3 border @error('previous_school') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-300" id="previous_school" placeholder="misal: SMP Negeri 1 Jakarta" type="text" />
                            </div>

                            <!-- Graduation Year -->
                            <div class="md:col-span-2">
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="graduation_year">Tahun Kelulusan</label>
                                <select wire:model="graduation_year" class="w-full px-4 py-3 border @error('graduation_year') border-red-500 @else border-slate-200 @enderror rounded-lg focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all bg-white" id="graduation_year">
                                    <option value="">Pilih Tahun</option>
                                    @foreach (range(date('Y') + 1, date('Y') - 5) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    @elseif ($currentStep === 3)
                        <!-- STEP 3: Documents -->
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <h2 class="font-headline-md text-headline-md text-on-surface">Dokumen yang Diperlukan</h2>
                            <p class="font-body-md text-body-md text-on-surface-variant">Unggah salinan hasil pindai dokumen resmi Anda untuk verifikasi.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Document Identity -->
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="document_identity">Kartu Identitas (KTP/KK)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg @error('document_identity') border-red-500 bg-red-50 @else hover:bg-surface-container-low transition-colors @enderror">
                                    <div class="space-y-1 text-center">
                                        @if($document_identity)
                                            <span class="material-symbols-outlined mx-auto text-4xl text-primary mb-2">task</span>
                                            <div class="text-sm text-on-surface-variant">File terpilih</div>
                                        @else
                                            <span class="material-symbols-outlined mx-auto text-4xl text-slate-400 mb-2">cloud_upload</span>
                                            <div class="flex text-sm text-on-surface-variant justify-center">
                                                <label for="document_identity" class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary-container focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                                    <span>Unggah file</span>
                                                    <input wire:model="document_identity" id="document_identity" name="document_identity" type="file" class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">atau seret dan lepas</p>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">PNG, JPG, JPEG hingga 2MB</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Document Diploma -->
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface-variant mb-2" for="document_diploma">Ijazah/Sertifikat (Ijazah/SKL)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg @error('document_diploma') border-red-500 bg-red-50 @else hover:bg-surface-container-low transition-colors @enderror">
                                    <div class="space-y-1 text-center">
                                        @if($document_diploma)
                                            <span class="material-symbols-outlined mx-auto text-4xl text-primary mb-2">task</span>
                                            <div class="text-sm text-on-surface-variant">File terpilih</div>
                                        @else
                                            <span class="material-symbols-outlined mx-auto text-4xl text-slate-400 mb-2">cloud_upload</span>
                                            <div class="flex text-sm text-on-surface-variant justify-center">
                                                <label for="document_diploma" class="relative cursor-pointer rounded-md font-medium text-primary hover:text-primary-container focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                                    <span>Unggah file</span>
                                                    <input wire:model="document_diploma" id="document_diploma" name="document_diploma" type="file" class="sr-only" accept=".pdf,image/*">
                                                </label>
                                                <p class="pl-1">atau seret dan lepas</p>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2">PDF, PNG, JPG hingga 5MB</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="pt-8 mt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="order-2 sm:order-1 flex gap-4">
                            @if ($currentStep > 1)
                                <button type="button" wire:click="previousStep" class="text-on-surface-variant font-label-md flex items-center gap-2 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined">arrow_back</span>
                                    Kembali
                                </button>
                            @else
                                <button type="button" class="text-on-surface-variant font-label-md flex items-center gap-2 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined">delete</span>
                                    Bersihkan Formulir
                                </button>
                            @endif
                        </div>
                        <div class="flex gap-4 w-full sm:w-auto order-1 sm:order-2">
                            <button type="submit" class="flex-1 sm:flex-none bg-primary text-on-primary px-8 py-3 rounded-lg font-label-md hover:shadow-lg hover:shadow-primary/20 transition-all flex items-center justify-center gap-2 w-full">
                                @if ($currentStep === 3)
                                    Selesaikan Pendaftaran
                                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                @else
                                    Langkah Berikutnya
                                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Terms Footer -->
            <p class="mt-6 text-center text-label-sm text-on-surface-variant px-12">
                Dengan mengeklik "{{ $currentStep === 3 ? 'Selesaikan Pendaftaran' : 'Langkah Berikutnya' }}", Anda menyetujui <a class="text-primary underline" href="#">Kebijakan Privasi</a> dan <a class="text-primary underline" href="#">Syarat Pendaftaran</a> kami. Data Anda dienkripsi dan dikelola secara aman.
            </p>
        </section>
    </div>
</div>
