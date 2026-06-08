<div class="flex flex-col md:flex-row min-h-screen bg-slate-50">
    @include('components.sidebar', ['role' => 'murid'])

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 p-4 md:p-6 pb-24 md:pb-8 flex flex-col justify-between">
        <div class="max-w-2xl mx-auto w-full space-y-6">
            <!-- Header -->
            <div class="text-center md:text-left animate-fade-in">
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Presensi Mandiri</h1>
                <p class="text-sm text-slate-500 font-medium mt-1">Lakukan verifikasi lokasi dan ambil foto selfie untuk presensi hari ini.</p>
            </div>

            <!-- Error Messages -->
            @error('location')
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-semibold rounded-2xl flex items-center space-x-3 shadow-sm animate-slide-in-right">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            @error('selfie')
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-semibold rounded-2xl flex items-center space-x-3 shadow-sm animate-slide-in-right">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <!-- Main Interactive Card -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden animate-fade-in delay-100 flex flex-col">
                <!-- Geofencing Status Bar -->
                <div class="p-4 text-center border-b border-slate-100 transition-colors {{ $isWithinRange ? 'bg-emerald-50 text-emerald-800' : 'bg-red-50 text-red-800' }}" id="status-bar">
                    <span class="text-xs font-extrabold uppercase tracking-wider block" id="status-text">
                        {{ $isWithinRange ? 'Lokasi Valid (Di dalam Area Radius)' : 'Mendeteksi Lokasi Anda...' }}
                    </span>
                    <span class="text-[10px] font-semibold block mt-0.5" id="status-subtext">
                        @if($isWithinRange)
                            Jarak Anda ke sekolah: {{ round($distance) }} meter
                        @else
                            Silakan izinkan akses lokasi pada browser Anda.
                        @endif
                    </span>
                </div>

                <!-- Camera/Preview Screen Container -->
                <div class="relative bg-slate-900 aspect-[3/4] max-w-sm mx-auto w-full my-6 rounded-3xl overflow-hidden shadow-inner flex items-center justify-center">
                    <!-- Loading skeleton -->
                    <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center text-slate-500 space-y-3 bg-slate-950 z-20">
                        <div class="w-10 h-10 border-4 border-slate-700 border-t-indigo-600 rounded-full animate-spin"></div>
                        <span class="text-xs font-semibold">Mengaktifkan kamera selfie...</span>
                    </div>

                    <!-- Webcam stream -->
                    <video id="webcam" autoplay playsinline class="w-full h-full object-cover transform -scale-x-100"></video>
                    
                    <!-- Captured static preview image -->
                    <img id="selfie-preview" class="absolute inset-0 w-full h-full object-cover transform -scale-x-100 hidden z-10" src="#" alt="Preview">

                    <!-- Watermark HUD preview overlay -->
                    <div class="absolute bottom-4 left-4 right-4 bg-slate-950/60 backdrop-blur-xs p-3 rounded-2xl text-[10px] text-white font-mono z-20 space-y-0.5 border border-white/10">
                        <div class="font-bold text-xs">{{ auth()->user()->name }}</div>
                        <div>NIS: {{ auth()->user()->nis ?? '-' }}</div>
                        <div id="hud-date">{{ now()->format('d M Y H:i:s') }}</div>
                        <div id="hud-coords">Mencari koordinat...</div>
                    </div>
                </div>

                <!-- Capture Button Controls -->
                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex flex-col items-center space-y-4">
                    <!-- Standard Capture action -->
                    <button type="button" id="btn-capture" class="w-full max-w-xs inline-flex items-center justify-center space-x-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/15 transition-all duration-200" disabled>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                        <span>Ambil Foto Selfie</span>
                    </button>

                    <!-- Confirmation Send / Retake actions (Shown post capture) -->
                    <div id="btn-group-confirm" class="w-full max-w-xs grid grid-cols-2 gap-3 hidden">
                        <button type="button" id="btn-retake" class="px-4 py-3 bg-white hover:bg-slate-100 border border-slate-200 text-slate-500 font-bold rounded-2xl transition-all">
                            Ulangi Foto
                        </button>
                        <button type="button" id="btn-submit" class="px-4 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-2xl shadow-md transition-all {{ $isWithinRange ? '' : 'opacity-50 cursor-not-allowed' }}" {{ $isWithinRange ? '' : 'disabled' }}>
                            Kirim Absen
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hidden elements for canvas capture -->
        <canvas id="capture-canvas" class="hidden" width="800" height="1066"></canvas>
    </main>
</div>

