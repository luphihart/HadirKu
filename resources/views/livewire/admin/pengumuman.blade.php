<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Pengumuman Sekolah</h1>
                <p class="text-sm text-slate-500 font-medium">Buat pengumuman penting untuk ditayangkan di dashboard murid.</p>
            </div>
            
            <button wire:click="openAddModal" class="inline-flex items-center space-x-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-2xl shadow-md shadow-indigo-600/10 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buat Pengumuman</span>
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

        <!-- Announcements Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in delay-100">
            @forelse($announcements as $ann)
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow duration-200 relative">
                    <div>
                        <!-- Header / badges -->
                        <div class="flex items-center justify-between mb-4">
                            <!-- Target Badge -->
                            @php 
                                $targetColor = match($ann->target) {
                                    'semua' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'kelas' => 'bg-purple-50 text-purple-700 border-purple-100',
                                    'murid' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    default => 'bg-slate-50 text-slate-700 border-slate-100'
                                };
                                $targetText = match($ann->target) {
                                    'semua' => 'Semua Murid',
                                    'kelas' => 'Kelas: ' . ($ann->targetKelas->nama_kelas ?? '-'),
                                    'murid' => 'Murid: ' . ($ann->targetMurid->name ?? '-'),
                                    default => '-'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $targetColor }}">
                                {{ $targetText }}
                            </span>

                            <!-- Active Toggle -->
                            <button wire:click="toggleStatus({{ $ann->id }})" 
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider transition-colors {{ $ann->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}">
                                {{ $ann->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </div>

                        <!-- Title & Content -->
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $ann->judul }}</h3>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6 whitespace-pre-wrap">{{ $ann->konten }}</p>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-semibold">
                            Dipublikasi: {{ $ann->created_at->translatedFormat('d M Y H:i') }} WIB
                        </span>
                        
                        <div class="flex items-center space-x-1.5">
                            <button wire:click="openEditModal({{ $ann->id }})" 
                                    class="p-2 border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 text-slate-400 rounded-xl transition-all duration-200">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="confirmDelete({{ $ann->id }})" 
                                    class="p-2 border border-slate-200 hover:border-red-600 hover:text-red-600 text-slate-400 rounded-xl transition-all duration-200">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-12 text-center">
                    <div class="flex flex-col items-center justify-center space-y-3">
                        <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-700">Tidak ada pengumuman</h3>
                            <p class="text-xs text-slate-400 mt-1">Gunakan tombol di atas untuk mempublikasikan pengumuman baru.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </main>

    <!-- CRUD MODAL (ADD / EDIT) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>

            <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden z-10 animate-scale-up">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">{{ $editId ? 'Ubah Pengumuman' : 'Buat Pengumuman Baru' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 rounded-xl p-1.5 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="savePengumuman">
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <!-- Judul -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Pengumuman</label>
                            <input type="text" wire:model="judul" placeholder="Masukkan judul..." 
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('judul') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Target Penerima -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Target Penerima</label>
                            <select wire:model.live="target" 
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                <option value="semua">Semua Murid</option>
                                <option value="kelas">Kelas Tertentu</option>
                                <option value="murid">Murid Tertentu</option>
                            </select>
                            @error('target') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Conditional Target Input: Kelas -->
                        @if($target === 'kelas')
                            <div class="space-y-1.5 animate-fade-in">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih Kelas</label>
                                <select wire:model="targetKelasId" 
                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                    <option value="">Pilih Kelas...</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('targetKelasId') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Conditional Target Input: Murid (Autocomplete Search) -->
                        @if($target === 'murid')
                            <div class="space-y-1.5 animate-fade-in relative">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cari Murid</label>
                                <input type="text" wire:model.live.debounce.300ms="searchMurid" placeholder="Ketik nama murid (min. 2 karakter)..." 
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                @error('targetMuridId') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                                
                                @if(!empty($muridSuggestions))
                                    <div class="absolute w-full bg-white border border-slate-200 rounded-2xl shadow-lg mt-1 z-20 overflow-hidden divide-y divide-slate-100">
                                        @foreach($muridSuggestions as $s)
                                            <button type="button" wire:click="selectMurid({{ $s->id }}, '{{ $s->name }}')" 
                                                    class="w-full text-left px-4 py-2.5 text-sm hover:bg-indigo-50 text-slate-700 font-semibold flex items-center justify-between">
                                                <span>{{ $s->name }}</span>
                                                <span class="text-xs font-medium text-slate-400">{{ $s->kelas->nama_kelas ?? '-' }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Konten pengumuman -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Isi Pengumuman</label>
                            <textarea wire:model="konten" rows="6" placeholder="Ketik isi pengumuman lengkap..." 
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all resize-none"></textarea>
                            @error('konten') <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status aktif -->
                        <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <input type="checkbox" id="isActive" wire:model="isActive" class="w-5 h-5 text-indigo-600 border-slate-300 rounded-lg focus:ring-indigo-500">
                            <label for="isActive" class="text-xs font-bold text-slate-700 select-none cursor-pointer">Tayangkan pengumuman ini sekarang</label>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Hapus Pengumuman</h3>
                        <p class="text-xs text-slate-400 mt-1.5">Apakah Anda yakin ingin menghapus pengumuman ini secara permanen?</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" wire:click="$set('showDeleteModal', false)" 
                            class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="deletePengumuman" 
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow-md shadow-red-600/10 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
