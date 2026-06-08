<div class="flex flex-col md:flex-row min-h-screen bg-slate-50" x-data="{ showDetailModal: false, selectedItem: null }">
    @include('components.sidebar', ['role' => 'murid'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-6 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Pengajuan Izin / Sakit</h1>
                <p class="text-sm text-slate-500 font-medium">Ajukan permohonan ketidakhadiran sekolah dan unggah surat bukti.</p>
            </div>
            
            @if(!$showForm)
                <button wire:click="openForm" class="inline-flex items-center space-x-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-2xl shadow-md shadow-indigo-600/10 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Ajukan Permohonan</span>
                </button>
            @endif
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

        <!-- REQUEST FORM CARD (Shown conditionally) -->
        @if($showForm)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-8 mb-8 animate-scale-up max-w-xl mx-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-slate-800">{{ $editId ? 'Form Revisi Permohonan Absen' : 'Form Permohonan Absen' }}</h3>
                    <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitRequest" class="space-y-4">
                    <!-- Jenis Izin -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Ketidakhadiran</label>
                        <select wire:model.live="jenis" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            <option value="izin">Izin (Keperluan Mendesak)</option>
                            <option value="sakit">Sakit (Membutuhkan Istirahat)</option>
                        </select>
                        @error('jenis') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rentang Tanggal -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dari Tanggal</label>
                            <input type="date" wire:model="tanggalMulai" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('tanggalMulai') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sampai Tanggal</label>
                            <input type="date" wire:model="tanggalSelesai" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                            @error('tanggalSelesai') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alasan / Keterangan Tidak Hadir</label>
                        <textarea wire:model="keterangan" rows="4" placeholder="Jelaskan alasan detail ketidakhadiran Anda..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all resize-none"></textarea>
                        @error('keterangan') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Upload Lampiran -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            {{ $jenis === 'sakit' ? 'Unggah Surat Dokter (PDF/JPG, Maks 2MB)' : 'Unggah Surat Pernyataan / Bukti Pendukung (Maks 2MB)' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="file" wire:model="lampiran" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 p-2.5 rounded-2xl bg-slate-50">
                        @if($existingLampiran)
                            <div class="text-[11px] text-indigo-600 font-semibold mt-1">
                                📄 Berkas terunggah: <a href="{{ asset('storage/' . $existingLampiran) }}" target="_blank" class="underline hover:text-indigo-800 font-bold">Lihat Berkas Saat Ini</a> (Biarkan kosong jika tidak ingin mengubah).
                            </div>
                        @endif
                        @error('lampiran') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" wire:click="$set('showForm', false)" class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-600/10 transition-colors">
                            Kirim Permohonan
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- HISTORY LIST (Mobile-First Cards Grid) -->
        <div class="space-y-4 animate-fade-in delay-100">
            <h3 class="text-sm font-bold text-slate-400 tracking-wider uppercase">Riwayat Pengajuan Absen</h3>
            
            @php
                $revisiList = $riwayat->where('status_pengajuan', 'revisi');
            @endphp
            @if($revisiList->isNotEmpty())
                <div class="p-5 bg-amber-50 border border-amber-200 text-amber-800 text-sm font-semibold rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm shadow-amber-100 animate-slide-in-right">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-amber-600 flex-shrink-0 animate-pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <span class="block text-slate-800 text-sm font-bold">Ada {{ $revisiList->count() }} pengajuan izin yang memerlukan revisi.</span>
                            <span class="text-xs text-slate-500 font-medium block mt-0.5">Catatan Admin: "{{ $revisiList->first()->catatan_admin }}"</span>
                        </div>
                    </div>
                    <button wire:click="editRequest({{ $revisiList->first()->id }})" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 self-start md:self-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Revisi Sekarang</span>
                    </button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($riwayat as $item)
                    @php
                        $isRevisi = $item->status_pengajuan === 'revisi';
                        $cardClass = $isRevisi ? 'border-amber-300 bg-amber-50/5 ring-1 ring-amber-300/30' : 'border-slate-200 bg-white';
                        
                        $jenisColor = $item->jenis === 'sakit' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-purple-50 text-purple-700 border-purple-100';
                        
                        $statusColor = match($item->status_pengajuan) {
                            'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'ditolak' => 'bg-red-50 text-red-700 border-red-200',
                            'revisi' => 'bg-amber-50 text-amber-700 border-amber-200',
                            default => 'bg-slate-50 text-slate-700 border-slate-200'
                        };
                    @endphp
                    <div class="p-5 rounded-3xl border shadow-sm flex flex-col justify-between hover:shadow-md transition-all duration-200 {{ $cardClass }}">
                        <div>
                            <!-- Header status -->
                            <div class="flex items-center justify-between mb-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $jenisColor }}">
                                    {{ $item->jenis_label }}
                                </span>

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $statusColor }}">
                                    {{ $item->status_label }}
                                </span>
                            </div>

                            <span class="text-sm font-bold text-slate-700 block">
                                {{ $item->tanggal_mulai->format('d M Y') }} s.d {{ $item->tanggal_selesai->format('d M Y') }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">
                                ({{ $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1 }} hari)
                            </span>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] text-slate-400 font-semibold">
                                Diajukan: {{ $item->created_at->format('d-m-Y H:i') }}
                            </span>

                            <div class="flex items-center space-x-2">
                                <!-- Detail Button -->
                                <button @click="selectedItem = { 
                                    id: '{{ $item->id }}', 
                                    jenis: '{{ $item->jenis_label }}', 
                                    status: '{{ $item->status_label }}', 
                                    statusClass: '{{ $statusColor }}', 
                                    jenisClass: '{{ $jenisColor }}', 
                                    tanggal: '{{ $item->tanggal_mulai->format('d M Y') }} s.d {{ $item->tanggal_selesai->format('d M Y') }}', 
                                    durasi: '{{ $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1 }} hari', 
                                    keterangan: '{{ addslashes(str_replace(["\r", "\n"], ' ', $item->keterangan)) }}', 
                                    catatan_admin: '{{ $item->catatan_admin ? addslashes(str_replace(["\r", "\n"], ' ', $item->catatan_admin)) : '' }}', 
                                    lampiran: '{{ $item->lampiran ? asset('storage/' . $item->lampiran) : '' }}', 
                                    diajukan: '{{ $item->created_at->format('d-m-Y H:i') }} WIB', 
                                    is_revisi: {{ $item->status_pengajuan === 'revisi' ? 'true' : 'false' }} 
                                }; showDetailModal = true;" type="button" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>Detail</span>
                                </button>

                                @if($item->status_pengajuan === 'revisi')
                                    <button wire:click="editRequest({{ $item->id }})" type="button" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-md shadow-amber-600/10 transition-colors duration-150">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Revisi</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-700">Belum ada pengajuan</h3>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol di atas untuk mengajukan izin ketidakhadiran.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Detail Modal -->
        <div x-show="showDetailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak x-transition style="display: none;">
            <div @click.away="showDetailModal = false" class="bg-white w-full max-w-lg rounded-3xl border border-slate-200 shadow-2xl p-6 md:p-8 animate-scale-up">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Detail Pengajuan Izin / Sakit</h3>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-4" x-show="selectedItem" x-cloak>
                    <!-- Badges -->
                    <div class="flex items-center space-x-2">
                        <span :class="selectedItem ? selectedItem.jenisClass : ''" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider" x-text="selectedItem ? selectedItem.jenis : ''"></span>
                        <span :class="selectedItem ? selectedItem.statusClass : ''" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider" x-text="selectedItem ? selectedItem.status : ''"></span>
                    </div>

                    <!-- Date & Duration -->
                    <div class="border-b border-slate-100 pb-3">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Waktu Ketidakhadiran</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5" x-text="selectedItem ? selectedItem.tanggal : ''"></p>
                        <p class="text-xs text-slate-500 font-semibold" x-text="selectedItem ? selectedItem.durasi : ''"></p>
                    </div>

                    <!-- Alasan / Keterangan -->
                    <div class="border-b border-slate-100 pb-3">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Alasan / Keterangan</span>
                        <p class="text-sm text-slate-600 font-medium leading-relaxed bg-slate-50 p-3.5 rounded-2xl border border-slate-100" x-text="selectedItem ? selectedItem.keterangan : ''"></p>
                    </div>

                    <!-- Tanggapan Admin (if any) -->
                    <div x-show="selectedItem && selectedItem.catatan_admin" class="border-b border-slate-100 pb-3">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1 text-amber-600">Tanggapan Admin</span>
                        <p class="text-xs text-amber-800 font-mono leading-relaxed bg-amber-50/50 p-3.5 rounded-2xl border border-amber-200" x-text="selectedItem ? selectedItem.catatan_admin : ''"></p>
                    </div>

                    <!-- Diajukan Pada -->
                    <div class="flex justify-between text-xs text-slate-400 font-semibold pt-1">
                        <span>Tanggal Pengajuan:</span>
                        <span x-text="selectedItem ? selectedItem.diajukan : ''"></span>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-4 mt-5">
                    <a x-show="selectedItem && selectedItem.lampiran" :href="selectedItem ? selectedItem.lampiran : '#'" target="_blank" class="inline-flex items-center space-x-1 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Lihat Bukti Lampiran</span>
                    </a>
                    <button x-show="selectedItem && selectedItem.is_revisi" @click="showDetailModal = false; $wire.editRequest(selectedItem.id)" class="inline-flex items-center space-x-1 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition-colors shadow-md shadow-amber-600/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Revisi</span>
                    </button>
                    <button @click="showDetailModal = false" class="px-4.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>
