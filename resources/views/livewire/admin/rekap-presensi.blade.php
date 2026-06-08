<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Rekap Presensi</h1>
                <p class="text-sm text-slate-500 font-medium">Lihat, saring, dan ekspor laporan kehadiran kelas atau murid.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button wire:click="exportExcel" class="inline-flex items-center space-x-2 px-4.5 py-3 border border-slate-200 bg-white hover:bg-slate-50 active:scale-95 text-slate-600 font-bold rounded-2xl shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Ekspor Excel</span>
                </button>
                
                <button wire:click="exportPdf" class="inline-flex items-center space-x-2 px-4.5 py-3 border border-slate-200 bg-white hover:bg-slate-50 active:scale-95 text-slate-600 font-bold rounded-2xl shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Ekspor PDF</span>
                </button>
            </div>
        </div>

        <!-- Summary Statistics Row -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8 animate-fade-in delay-100">
            <!-- Hadir -->
            <div class="bg-white p-4.5 border border-slate-200 rounded-2xl flex items-center space-x-4 shadow-sm">
                <span class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <span class="text-xs font-bold text-slate-400 block uppercase">Hadir</span>
                    <span class="text-xl font-extrabold text-slate-800">{{ $summary['hadir'] }}</span>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="bg-white p-4.5 border border-slate-200 rounded-2xl flex items-center space-x-4 shadow-sm">
                <span class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <span class="text-xs font-bold text-slate-400 block uppercase">Terlambat</span>
                    <span class="text-xl font-extrabold text-slate-800">{{ $summary['terlambat'] }}</span>
                </div>
            </div>

            <!-- Sakit -->
            <div class="bg-white p-4.5 border border-slate-200 rounded-2xl flex items-center space-x-4 shadow-sm">
                <span class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6M9 16h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <div>
                    <span class="text-xs font-bold text-slate-400 block uppercase">Sakit</span>
                    <span class="text-xl font-extrabold text-slate-800">{{ $summary['sakit'] }}</span>
                </div>
            </div>

            <!-- Izin -->
            <div class="bg-white p-4.5 border border-slate-200 rounded-2xl flex items-center space-x-4 shadow-sm">
                <span class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div>
                    <span class="text-xs font-bold text-slate-400 block uppercase">Izin</span>
                    <span class="text-xl font-extrabold text-slate-800">{{ $summary['izin'] }}</span>
                </div>
            </div>

            <!-- Alpa -->
            <div class="bg-white p-4.5 border border-slate-200 rounded-2xl flex items-center space-x-4 shadow-sm col-span-2 lg:col-span-1">
                <span class="p-3 bg-red-50 text-red-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <span class="text-xs font-bold text-slate-400 block uppercase text-red-500">Alpa</span>
                    <span class="text-xl font-extrabold text-red-600">{{ $summary['tidak_presensi'] }}</span>
                </div>
            </div>
        </div>

        <!-- Filter Row & Data Table -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in delay-200">
            <!-- Filter Bar -->
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Kelas -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Kelas</label>
                        <select wire:model.live="filterKelas" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 outline-none transition-all">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Murid -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Murid</label>
                        <select wire:model.live="filterMurid" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 outline-none transition-all" {{ empty($filterKelas) ? 'disabled' : '' }}>
                            <option value="">Semua Murid</option>
                            @foreach($muridList as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Mulai Tanggal</label>
                        <input type="date" wire:model.live="tanggalMulai" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Hingga Tanggal</label>
                        <input type="date" wire:model.live="tanggalSelesai" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Table content -->
            <!-- Table content for larger screens -->
            <div class="hidden md:block table-responsive">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100">
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-center w-16">No</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Nama Murid</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Kelas</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-center">Jam Masuk</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-center">Jam Pulang</th>
                            <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-slate-400 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($presensi as $index => $item)
                            <tr class="hover:bg-slate-50/30 transition-colors duration-150">
                                <td class="py-4 px-6 text-sm font-semibold text-slate-500 text-center">{{ $index + 1 }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-8 h-8 rounded-full object-cover" 
                                             src="{{ $item->user->profile_photo ? asset('storage/' . $item->user->profile_photo) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&h=80&q=80' }}" 
                                             alt="Avatar">
                                        <div>
                                            <span class="text-sm font-bold text-slate-700 block">{{ $item->user->name }}</span>
                                            <span class="text-xs text-slate-400 block -mt-0.5">NIS: {{ $item->user->nis ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-600 font-semibold">{{ $item->user->kelas->nama_kelas ?? '-' }}</td>
                                <td class="py-4 px-6 text-sm text-slate-600 font-semibold">{{ $item->tanggal->translatedFormat('d F Y') }}</td>
                                <td class="py-4 px-6 text-sm text-slate-700 font-bold text-center">{{ $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '-' }}</td>
                                <td class="py-4 px-6 text-sm text-slate-700 font-bold text-center">{{ $item->jam_pulang ? substr($item->jam_pulang, 0, 5) : '-' }}</td>
                                <td class="py-4 px-6 text-center">
                                    @php 
                                        $color = match($item->status) {
                                            'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'terlambat' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'sakit' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'izin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'tidak_presensi' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-slate-50 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $color }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-700">Tidak ada riwayat presensi</h3>
                                            <p class="text-xs text-slate-400 mt-1">Sesuaikan tanggal atau saring kelas untuk menampilkan hasil.</p>
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
                @forelse($presensi as $index => $item)
                    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow animate-fade-in">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                            <div class="flex items-center space-x-3">
                                <img class="w-9 h-9 rounded-full object-cover" 
                                     src="{{ $item->user->profile_photo ? asset('storage/' . $item->user->profile_photo) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=80&h=80&q=80' }}" 
                                     alt="Avatar">
                                <div>
                                    <span class="text-sm font-bold text-slate-800 block">{{ $item->user->name }}</span>
                                    <span class="text-xs text-slate-400 font-semibold block">NIS: {{ $item->user->nis ?? '-' }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 bg-indigo-50 rounded-full text-xs font-bold text-indigo-700">
                                {{ $item->user->kelas->nama_kelas ?? '-' }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400 font-semibold">Tanggal:</span>
                                <span class="text-slate-700 font-bold">{{ $item->tanggal->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400 font-semibold">Jam Masuk:</span>
                                <span class="text-slate-700 font-bold">{{ $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '-' }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400 font-semibold">Jam Pulang:</span>
                                <span class="text-slate-700 font-bold">{{ $item->jam_pulang ? substr($item->jam_pulang, 0, 5) : '-' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <span class="text-[10px] text-slate-400 font-semibold">#{{ $index + 1 }}</span>
                            
                            @php 
                                $color = match($item->status) {
                                    'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'terlambat' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'sakit' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'izin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'tidak_presensi' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $color }}">
                                {{ $item->status_label }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <div class="p-4 bg-slate-100 rounded-full text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700">Tidak ada riwayat presensi</h3>
                            <p class="text-xs text-slate-400 mt-1">Sesuaikan tanggal atau saring kelas untuk menampilkan hasil.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
