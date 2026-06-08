<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'admin'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-8 pb-24 md:pb-8">
        <!-- Top bar/header -->
        <div class="flex items-center justify-between mb-8 animate-fade-in">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Pengaturan Sekolah</h1>
                <p class="text-sm text-slate-500 font-medium">Atur jam operasional, radius geofencing, hari aktif, dan data sekolah.</p>
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

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start animate-fade-in delay-100">
            <!-- Sidebar Navigation Tabs -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-4 flex flex-col space-y-1">
                <button wire:click="changeTab('identitas')" 
                        class="w-full text-left px-4.5 py-3 rounded-xl text-sm font-bold transition-all duration-150 {{ $activeTab === 'identitas' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    Identitas Sekolah
                </button>
                <button wire:click="changeTab('lokasi')" 
                        class="w-full text-left px-4.5 py-3 rounded-xl text-sm font-bold transition-all duration-150 {{ $activeTab === 'lokasi' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    Lokasi & Radius Map
                </button>
                <button wire:click="changeTab('jam')" 
                        class="w-full text-left px-4.5 py-3 rounded-xl text-sm font-bold transition-all duration-150 {{ $activeTab === 'jam' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    Jam Operasional
                </button>
                <button wire:click="changeTab('hari')" 
                        class="w-full text-left px-4.5 py-3 rounded-xl text-sm font-bold transition-all duration-150 {{ $activeTab === 'hari' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    Hari Kerja Aktif
                </button>
            </div>

            <!-- Settings Content Pane -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden lg:col-span-3">
                <form wire:submit.prevent="saveSettings">
                    <div class="p-6 md:p-8 space-y-6">
                        <!-- IDENTITAS TAB -->
                        @if($activeTab === 'identitas')
                            <div class="space-y-6 animate-fade-in">
                                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Profil & Kontak Sekolah</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nama Sekolah -->
                                    <div class="space-y-1.5 md:col-span-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Sekolah</label>
                                        <input type="text" wire:model="nama_sekolah" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                        @error('nama_sekolah') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- NPSN -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">NPSN</label>
                                        <input type="text" wire:model="npsn" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                        @error('npsn') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Telepon -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor Telepon</label>
                                        <input type="text" wire:model="telepon" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                        @error('telepon') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="space-y-1.5 md:col-span-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Resmi Sekolah</label>
                                        <input type="email" wire:model="email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                        @error('email') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Alamat -->
                                    <div class="space-y-1.5 md:col-span-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat Lengkap</label>
                                        <textarea wire:model="alamat_sekolah" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all resize-none"></textarea>
                                        @error('alamat_sekolah') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Logo Upload -->
                                    <div class="space-y-1.5 md:col-span-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Logo Sekolah</label>
                                        <div class="flex items-center space-x-4 p-4 bg-slate-50 border border-dashed border-slate-200 rounded-2xl">
                                            @if($logo)
                                                <img class="w-16 h-16 rounded-xl object-cover border border-slate-200" src="{{ $logo->temporaryUrl() }}" alt="Preview">
                                            @elseif($existingLogo)
                                                <img class="w-16 h-16 rounded-xl object-cover border border-slate-200" src="{{ asset('storage/' . $existingLogo) }}" alt="Logo">
                                            @else
                                                <div class="w-16 h-16 bg-slate-200 rounded-xl flex items-center justify-center text-slate-400 font-bold text-xs">No Logo</div>
                                            @endif
                                            <input type="file" wire:model="logo" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        </div>
                                        @error('logo') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- LOKASI TAB -->
                        @if($activeTab === 'lokasi')
                            <div class="space-y-6 animate-fade-in" wire:key="tab-lokasi">
                                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Koordinat & Radius Geofence</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Latitude -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Latitude</label>
                                        <input type="text" wire:model.live="latitude" id="input-lat" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                    </div>
                                    
                                    <!-- Longitude -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Longitude</label>
                                        <input type="text" wire:model.live="longitude" id="input-lng" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                    </div>

                                    <!-- Radius Slider -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Radius Presensi: <span class="text-indigo-600 font-bold" id="label-radius">{{ $radius_meter }}</span> meter</label>
                                        <input type="range" min="10" max="500" step="5" wire:model.live="radius_meter" id="input-radius" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600 my-3">
                                    </div>
                                </div>

                                <!-- Leaflet Map container -->
                                <div class="space-y-2" x-data="{
                                    map: null,
                                    marker: null,
                                    circle: null,
                                    initMap() {
                                        let lat = parseFloat($wire.latitude);
                                        let lng = parseFloat($wire.longitude);
                                        let rad = parseInt($wire.radius_meter);

                                        this.map = L.map('map').setView([lat, lng], 15);
                                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            maxZoom: 19,
                                            attribution: '© OpenStreetMap'
                                        }).addTo(this.map);

                                        this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                                        
                                        this.circle = L.circle([lat, lng], {
                                            color: '#4F46E5',
                                            fillColor: '#818CF8',
                                            fillOpacity: 0.15,
                                            radius: rad
                                        }).addTo(this.map);

                                        this.marker.on('dragend', (e) => {
                                            const pos = this.marker.getLatLng();
                                            this.circle.setLatLng(pos);
                                            $wire.set('latitude', parseFloat(pos.lat.toFixed(7)));
                                            $wire.set('longitude', parseFloat(pos.lng.toFixed(7)));
                                        });

                                        this.map.on('click', (e) => {
                                            this.marker.setLatLng(e.latlng);
                                            this.circle.setLatLng(e.latlng);
                                            $wire.set('latitude', parseFloat(e.latlng.lat.toFixed(7)));
                                            $wire.set('longitude', parseFloat(e.latlng.lng.toFixed(7)));
                                        });

                                        // Watchers for two-way synchronization
                                        this.$watch('$wire.radius_meter', value => {
                                            if (this.circle) this.circle.setRadius(parseInt(value));
                                        });
                                        
                                        this.$watch('$wire.latitude', value => {
                                            let parsedLat = parseFloat(value);
                                            let parsedLng = parseFloat($wire.longitude);
                                            if (!isNaN(parsedLat) && !isNaN(parsedLng)) {
                                                let latLng = L.latLng(parsedLat, parsedLng);
                                                this.marker.setLatLng(latLng);
                                                this.circle.setLatLng(latLng);
                                                this.map.panTo(latLng);
                                            }
                                        });
                                        
                                        this.$watch('$wire.longitude', value => {
                                            let parsedLat = parseFloat($wire.latitude);
                                            let parsedLng = parseFloat(value);
                                            if (!isNaN(parsedLat) && !isNaN(parsedLng)) {
                                                let latLng = L.latLng(parsedLat, parsedLng);
                                                this.marker.setLatLng(latLng);
                                                this.circle.setLatLng(latLng);
                                                this.map.panTo(latLng);
                                            }
                                        });
                                    }
                                }" x-init="setTimeout(() => initMap(), 150)">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Geser marker untuk menentukan titik pusat sekolah</label>
                                    <div id="map" class="w-full h-[320px] rounded-2xl border border-slate-200 shadow-inner" wire:ignore></div>
                                </div>
                            </div>
                        @endif

                        <!-- JAM TAB -->
                        @if($activeTab === 'jam')
                            <div class="space-y-6 animate-fade-in">
                                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Jam Batasan Absensi</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <!-- Jam Masuk -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jam Masuk</label>
                                        <input type="time" wire:model="jam_masuk" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                        @error('jam_masuk') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Jam Terlambat -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Batas Terlambat</label>
                                        <input type="time" wire:model="jam_terlambat" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                        @error('jam_terlambat') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Jam Pulang -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Buka Jam Pulang</label>
                                        <input type="time" wire:model="jam_pulang" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                        @error('jam_pulang') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Jam Pulang Akhir -->
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tutup Jam Pulang</label>
                                        <input type="time" wire:model="jam_pulang_akhir" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                        @error('jam_pulang_akhir') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- HARI TAB -->
                        @if($activeTab === 'hari')
                            <div class="space-y-6 animate-fade-in">
                                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Hari Aktif Kerja Sekolah</h3>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">Pilih hari-hari aktif di mana murid diwajibkan untuk hadir ke sekolah.</p>

                                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                        <label class="flex flex-col items-center justify-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-50/50 transition-all select-none">
                                            <input type="checkbox" value="{{ $hari }}" wire:model="hari_aktif" class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 mb-2">
                                            <span class="text-xs font-bold text-slate-700">{{ $hari }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('hari_aktif') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Footer Action buttons -->
                    <div class="px-6 py-5 bg-slate-50 border-t border-slate-150 flex items-center justify-end space-x-3">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-sm font-bold rounded-2xl shadow-md shadow-indigo-600/10 transition-all duration-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

