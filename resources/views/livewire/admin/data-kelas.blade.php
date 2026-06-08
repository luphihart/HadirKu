<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Data Kelas</h1>
                <p class="text-sm text-slate-500 font-medium">Kelola daftar kelas yang aktif di sekolah Anda.</p>
            </div>
            
            <button wire:click="openAddModal" class="inline-flex items-center space-x-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-2xl shadow-md shadow-indigo-600/10 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Kelas</span>
            </button>
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
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="relative w-full sm:max-w-xs">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Cari kelas..." 
                           class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all duration-200">
                </div>
                <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    Total: {{ $kelas->count() }} Kelas
                </div>
            </div>

            <!-- Table content for larger screens -->
            <div class="hidden md:block table-responsive">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100">
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 w-16 text-center">No</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Nama Kelas</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-center">Jumlah Murid</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kelas as $index => $item)
                            <tr class="hover:bg-slate-50/30 transition-colors duration-150">
                                <td class="py-4 px-6 text-sm font-semibold text-slate-500 text-center">{{ $index + 1 }}</td>
                                <td class="py-4 px-6">
                                    <span class="text-sm font-bold text-slate-700 block">{{ $item->nama_kelas }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 rounded-full text-xs font-bold text-slate-600">
                                        {{ $item->murid_count }} Murid
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <button wire:click="openEditModal({{ $item->id }})" 
                                            class="inline-flex items-center space-x-1.5 px-3.5 py-2 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 text-slate-500 text-xs font-bold rounded-xl transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Ubah</span>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})" 
                                            class="inline-flex items-center space-x-1.5 px-3.5 py-2 border border-slate-200 hover:border-red-600 hover:text-red-600 text-slate-500 text-xs font-bold rounded-xl transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-700">Tidak ada data kelas</h3>
                                            <p class="text-xs text-slate-400 mt-1">Silakan tambahkan kelas baru terlebih dahulu.</p>
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
                @forelse($kelas as $index => $item)
                    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                            <span class="text-xs font-bold text-slate-400">#{{ $index + 1 }}</span>
                            <span class="inline-flex items-center px-3 py-1 bg-slate-100 rounded-full text-xs font-bold text-slate-600">
                                {{ $item->murid_count }} Murid
                            </span>
                        </div>
                        <div class="mb-4">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Nama Kelas</span>
                            <span class="text-base font-bold text-slate-800 block mt-0.5">{{ $item->nama_kelas }}</span>
                        </div>
                        <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                            <button wire:click="openEditModal({{ $item->id }})" 
                                    class="inline-flex items-center space-x-1.5 px-3 py-2 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 text-slate-500 text-xs font-bold rounded-xl transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span>Ubah</span>
                            </button>
                            <button wire:click="confirmDelete({{ $item->id }})" 
                                    class="inline-flex items-center space-x-1.5 px-3 py-2 border border-slate-200 hover:border-red-600 hover:text-red-600 text-slate-500 text-xs font-bold rounded-xl transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 text-center">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <div class="p-3 bg-slate-100 rounded-full text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                            </div>
                            <h3 class="text-xs font-bold text-slate-700">Tidak ada data kelas</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Silakan tambahkan kelas baru terlebih dahulu.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- CRUD MODAL (ADD / EDIT) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>

            <!-- Modal Content Card -->
            <div class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up z-10">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">{{ $editId ? 'Ubah Kelas' : 'Tambah Kelas Baru' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 rounded-xl p-1.5 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveKelas">
                    <div class="p-6 space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Kelas</label>
                            <input type="text" wire:model="namaKelas" 
                                   placeholder="Contoh: XII RPL 1, X MIPA 3" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 outline-none transition-all duration-200">
                            @error('namaKelas') 
                                <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" 
                                class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors duration-150">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-600/10 transition-colors duration-150">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- DELETE CONFIRMATION MODAL -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showDeleteModal', false)"></div>

            <!-- Modal Content Card -->
            <div class="relative bg-white w-full max-w-sm rounded-3xl shadow-2xl border border-slate-200 overflow-hidden animate-scale-up z-10">
                <div class="p-6 text-center space-y-4">
                    <div class="inline-flex p-3 bg-red-50 rounded-full text-red-600 mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Konfirmasi Hapus</h3>
                        <p class="text-xs text-slate-400 mt-1.5">Apakah Anda yakin ingin menghapus kelas ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" wire:click="$set('showDeleteModal', false)" 
                            class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors duration-150">
                        Batal
                    </button>
                    <button type="button" wire:click="deleteKelas" 
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow-md shadow-red-600/10 transition-colors duration-150">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
