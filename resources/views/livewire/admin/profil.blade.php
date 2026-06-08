<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex items-center justify-between mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Profil Saya</h1>
                <p class="text-sm text-slate-500 font-medium">Ubah info profil, perbarui kata sandi, atau unggah foto profil baru.</p>
            </div>
        </div>

        <!-- Session Flash Messages -->
        @if (session()->has('success'))
            <div class="p-4 mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center space-x-3 shadow-sm shadow-emerald-100 animate-slide-in-right">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start animate-fade-in delay-100">
            <!-- Left Panel: Avatar Card -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 text-center flex flex-col items-center">
                <!-- Avatar Preview -->
                <div class="relative group cursor-pointer mb-5">
                    @if($photo)
                        <img class="w-32 h-32 rounded-3xl object-cover ring-4 ring-indigo-50 shadow-lg" src="{{ $photo->temporaryUrl() }}" alt="Preview">
                    @elseif($existingPhoto)
                        <img class="w-32 h-32 rounded-3xl object-cover ring-4 ring-slate-100 shadow-lg" src="{{ asset('storage/' . $existingPhoto) }}" alt="Foto Profil">
                    @else
                        <img class="w-32 h-32 rounded-3xl object-cover ring-4 ring-slate-100 shadow-lg" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80" alt="Default Avatar">
                    @endif
                </div>

                <h3 class="text-lg font-bold text-slate-800">{{ $name }}</h3>
                <span class="inline-flex items-center px-3 py-1 bg-indigo-50 rounded-full text-xs font-bold text-indigo-600 mt-2">
                    Administrator
                </span>

                <div class="w-full mt-6 pt-6 border-t border-slate-100 text-left space-y-3">
                    <div class="flex items-center space-x-3 text-slate-500 text-sm font-medium">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="truncate">{{ $email }}</span>
                    </div>
                    @if($phone)
                        <div class="flex items-center space-x-3 text-slate-500 text-sm font-medium">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-1"/>
                            </svg>
                            <span>{{ $phone }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Panel: Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Edit Form -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-8">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Ubah Informasi Profil</h3>
                    
                    <form wire:submit.prevent="updateProfile" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama -->
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                                <input type="text" wire:model="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                @error('name') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Akun</label>
                                <input type="email" wire:model="email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                @error('email') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- No HP -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor HP</label>
                                <input type="text" wire:model="phone" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                @error('phone') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Photo Uploader -->
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Unggah Foto Baru (Maks 1MB)</label>
                                <input type="file" wire:model="photo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-2xl p-2 bg-slate-50">
                                @error('photo') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-600/10 transition-colors">
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Change Form -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-8">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Ubah Kata Sandi</h3>
                    
                    <form wire:submit.prevent="updatePassword" class="space-y-4">
                        <!-- Password Lama -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kata Sandi Lama</label>
                            <input type="password" wire:model="old_password" placeholder="Masukkan kata sandi lama..." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('old_password') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password Baru -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kata Sandi Baru</label>
                                <input type="password" wire:model="new_password" placeholder="Min. 6 karakter..." 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                @error('new_password') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" wire:model="new_password_confirmation" placeholder="Ulangi kata sandi baru..." 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-600/10 transition-colors">
                                Ganti Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
