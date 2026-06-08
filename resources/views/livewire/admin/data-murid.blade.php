<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Data Murid</h1>
                <p class="text-sm text-slate-500 font-medium">Kelola data murid, reset kata sandi, dan import dari Excel.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="openImportModal" class="inline-flex items-center space-x-2 px-4.5 py-3 border border-slate-200 bg-white hover:bg-slate-50 active:scale-95 text-slate-600 font-bold rounded-2xl shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span>Import Excel</span>
                </button>
                
                <button wire:click="openAddModal" class="inline-flex items-center space-x-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-2xl shadow-md shadow-indigo-600/10 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Murid</span>
                </button>
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

        @if (session()->has('error'))
            <div class="p-4 mb-6 bg-red-50 border border-red-200 text-red-800 text-sm font-semibold rounded-2xl flex items-center space-x-3 shadow-sm shadow-red-100 animate-slide-in-right">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card Container -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in delay-100">
            <!-- Search & Filters Header -->
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-64">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                               placeholder="Cari murid..." 
                               class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all duration-200">
                    </div>

                    <!-- Kelas Filter -->
                    <select wire:model.live="filterKelas" 
                            class="w-full sm:w-48 px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 outline-none transition-all duration-200">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    Menampilkan: {{ $murid->total() }} Murid
                </div>
            </div>

            <!-- Table content for larger screens -->
            <div class="hidden md:block table-responsive">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100">
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-center w-16">No</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 w-24">NIS</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Kelas</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Email</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">No HP</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($murid as $index => $item)
                            <tr class="hover:bg-slate-50/30 transition-colors duration-150">
                                <td class="py-4 px-6 text-sm font-semibold text-slate-500 text-center">
                                    {{ $index + $murid->firstItem() }}
                                </td>
                                <td class="py-4 px-6 text-sm font-semibold text-slate-600">{{ $item->nis ?? '-' }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-8 h-8 rounded-full object-cover" 
                                             src="{{ $item->profile_photo ? asset('storage/' . $item->profile_photo) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&h=80&q=80' }}" 
                                             alt="Avatar">
                                        <span class="text-sm font-bold text-slate-700 block">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-indigo-50 rounded-full text-xs font-bold text-indigo-600">
                                        {{ $item->kelas->nama_kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-600">{{ $item->email }}</td>
                                <td class="py-4 px-6 text-sm text-slate-600">{{ $item->phone ?? '-' }}</td>
                                <td class="py-4 px-6 text-right space-x-1 whitespace-nowrap">
                                    <button wire:click="resetPassword({{ $item->id }})" 
                                            title="Reset Kata Sandi"
                                            class="inline-flex items-center p-2 border border-slate-200 hover:border-amber-600 hover:text-amber-600 text-slate-500 rounded-xl transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </button>
                                    <button wire:click="openEditModal({{ $item->id }})" 
                                            class="inline-flex items-center p-2 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 text-slate-500 rounded-xl transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})" 
                                            class="inline-flex items-center p-2 border border-slate-200 hover:border-red-600 hover:text-red-600 text-slate-500 rounded-xl transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-700">Tidak ada data murid</h3>
                                            <p class="text-xs text-slate-400 mt-1">Silakan tambahkan data murid baru.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Responsive Card View -->
            <div class="md:hidden p-4 space-y-4">
                @forelse($murid as $index => $item)
                    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow animate-fade-in">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                            <div class="flex items-center space-x-3">
                                <img class="w-9 h-9 rounded-full object-cover" 
                                     src="{{ $item->profile_photo ? asset('storage/' . $item->profile_photo) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&h=80&q=80' }}" 
                                     alt="Avatar">
                                <div>
                                    <span class="text-sm font-bold text-slate-800 block">{{ $item->name }}</span>
                                    <span class="text-xs text-slate-400 font-semibold block">NIS: {{ $item->nis ?? '-' }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 bg-indigo-50 rounded-full text-xs font-bold text-indigo-700">
                                {{ $item->kelas->nama_kelas ?? '-' }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400 font-semibold">Email:</span>
                                <span class="text-slate-700 font-bold break-all ml-4 text-right">{{ $item->email }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400 font-semibold">No HP:</span>
                                <span class="text-slate-700 font-bold">{{ $item->phone ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                            <button wire:click="resetPassword({{ $item->id }})" 
                                    title="Reset Kata Sandi"
                                    class="inline-flex items-center space-x-1.5 px-3 py-2 border border-slate-200 hover:border-amber-600 hover:text-amber-600 text-slate-500 text-xs font-bold rounded-xl transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Reset Sandi</span>
                            </button>
                            <button wire:click="openEditModal({{ $item->id }})" 
                                    class="inline-flex items-center p-2 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 text-slate-500 rounded-xl transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="confirmDelete({{ $item->id }})" 
                                    class="inline-flex items-center p-2 border border-slate-200 hover:border-red-600 hover:text-red-600 text-slate-500 rounded-xl transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Tidak ada data murid</h3>
                            <p class="text-xs text-slate-400 mt-1">Silakan tambahkan data murid baru.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            <div class="p-5 border-t border-slate-100">
                {{ $murid->links() }}
            </div>
        </div>
    </main>

    <!-- CRUD MODAL (ADD / EDIT) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>

            <div class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden z-10 animate-scale-up">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">{{ $editId ? 'Ubah Data Murid' : 'Tambah Murid Baru' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 rounded-xl p-1.5 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveMurid">
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[70vh] overflow-y-auto">
                        <!-- NIS -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor Induk Siswa (NIS)</label>
                            <input type="text" wire:model="nis" placeholder="Masukkan NIS..." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('nis') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" wire:model="name" placeholder="Nama Lengkap..." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('name') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kelas -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</label>
                            <select wire:model="kelas_id" 
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                <option value="">Pilih Kelas...</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat Email</label>
                            <input type="email" wire:model="email" placeholder="Email..." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('email') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- No HP -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor HP</label>
                            <input type="text" wire:model="phone" placeholder="Contoh: 08123456789..." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('phone') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Lahir</label>
                            <input type="date" wire:model="birth_date" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('birth_date') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        @if(!$editId)
                            <!-- Password (only for new users) -->
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kata Sandi Akun</label>
                                <input type="password" wire:model="password" placeholder="Masukkan sandi..." 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                @error('password') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" 
                                class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-600/10 transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- IMPORT EXCEL MODAL -->
    @if($showImportModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showImportModal', false)"></div>

            <div class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 overflow-hidden z-10 animate-scale-up">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">Import Murid dari Excel</h3>
                    <button wire:click="$set('showImportModal', false)" class="text-slate-400 hover:text-slate-600 rounded-xl p-1.5 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="importMurid">
                    <div class="p-6 space-y-4">
                        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl space-y-2">
                            <h4 class="text-xs font-bold text-indigo-900 uppercase">Petunjuk Format Kolom Excel:</h4>
                            <p class="text-xs text-indigo-700 leading-relaxed font-medium">Pastikan baris pertama Excel Anda berisi nama kolom berikut:</p>
                            <code class="block text-[10px] bg-indigo-900 text-indigo-100 p-2 rounded-xl font-mono text-center select-all">nis, nama, kelas, email, no_hp, tanggal_lahir, password</code>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih File Spreadsheet (.xlsx, .xls, .csv)</label>
                            <input type="file" wire:model="importFile" 
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-dashed border-slate-200 p-4 rounded-2xl bg-slate-50">
                            @error('importFile') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" wire:click="$set('showImportModal', false)" 
                                class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-600/10 transition-colors">
                            Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- DELETE CONFIRMATION MODAL -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>

            <div class="relative bg-white w-full max-w-sm rounded-3xl shadow-2xl border border-slate-200 overflow-hidden z-10 animate-scale-up">
                <div class="p-6 text-center space-y-4">
                    <div class="inline-flex p-3 bg-red-50 rounded-full text-red-600 mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Hapus Data Murid</h3>
                        <p class="text-xs text-slate-400 mt-1.5">Apakah Anda yakin ingin menghapus data murid ini? Data riwayat presensi dan pengajuan izin yang bersangkutan juga akan dihapus.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" wire:click="$set('showDeleteModal', false)" 
                            class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="deleteMurid" 
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow-md shadow-red-600/10 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
