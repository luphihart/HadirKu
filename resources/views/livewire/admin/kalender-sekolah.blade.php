<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Kalender & Agenda Sekolah</h1>
                <p class="text-sm text-slate-500 font-medium">Kelola libur nasional dan kegiatan sekolah yang berdampak pada presensi.</p>
            </div>
            
            <button wire:click="openAddModal" class="inline-flex items-center space-x-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-2xl shadow-md shadow-indigo-600/10 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Agenda</span>
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

        <!-- List Agenda/Events -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in delay-100">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Daftar Agenda Terjadwal</h3>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($agendaList as $event)
                    <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/30 transition-colors">
                        <div class="flex items-start space-x-4">
                            <!-- Indicator icon -->
                            @if($event->kategori === 'libur_nasional')
                                <div class="p-3 bg-red-50 text-red-600 rounded-2xl">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            <div>
                                <h4 class="text-base font-bold text-slate-800">{{ $event->judul }}</h4>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                    <!-- Date Badge -->
                                    <span class="inline-flex items-center text-xs font-semibold text-slate-500">
                                        <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $event->tanggal_mulai->translatedFormat('d M Y') }} 
                                        @if(!$event->tanggal_mulai->isSameDay($event->tanggal_selesai))
                                            s.d {{ $event->tanggal_selesai->translatedFormat('d M Y') }}
                                        @endif
                                    </span>

                                    <!-- Category Badge -->
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $event->kategori === 'libur_nasional' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                        {{ $event->kategori_label }}
                                    </span>

                                    <!-- Affect Attendance Badge -->
                                    @if($event->is_libur)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-orange-50 text-orange-600 border border-orange-100">
                                            Meliburkan Presensi
                                        </span>
                                    @endif
                                </div>
                                @if($event->keterangan)
                                    <p class="text-sm text-slate-500 font-medium mt-2 leading-relaxed">{{ $event->keterangan }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 self-end md:self-center">
                            <button wire:click="openEditModal({{ $event->id }})" 
                                    class="inline-flex items-center space-x-1 px-3.5 py-2 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 text-slate-500 text-xs font-bold rounded-xl transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span>Ubah</span>
                            </button>
                            <button wire:click="confirmDelete({{ $event->id }})" 
                                    class="inline-flex items-center space-x-1 px-3.5 py-2 border border-slate-200 hover:border-red-600 hover:text-red-600 text-slate-400 rounded-xl transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-700">Belum ada agenda sekolah</h3>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol di atas untuk menambahkan agenda baru.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- CRUD MODAL (ADD / EDIT) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>

            <div class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 overflow-hidden z-10 animate-scale-up">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">{{ $editId ? 'Ubah Agenda' : 'Tambah Agenda Sekolah' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 rounded-xl p-1.5 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveEvent">
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <!-- Judul Agenda -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Agenda</label>
                            <input type="text" wire:model="judul" placeholder="Contoh: Libur Lebaran, Ujian Akhir Semester" 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('judul') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</label>
                            <select wire:model.live="kategori" 
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                <option value="kegiatan_sekolah">Kegiatan Sekolah</option>
                                <option value="libur_nasional">Libur Nasional</option>
                            </select>
                            @error('kategori') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggalMulai" 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                @error('tanggalMulai') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Selesai</label>
                                <input type="date" wire:model="tanggalSelesai" 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                @error('tanggalSelesai') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        @if($kategori !== 'libur_nasional')
                            <!-- Meliburkan Presensi Checkbox (Libur nasional is auto-libur) -->
                            <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                <input type="checkbox" id="isLibur" wire:model="isLibur" class="w-5 h-5 text-indigo-600 border-slate-300 rounded-lg focus:ring-indigo-500">
                                <label for="isLibur" class="text-xs font-bold text-slate-700 select-none cursor-pointer">Liburkan Presensi pada tanggal ini</label>
                            </div>
                        @endif

                        <!-- Keterangan -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan Tambahan</label>
                            <textarea wire:model="keterangan" rows="3" placeholder="Deskripsi singkat agenda..." 
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all resize-none"></textarea>
                            @error('keterangan') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>
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
                        <h3 class="text-lg font-bold text-slate-800">Hapus Agenda</h3>
                        <p class="text-xs text-slate-400 mt-1.5">Apakah Anda yakin ingin menghapus agenda sekolah ini dari kalender?</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" wire:click="$set('showDeleteModal', false)" 
                            class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="deleteEvent" 
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow-md shadow-red-600/10 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
