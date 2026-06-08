<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Persetujuan Izin / Sakit</h1>
                <p class="text-sm text-slate-500 font-medium">Tinjau, setujui, tolak, atau minta revisi pengajuan absen murid.</p>
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

        <!-- Tabs & Filters Row -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 animate-fade-in delay-100">
            <!-- Leave/Sick Tabs -->
            <div class="flex bg-slate-200/60 p-1.5 rounded-2xl w-full md:w-auto">
                <button wire:click="changeTab('izin')" 
                        class="flex-1 md:flex-none px-6 py-2.5 text-xs md:text-sm font-bold rounded-xl transition-all duration-200 {{ $activeTab === 'izin' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-800' }}">
                    Pengajuan Izin
                </button>
                <button wire:click="changeTab('sakit')" 
                        class="flex-1 md:flex-none px-6 py-2.5 text-xs md:text-sm font-bold rounded-xl transition-all duration-200 {{ $activeTab === 'sakit' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-800' }}">
                    Pengajuan Sakit
                </button>
            </div>

            <!-- Status filter row -->
            <div class="flex flex-wrap items-center gap-2">
                @foreach(['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'revisi' => 'Revisi'] as $statusVal => $statusLabel)
                    <button wire:click="setFilterStatus('{{ $statusVal }}')" 
                            class="px-4 py-2 border text-xs font-bold rounded-xl transition-all duration-200 {{ $filterStatus === $statusVal ? 'bg-slate-800 text-white border-slate-800 shadow-sm' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-400' }}">
                        {{ $statusLabel }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in delay-200">
            <!-- Table content for larger screens -->
            <div class="hidden md:block table-responsive">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100">
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Nama Murid</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Kelas</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal Absen</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Keterangan</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Lampiran</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pengajuanList as $item)
                            <tr class="hover:bg-slate-50/30 transition-colors duration-150">
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-9 h-9 rounded-full object-cover" 
                                             src="{{ $item->user->profile_photo ? asset('storage/' . $item->user->profile_photo) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&h=80&q=80' }}" 
                                             alt="Avatar">
                                        <div>
                                            <span class="text-sm font-bold text-slate-700 block">{{ $item->user->name }}</span>
                                            <span class="text-xs text-slate-400 block -mt-0.5">NIS: {{ $item->user->nis ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-600 font-semibold">{{ $item->user->kelas->nama_kelas ?? '-' }}</td>
                                <td class="py-4 px-6 text-sm text-slate-600">
                                    <span class="font-bold text-slate-700 block">
                                        {{ $item->tanggal_mulai->format('d/m/Y') }} s.d {{ $item->tanggal_selesai->format('d/m/Y') }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-semibold">
                                        ({{ $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1 }} hari)
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-500 max-w-xs leading-relaxed">
                                    <span class="block text-slate-700 font-medium">{{ $item->keterangan }}</span>
                                    @if($item->catatan_admin)
                                        <span class="block text-xs bg-slate-50 p-2 border border-slate-100 rounded-xl text-slate-500 mt-2 font-mono">
                                            Catatan: {{ $item->catatan_admin }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-sm">
                                    @if($item->lampiran)
                                        <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank" 
                                           class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors duration-150">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            <span>Lihat File</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 font-semibold uppercase">Tidak Ada</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @php 
                                        $color = match($item->status_pengajuan) {
                                            'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                            'revisi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            default => 'bg-slate-50 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $color }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    @if($item->status_pengajuan === 'menunggu')
                                        <div class="flex items-center justify-end space-x-1">
                                            <button wire:click="approve({{ $item->id }})" 
                                                    title="Setujui"
                                                    class="inline-flex items-center p-2.5 bg-emerald-50 border border-emerald-100 hover:bg-emerald-600 hover:text-white text-emerald-600 rounded-xl transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                            <button wire:click="openActionModal({{ $item->id }}, 'revisi')" 
                                                    title="Minta Revisi"
                                                    class="inline-flex items-center p-2.5 bg-blue-50 border border-blue-100 hover:bg-blue-600 hover:text-white text-blue-600 rounded-xl transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                            </button>
                                            <button wire:click="openActionModal({{ $item->id }}, 'tolak')" 
                                                    title="Tolak"
                                                    class="inline-flex items-center p-2.5 bg-red-50 border border-red-100 hover:bg-red-600 hover:text-white text-red-600 rounded-xl transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <button wire:click="deletePengajuan({{ $item->id }})" 
                                                title="Hapus"
                                                class="inline-flex items-center p-2 border border-slate-200 hover:border-red-600 hover:text-red-600 text-slate-400 rounded-xl transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-700">Tidak ada pengajuan</h3>
                                            <p class="text-xs text-slate-400 mt-1">Tidak ditemukan pengajuan dengan status ini.</p>
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
                @forelse($pengajuanList as $item)
                    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow animate-fade-in">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                            <div class="flex items-center space-x-3">
                                <img class="w-9 h-9 rounded-full object-cover" 
                                     src="{{ $item->user->profile_photo ? asset('storage/' . $item->user->profile_photo) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&h=80&q=80' }}" 
                                     alt="Avatar">
                                <div>
                                    <span class="text-sm font-bold text-slate-800 block">{{ $item->user->name }}</span>
                                    <span class="text-xs text-slate-400 font-semibold block">{{ $item->user->kelas->nama_kelas ?? '-' }}</span>
                                </div>
                            </div>
                            
                            @php 
                                $color = match($item->status_pengajuan) {
                                    'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                    'revisi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $color }}">
                                {{ $item->status_label }}
                            </span>
                        </div>

                        <div class="space-y-2.5 mb-4">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Tanggal Absen</span>
                                <span class="text-sm font-bold text-slate-700 block mt-0.5">
                                    {{ $item->tanggal_mulai->format('d/m/Y') }} s.d {{ $item->tanggal_selesai->format('d/m/Y') }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium">
                                    ({{ $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1 }} hari)
                                </span>
                            </div>
                            
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Keterangan / Alasan</span>
                                <span class="text-sm text-slate-600 block mt-0.5 leading-relaxed">{{ $item->keterangan }}</span>
                                @if($item->catatan_admin)
                                    <span class="block text-xs bg-slate-50 p-2 border border-slate-100 rounded-xl text-slate-500 mt-2 font-mono">
                                        Catatan: {{ $item->catatan_admin }}
                                    </span>
                                @endif
                            </div>

                            @if($item->lampiran)
                                <div class="pt-2">
                                    <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank" 
                                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        <span>Lihat Lampiran</span>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                            @if($item->status_pengajuan === 'menunggu')
                                <button wire:click="approve({{ $item->id }})" 
                                        class="inline-flex items-center space-x-1 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-sm shadow-emerald-600/10">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Setujui</span>
                                </button>
                                <button wire:click="openActionModal({{ $item->id }}, 'revisi')" 
                                        class="inline-flex items-center space-x-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-sm shadow-blue-600/10">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                    <span>Minta Revisi</span>
                                </button>
                                <button wire:click="openActionModal({{ $item->id }}, 'tolak')" 
                                        class="inline-flex items-center space-x-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-sm shadow-red-600/10">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Tolak</span>
                                </button>
                            @else
                                <button wire:click="deletePengajuan({{ $item->id }})" 
                                        class="inline-flex items-center space-x-1 px-3.5 py-2 border border-slate-200 hover:border-red-600 hover:text-red-600 text-slate-500 text-xs font-bold rounded-xl transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Tidak ada pengajuan</h3>
                            <p class="text-xs text-slate-400 mt-1">Tidak ditemukan pengajuan dengan status ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- REJECTION & REVISION ACTION MODAL -->
    @if($showActionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showActionModal', false)"></div>

            <div class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-200 overflow-hidden z-10 animate-scale-up">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $actionType === 'tolak' ? 'Tolak Pengajuan' : 'Minta Revisi Pengajuan' }}
                    </h3>
                    <button wire:click="$set('showActionModal', false)" class="text-slate-400 hover:text-slate-600 rounded-xl p-1.5 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitAction">
                    <div class="p-6 space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Berikan Catatan atau Alasan</label>
                            <textarea wire:model="catatanAdmin" rows="4" 
                                      placeholder="Contoh: Lampiran file tidak valid/tidak terbaca. Silakan ajukan ulang dengan file yang jelas." 
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all resize-none"></textarea>
                            @error('catatanAdmin') 
                                <span class="text-xs font-semibold text-red-500 block mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <button type="button" wire:click="$set('showActionModal', false)" 
                                class="px-4.5 py-2.5 bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 text-white text-sm font-bold rounded-xl shadow-md transition-colors {{ $actionType === 'tolak' ? 'bg-red-600 hover:bg-red-700 shadow-red-600/10' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-600/10' }}">
                            Kirim Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
