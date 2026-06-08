<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'murid'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-6 pb-24 md:pb-8">
        <!-- Welcome Card with Gradient -->
        <div class="relative bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-800 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-indigo-600/10 mb-6 overflow-hidden animate-fade-in">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;40&quot; height=&quot;40&quot; viewBox=&quot;0 0 40 40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M0 0h20v20H0V0zm20 20h20v20H20V20z&quot; fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.15&quot; fill-rule=&quot;evenodd&quot;/%3E%3C/svg%3E');"></div>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full text-indigo-100">
                        {{ $user->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                    </span>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-white mt-3">Selamat Datang, {{ $user->name }}!</h1>
                    <p class="text-sm text-indigo-100 font-medium mt-1">Jangan lupa untuk melakukan presensi tepat waktu hari ini.</p>
                </div>
                <div class="text-left md:text-right">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-200 block">Hari Ini</span>
                    <span class="text-lg font-bold text-white block mt-0.5">{{ now()->translatedFormat('l, d M Y') }}</span>
                </div>
            </div>
        </div>

        @if($isBirthday)
            <!-- Birthday Card -->
            <div class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 rounded-3xl p-6 text-white shadow-xl shadow-pink-500/10 mb-6 animate-slide-in-right">
                <div class="flex items-center space-x-4">
                    <div class="p-3.5 bg-white/25 rounded-2xl animate-bounce">
                        🎂
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Selamat Ulang Tahun, {{ $user->name }}! 🎉</h3>
                        <p class="text-sm text-pink-100 font-medium mt-0.5">Semoga panjang umur, sehat selalu, dan dilancarkan segala kegiatannya di sekolah.</p>
                    </div>
                </div>
            </div>
        @endif

        @if($revisiIzin)
            <!-- Revision Request Alert Card -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-3xl p-6 text-white shadow-xl shadow-amber-500/10 mb-6 animate-slide-in-right">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-white/20 rounded-2xl animate-pulse-soft">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold">Revisi Pengajuan Izin Diperlukan!</h3>
                            <p class="text-xs text-amber-50 font-medium mt-1">Catatan Admin: "{{ $revisiIzin->catatan_admin }}"</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('murid.pengajuan-izin') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-white hover:bg-amber-50 active:scale-95 text-amber-700 font-extrabold text-xs rounded-xl shadow-md transition-all duration-150">
                            <span>Lakukan Revisi</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Attendance status widget -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6 animate-fade-in delay-100">
            <h3 class="text-sm font-bold text-slate-400 tracking-wider uppercase mb-5">Status Kehadiran Hari Ini</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <!-- Check-in / Check-out visual pipeline -->
                <div class="space-y-4">
                    <!-- Masuk -->
                    <div class="flex items-center space-x-4">
                        @if($presensiHariIni && $presensiHariIni->jam_masuk)
                            <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <div>
                                <span class="text-xs font-bold text-slate-400 block">Presensi Masuk</span>
                                <span class="text-base font-extrabold text-slate-800">{{ substr($presensiHariIni->jam_masuk, 0, 5) }}</span>
                                @if($presensiHariIni->status === 'terlambat')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-md border border-amber-100 ml-1">Terlambat</span>
                                @endif
                            </div>
                        @else
                            <span class="p-2.5 bg-slate-100 text-slate-400 rounded-2xl border border-slate-150">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </span>
                            <div>
                                <span class="text-xs font-bold text-slate-400 block">Presensi Masuk</span>
                                <span class="text-sm font-bold text-slate-400">Belum masuk</span>
                            </div>
                        @endif
                    </div>

                    <!-- Line connector -->
                    <div class="w-0.5 h-6 bg-slate-200 ml-6.5"></div>

                    <!-- Pulang -->
                    <div class="flex items-center space-x-4">
                        @if($presensiHariIni && $presensiHariIni->jam_pulang)
                            <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <div>
                                <span class="text-xs font-bold text-slate-400 block">Presensi Pulang</span>
                                <span class="text-base font-extrabold text-slate-800">{{ substr($presensiHariIni->jam_pulang, 0, 5) }}</span>
                            </div>
                        @else
                            <span class="p-2.5 bg-slate-100 text-slate-400 rounded-2xl border border-slate-150">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            <div>
                                <span class="text-xs font-bold text-slate-400 block">Presensi Pulang</span>
                                <span class="text-sm font-bold text-slate-400">Belum pulang</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Button -->
                <div class="text-center md:text-right">
                    @if($isHoliday)
                        <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-sm font-semibold text-center">
                            Hari ini adalah Libur Sekolah. Presensi tidak aktif.
                        </div>
                    @elseif($isInactiveDay)
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-500 text-sm font-semibold text-center">
                            Hari ini bukan hari kerja aktif sekolah.
                        </div>
                    @elseif(!$presensiHariIni)
                        <a href="{{ route('murid.presensi') }}" class="w-full inline-flex items-center justify-center space-x-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-2xl shadow-lg shadow-indigo-600/20 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150">
                            <span>Lakukan Presensi Masuk</span>
                        </a>
                    @elseif($presensiHariIni && !$presensiHariIni->jam_pulang)
                        @if(now()->format('H:i:s') < $settings->jam_pulang)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-500 text-sm font-semibold text-center leading-relaxed">
                                Presensi masuk tercatat. Presensi pulang dibuka mulai pukul <span class="text-slate-800 font-bold">{{ substr($settings->jam_pulang, 0, 5) }}</span>.
                            </div>
                        @else
                            <a href="{{ route('murid.presensi') }}" class="w-full inline-flex items-center justify-center space-x-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-2xl shadow-lg shadow-indigo-600/20 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150">
                                <span>Lakukan Presensi Pulang</span>
                            </a>
                        @endif
                    @else
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-800 text-sm font-semibold text-center flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Presensi Anda hari ini telah lengkap! Terima kasih.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Attendance History list (last 30 days) -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 lg:col-span-2">
                <h3 class="text-sm font-bold text-slate-400 tracking-wider uppercase mb-5">Riwayat Presensi (30 Hari Terakhir)</h3>
                
                <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">
                    @forelse($riwayat as $r)
                        <div class="p-4 border border-slate-150 rounded-2xl flex items-center justify-between bg-slate-50/20">
                            <div>
                                <span class="text-sm font-bold text-slate-700 block">{{ $r->tanggal->translatedFormat('l, d M Y') }}</span>
                                <div class="flex items-center space-x-4 mt-1 text-xs text-slate-400 font-semibold">
                                    <span>Masuk: <span class="text-slate-600 font-bold">{{ $r->jam_masuk ? substr($r->jam_masuk, 0, 5) : '-' }}</span></span>
                                    <span>Pulang: <span class="text-slate-600 font-bold">{{ $r->jam_pulang ? substr($r->jam_pulang, 0, 5) : '-' }}</span></span>
                                </div>
                            </div>
                            
                            <div>
                                @php 
                                    $col = match($r->status) {
                                        'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'terlambat' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'sakit' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'izin' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'tidak_presensi' => 'bg-red-50 text-red-700 border-red-100',
                                        default => 'bg-slate-50 text-slate-700 border-slate-100'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $col }}">
                                    {{ $r->status_label }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 font-medium">Belum ada riwayat presensi tercatat.</div>
                    @endforelse
                </div>
            </div>

            <!-- Announcements section -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col">
                <h3 class="text-sm font-bold text-slate-400 tracking-wider uppercase mb-5">Pengumuman</h3>
                
                <div class="space-y-4">
                    @forelse($pengumuman as $p)
                        <div class="p-4.5 bg-indigo-50/30 border border-indigo-50 rounded-2xl">
                            <h4 class="text-sm font-bold text-slate-800 mb-1 leading-snug">{{ $p->judul }}</h4>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed mb-3">{{ Str::limit($p->konten, 100) }}</p>
                            <span class="text-[10px] font-semibold text-slate-400">
                                {{ $p->created_at->translatedFormat('d M Y H:i') }} WIB
                            </span>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 font-medium">Tidak ada pengumuman terbaru.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