@script
<script>
    let stream = null;
    let video = document.getElementById('webcam');
    let preview = document.getElementById('selfie-preview');
    let btnCapture = document.getElementById('btn-capture');
    let btnConfirmGroup = document.getElementById('btn-group-confirm');
    let btnRetake = document.getElementById('btn-retake');
    let btnSubmit = document.getElementById('btn-submit');
    let canvas = document.getElementById('capture-canvas');
    let cameraLoading = document.getElementById('camera-loading');

    let userLat = null;
    let userLng = null;
    let base64Photo = null;

    // Start video streaming
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user', // Front camera
                    width: { ideal: 800 },
                    height: { ideal: 1066 }
                },
                audio: false
            });
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                cameraLoading.classList.add('hidden');
                btnCapture.disabled = false;
            };
        } catch (err) {
            console.error('Kamera gagal diakses:', err);
            cameraLoading.innerHTML = `<span class="text-xs font-bold text-red-500 text-center px-4">Gagal mengakses kamera front-facing. Pastikan izin kamera aktif pada browser.</span>`;
        }
    }

    // Geolocation API check
    function requestLocation() {
        if (!navigator.geolocation) {
            updateStatusBar(false, "GPS tidak didukung oleh browser Anda.");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;

                // Send coords back to livewire
                $wire.verifyLocation(userLat, userLng).then(() => {
                    const isRange = $wire.isWithinRange;
                    const distance = Math.round($wire.distance);
                    const radius = parseInt($wire.radius_meter);

                    updateStatusBar(isRange, isRange 
                        ? `Lokasi Valid (Di dalam Area)` 
                        : `Lokasi Tidak Valid (Di luar Area)`
                    , `Jarak Anda ke sekolah: ${distance} meter (Batas: ${radius}m)`);

                    document.getElementById('hud-coords').innerText = `Lat: ${userLat.toFixed(5)} Lng: ${userLng.toFixed(5)}`;
                });
            },
            (err) => {
                updateStatusBar(false, "Akses lokasi ditolak.", "Pastikan setelan GPS dan lokasi browser diizinkan.");
                document.getElementById('hud-coords').innerText = "Titik GPS diblokir";
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    function updateStatusBar(isValid, text, subtext = "") {
        const bar = document.getElementById('status-bar');
        const txt = document.getElementById('status-text');
        const sub = document.getElementById('status-subtext');

        if (bar && txt) {
            txt.innerText = text;
            sub.innerText = subtext;
            
            if (isValid) {
                bar.className = "p-4 text-center border-b border-slate-100 bg-emerald-50 text-emerald-800";
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            } else {
                bar.className = "p-4 text-center border-b border-slate-100 bg-red-50 text-red-800";
                if (btnSubmit) {
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }
    }

    // Initialize Camera and Geo GPS
    startCamera();
    requestLocation();

    // Live Watermark HUD Clock
    setInterval(() => {
        const timeHud = document.getElementById('hud-date');
        if (timeHud) {
            const now = new Date();
            timeHud.innerText = now.toLocaleString('id-ID', { hour12: false });
        }
    }, 1000);

    // Capture click
    btnCapture.addEventListener('click', () => {
        if (!stream) return;

        // Draw image frame to hidden canvas
        const ctx = canvas.getContext('2d');
        // Mirror canvas horizontal flipping to match mirrored video preview
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Reset transform
        ctx.setTransform(1, 0, 0, 1, 0, 0);

        // Convert base64 data url
        base64Photo = canvas.toDataURL('image/jpeg', 0.85);

        // Hide stream, show snapshot preview
        video.classList.add('hidden');
        preview.src = base64Photo;
        preview.classList.remove('hidden');

        // Toggle buttons
        btnCapture.classList.add('hidden');
        btnConfirmGroup.classList.remove('hidden');
    });

    // Retake click
    btnRetake.addEventListener('click', () => {
        // Hide preview, show camera stream
        preview.classList.add('hidden');
        video.classList.remove('hidden');
        
        // Clear snapshot cache
        base64Photo = null;

        // Toggle buttons
        btnConfirmGroup.classList.add('hidden');
        btnCapture.classList.remove('hidden');
    });

    // Submit click
    btnSubmit.addEventListener('click', () => {
        if (!base64Photo || !userLat || !userLng) return;
        
        btnSubmit.disabled = true;
        btnSubmit.innerText = "Mengirim...";

        $wire.submitPresensi(base64Photo, userLat, userLng);
    });

    // Clean up streams when navigated away
    document.addEventListener('livewire:navigating', () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endscript
