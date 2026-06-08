<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Dashboard Admin</h1>
                <p class="text-sm text-slate-500 font-medium">Selamat datang kembali! Berikut ringkasan kehadiran hari ini.</p>
            </div>
            
            <!-- Period Toggle -->
            <div class="flex items-center bg-white p-1 rounded-2xl shadow-sm border border-slate-200">
                <button wire:click="setPeriod('harian')" 
                        class="px-4 py-2 text-xs md:text-sm font-semibold rounded-xl transition-all duration-200 {{ $period === 'harian' ? 'bg-indigo-600 text-white shadow' : 'text-slate-500 hover:text-slate-800' }}">
                    Harian
                </button>
                <button wire:click="setPeriod('mingguan')" 
                        class="px-4 py-2 text-xs md:text-sm font-semibold rounded-xl transition-all duration-200 {{ $period === 'mingguan' ? 'bg-indigo-600 text-white shadow' : 'text-slate-500 hover:text-slate-800' }}">
                    Mingguan
                </button>
                <button wire:click="setPeriod('bulanan')" 
                        class="px-4 py-2 text-xs md:text-sm font-semibold rounded-xl transition-all duration-200 {{ $period === 'bulanan' ? 'bg-indigo-600 text-white shadow' : 'text-slate-500 hover:text-slate-800' }}">
                    Bulanan
                </button>
            </div>
        </div>

        @if($stats['pending_izin'] > 0)
            <!-- Alert/Notification Card for Pending Leave requests -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-3xl p-5 mb-8 flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0 shadow-sm shadow-indigo-100 animate-slide-in-right">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-indigo-600 rounded-2xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Persetujuan Izin Butuh Tindakan</h3>
                        <p class="text-xs text-slate-500 font-medium">Terdapat <span class="text-indigo-600 font-bold">{{ $stats['pending_izin'] }}</span> pengajuan izin/sakit yang belum ditinjau.</p>
                    </div>
                </div>
                <a href="{{ route('admin.persetujuan-izin') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/10 transition-all duration-200">
                    Tinjau Sekarang
                </a>
            </div>
        @endif

        <!-- Interactive Stat Cards Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-8 animate-fade-in delay-100">
            <!-- Hadir Card -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Hadir</span>
                    <span class="p-2 bg-emerald-50 rounded-xl text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-slate-800 block animate-count-up">{{ $stats['hadir'] }}</span>
                    <span class="text-xs text-slate-400 font-medium mt-1 block">Murid tepat waktu</span>
                </div>
            </div>

            <!-- Terlambat Card -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Terlambat</span>
                    <span class="p-2 bg-amber-50 rounded-xl text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-slate-800 block animate-count-up">{{ $stats['terlambat'] }}</span>
                    <span class="text-xs text-slate-400 font-medium mt-1 block">Melebihi batas jam masuk</span>
                </div>
            </div>

            <!-- Sakit Card -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Sakit</span>
                    <span class="p-2 bg-blue-50 rounded-xl text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6M9 16h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-slate-800 block animate-count-up">{{ $stats['sakit'] }}</span>
                    <span class="text-xs text-slate-400 font-medium mt-1 block">Dengan surat dokter</span>
                </div>
            </div>

            <!-- Izin Card -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Izin</span>
                    <span class="p-2 bg-purple-50 rounded-xl text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-slate-800 block animate-count-up">{{ $stats['izin'] }}</span>
                    <span class="text-xs text-slate-400 font-medium mt-1 block">Dengan izin disetujui</span>
                </div>
            </div>

            <!-- Alpa / Tidak Presensi Card -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover flex flex-col justify-between col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 tracking-wider uppercase text-red-500">Tidak Hadir</span>
                    <span class="p-2 bg-red-50 rounded-xl text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-extrabold text-red-600 block animate-count-up">{{ $stats['tidak_presensi'] }}</span>
                    <span class="text-xs text-slate-400 font-medium mt-1 block">Tanpa keterangan</span>
                </div>
            </div>
        </div>

        <!-- Main Chart & Quick Action Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Attendance Trend Graph -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm lg:col-span-2 flex flex-col min-h-[400px]"
                 x-data="{
                     chart: null,
                     renderChart() {
                         const ctx = document.getElementById('trendChart');
                         if (!ctx) return;
                         
                         if (this.chart) {
                             this.chart.destroy();
                         }
                         
                         const data = $wire.chartData;
                         
                         this.chart = new Chart(ctx, {
                             type: 'line',
                             data: {
                                 labels: data.labels,
                                 datasets: [
                                     {
                                         label: 'Hadir',
                                         data: data.hadir,
                                         borderColor: '#10b981',
                                         backgroundColor: 'rgba(16, 185, 129, 0.05)',
                                         tension: 0.35,
                                         fill: true,
                                         borderWidth: 3,
                                         pointBackgroundColor: '#10b981',
                                     },
                                     {
                                         label: 'Terlambat',
                                         data: data.terlambat,
                                         borderColor: '#f59e0b',
                                         backgroundColor: 'rgba(245, 158, 11, 0.05)',
                                         tension: 0.35,
                                         fill: true,
                                         borderWidth: 3,
                                         pointBackgroundColor: '#f59e0b',
                                     },
                                     {
                                         label: 'Alpa',
                                         data: data.tidak_presensi,
                                         borderColor: '#ef4444',
                                         backgroundColor: 'rgba(239, 68, 68, 0.05)',
                                         tension: 0.35,
                                         fill: true,
                                         borderWidth: 3,
                                         pointBackgroundColor: '#ef4444',
                                     }
                                 ]
                             },
                             options: {
                                 responsive: true,
                                 maintainAspectRatio: false,
                                 plugins: {
                                     legend: {
                                         position: 'bottom',
                                         labels: {
                                             font: { family: 'Inter', weight: 'bold', size: 12 },
                                             usePointStyle: true,
                                             padding: 20
                                         }
                                     },
                                     tooltip: {
                                         padding: 12,
                                         bodyFont: { family: 'Inter' },
                                         titleFont: { family: 'Inter', weight: 'bold' }
                                     }
                                 },
                                 scales: {
                                     y: {
                                         beginAtZero: true,
                                         grid: { color: '#f1f5f9' },
                                         ticks: {
                                             stepSize: 1,
                                             font: { family: 'Inter', size: 11 }
                                         }
                                     },
                                     x: {
                                         grid: { display: false },
                                         ticks: { font: { family: 'Inter', size: 11 } }
                                     }
                                 }
                             }
                         });
                     }
                 }"
                 x-init="renderChart()"
                 @stats-updated.window="setTimeout(() => renderChart(), 50)">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Tren Kehadiran</h3>
                <div class="flex-1 relative" wire:ignore>
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions Panel -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Aksi Cepat</h3>
                    <p class="text-sm text-slate-500 font-medium mb-6">Kelola data sekolah dan ekspor laporan secara langsung.</p>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.murid') }}" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-indigo-50 border border-slate-100 rounded-2xl group transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <span class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold text-slate-700">Tambah Murid Baru</span>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>

                        <a href="{{ route('admin.rekap-presensi') }}" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-emerald-50 border border-slate-100 rounded-2xl group transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <span class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold text-slate-700">Unduh Rekap Laporan</span>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>

                        <a href="{{ route('admin.pengaturan') }}" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-purple-50 border border-slate-100 rounded-2xl group transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <span class="p-2.5 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold text-slate-700">Sesuaikan Lokasi Geofence</span>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Footer Summary info -->
                <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
                    <span>Aktif hari ini: {{ $stats['total_murid'] }} murid</span>
                    <span>Hadirku v1.0.0</span>
                </div>
            </div>
        </div>
    </main>
</div>
</div>
