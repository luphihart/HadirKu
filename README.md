# Hadirku — Aplikasi Presensi Digital Sekolah

**Hadirku** adalah aplikasi manajemen presensi digital sekolah modern berbasis web yang mengedepankan pendekatan *mobile-first*. Aplikasi ini dirancang untuk memudahkan sekolah dalam mencatat dan memantau kehadiran siswa secara real-time melalui kamera selfie yang dilengkapi dengan penanda air (watermark) dinamis serta pembatasan wilayah presensi berbasis geofencing GPS (Leaflet.js).

Aplikasi dibangun di atas fondasi teknologi terbaru **Laravel 12, Livewire 4, Tailwind CSS 4, dan MySQL/MariaDB**.

---

## 🚀 Fitur Utama

### 1. Panel Siswa (Student Portal)
* **Dashboard Reaktif**: Selamat datang personal, info status presensi hari ini (Masuk & Pulang), pengumuman terbaru, dan ucapan ulang tahun otomatis.
* **Presensi Kamera + GPS Geofencing**:
  * Menggunakan API browser untuk melacak lokasi dengan presensi tinggi.
  * Preview kamera depan dengan efek mirror dan visualisasi radius geofence (Hijau = di dalam area, Merah = di luar area).
  * Kompresi foto selfie server-side dengan penulisan watermark permanen (Nama, NIS, Tanggal, Jam, dan Koordinat GPS).
* **Pengajuan Izin / Sakit**:
  * Form pengajuan digital yang mewajibkan unggah bukti surat (PDF/JPG/PNG).
  * Alur **Revisi Izin** jika ditolak/diminta perbaikan oleh admin, dilengkapi banner dashboard dan tombol revisi cepat.
  * Tampilan riwayat pengajuan izin yang ringkas dengan modal detail berbasis Alpine.js.
* **Profil Siswa**: Ubah biodata, ganti kata sandi, dan perbarui foto profil.

### 2. Panel Admin (Admin Dashboard)
* **Analisis Data & Grafik**:
  * Statistik harian lengkap (Hadir, Terlambat, Sakit, Izin, Alpa).
  * Tren kehadiran interaktif menggunakan Chart.js yang reaktif terhadap pilihan periode (Harian, Mingguan, Bulanan).
* **Manajemen Kelas**: CRUD kelas lengkap dengan counter jumlah murid otomatis.
* **Manajemen Murid**:
  * CRUD data murid lengkap.
  * **Import Murid Massal**: Mengunggah spreadsheet Excel dengan penanganan kelas otomatis.
  * Fitur reset cepat kata sandi siswa ke nilai bawaan (`password123`).
* **Persetujuan Izin**: Konfirmasi pengajuan izin/sakit murid (Status: Setuju, Tolak, Butuh Revisi). Izin yang disetujui akan otomatis tersinkronisasi ke presensi harian siswa.
* **Kalender Sekolah**: Penentuan agenda kegiatan dan libur nasional. Hari libur otomatis menonaktifkan tombol presensi siswa.
* **Pengumuman**: Pembuatan pengumuman bertarget (Semua Murid, Kelas Tertentu, Murid Spesifik).
* **Pengaturan Sekolah & Geofence**:
  * Atur koordinat GPS sekolah dan batas radius geofence (dalam meter) secara visual dengan Leaflet.js.
  * Setelan jam masuk, batas terlambat, jam pulang, serta hari sekolah aktif (Senin - Jumat).
* **Profil Admin**: Update biodata, ganti password, dan ganti foto profil.

### 3. Otomatisasi Sistem (Scheduler Command)
* Command Artisan `php artisan presensi:auto-absent` berjalan otomatis setiap hari pukul **23:59** untuk mengubah status murid yang tidak hadir tanpa keterangan menjadi `tidak_presensi` (Alpa), serta mencocokkan siswa yang izinnya disetujui.

---

## 🛠️ Persyaratan Sistem

* PHP 8.2 atau lebih baru
* MySQL / MariaDB
* Composer (Package Manager PHP)
* Node.js & NPM (Asset Compiler)
* Ekstensi PHP: GD (untuk manipulasi gambar watermark)

---

## 💻 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal Anda:

### 1. Klon Repositori & Masuk ke Folder
```bash
git clone https://github.com/username/Hadirku.git
cd Hadirku
```

### 2. Pasang Dependensi PHP & JavaScript
```bash
composer install
npm install
```

### 3. Buat Berkas Konfigurasi Lingkungan (`.env`)
Salin berkas `.env.example` menjadi `.env`:
```bash
copy .env.example .env
```
Buka berkas `.env` dan sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hadirku
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Hasilkan Kunci Aplikasi (App Key)
```bash
php artisan key:generate
```

### 5. Buat Tautan Simbolik Storage
Agar foto selfie dan lampiran izin dapat diakses oleh publik, buat tautan simbolik folder storage:
```bash
php artisan storage:link
```

### 6. Jalankan Migrasi & Database Seeder
Langkah ini akan membuat seluruh tabel di database dan mengisi data bawaan (seperti pengaturan sekolah dan akun admin):
```bash
php artisan migrate --seed
```

### 7. Jalankan Aplikasi
Jalankan server pengembangan Laravel dan server kompilasi asset Vite secara bersamaan (di dua tab terminal berbeda):

* **Terminal 1 (Laravel Server)**:
  ```bash
  php artisan serve
  ```
  Aplikasi akan berjalan di `http://127.0.0.1:8000`

* **Terminal 2 (Vite Compiler)**:
  ```bash
  npm run dev
  ```

---

## 🔑 Akun Uji Coba Default

Setelah menjalankan database seeder, Anda dapat masuk sebagai administrator menggunakan akun berikut:

* **Halaman Login**: `http://127.0.0.1:8000/login`
* **Email Admin**: `admin@hadirku.com`
* **Kata Sandi**: `password`

Untuk masuk sebagai siswa, silakan buat akun siswa terlebih dahulu melalui menu **Data Murid** di panel admin.

---

## 📁 Panduan Lainnya
* Panduan deployment aman ke Webhosting / cPanel dapat dibaca secara lengkap pada berkas [DEPLOYMENT.md](file:///d:/sinaumedia/Hadirku/DEPLOYMENT.md).

---

## 📝 Lisensi
Aplikasi ini bersifat open-source di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
