# Panduan Deployment Aplikasi Hadirku di Webhosting / cPanel

Panduan ini menjelaskan langkah demi langkah untuk mendeploy aplikasi presensi **Hadirku** (Laravel 12 + Livewire 4 + Vite) ke layanan webhosting berbasis cPanel secara aman dan optimal.

---

## 🔒 Pendekatan Struktur Folder Aman (Rekomendasi)

Untuk mengamankan informasi kredensial pada berkas `.env`, database, dan logika kode backend Laravel, **sangat tidak disarankan** menaruh seluruh file project langsung di dalam folder `public_html`.

Gunakan struktur folder berikut:
*   `/home/username/hadirku_app/` : Menyimpan seluruh berkas inti Laravel (seperti `app`, `config`, `routes`, `vendor`, `.env`, dll).
*   `/home/username/public_html/` : Menyimpan hanya berkas yang ada di dalam folder `public` bawaan Laravel (seperti `build`, `storage`, `index.php`, `.htaccess`, dll).

---

## 🛠️ Langkah-Langkah Deployment

### Langkah 1: Persiapan Aset & Database di Lokal (Localhost)

Sebelum mengunggah file ke hosting, lakukan kompilasi aset frontend dan ekspor database di komputer lokal Anda:

1.  **Kompilasi Aset Frontend (CSS/JS)**:
    Jalankan perintah ini di direktori project lokal agar Vite memproduksi bundel file siap produksi ke folder `public/build/`:
    ```bash
    npm run build
    ```
2.  **Ekspor Database**:
    Ekspor database lokal Anda menjadi berkas `.sql` (misalnya: `hadirku.sql`) menggunakan phpMyAdmin lokal atau CLI:
    ```bash
    mysqldump -u root -p hadirku > hadirku.sql
    ```

### Langkah 2: Mengunggah Berkas ke Hosting

1.  Masuk ke **cPanel File Manager**.
2.  Pergi ke direktori root akun Anda (`/home/username/`).
3.  Buat folder baru bernama `hadirku_app`.
4.  Kompres seluruh berkas proyek Anda di komputer lokal menjadi file `.zip` (**kecuali** folder `node_modules`, `.git`, dan isi dari folder `public` yang akan diunggah terpisah). Unggah file `.zip` tersebut ke dalam `/home/username/hadirku_app/` lalu ekstrak.
5.  Pergi ke folder `/home/username/public_html/`.
6.  Buka folder `public` di proyek lokal Anda, kompres seluruh isinya (seperti `build`, `favicon.ico`, `index.php`, `.htaccess`), lalu unggah ke `/home/username/public_html/` dan ekstrak di sana.

### Langkah 3: Menyesuaikan Path di `index.php`

Karena file publik dipisahkan dari file inti Laravel, Anda harus memberi tahu `index.php` di mana letak berkas konfigurasi Laravel berada:

1.  Di cPanel File Manager, edit berkas `/home/username/public_html/index.php`.
2.  Cari baris berikut (biasanya di baris awal):
    ```php
    // Sebelum perubahan:
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    ```
3.  Ubah menjadi:
    ```php
    // Setelah perubahan (mengarahkan ke folder hadirku_app):
    require __DIR__.'/../hadirku_app/vendor/autoload.php';
    $app = require_once __DIR__.'/../hadirku_app/bootstrap/app.php';
    ```
4.  Simpan perubahan berkas.

### Langkah 4: Membuat & Mengimpor Database di cPanel

1.  Masuk ke menu **MySQL Database Wizard** di cPanel.
2.  **Langkah 1**: Buat database baru (misal: `username_hadirku`).
3.  **Langkah 2**: Buat user database baru (misal: `username_hadirkuuser`) dan buat kata sandi yang sangat kuat. Catat nama database, user, dan password ini.
4.  **Langkah 3**: Hubungkan user ke database tersebut dan centang **ALL PRIVILEGES**.
5.  Kembali ke halaman utama cPanel, buka **phpMyAdmin**.
6.  Pilih database baru Anda, klik tab **Import**, pilih berkas `hadirku.sql` yang Anda ekspor dari lokal, lalu klik **Go** atau **Import**.

### Langkah 5: Membuat Berkas `.env` di Hosting

1.  Buka cPanel File Manager dan masuk ke `/home/username/hadirku_app/`.
2.  Buat berkas baru bernama `.env` (atau edit berkas `.env` yang sudah ada).
3.  Sesuaikan pengaturannya dengan server hosting Anda:
    ```env
    APP_NAME=Hadirku
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://nama-domain-sekolah.sch.id   # URL domain asli Anda

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=username_hadirku                 # Database cPanel Anda
    DB_USERNAME=username_hadirkuuser             # User DB cPanel Anda
    DB_PASSWORD=PasswordKuatAnda                 # Sandi DB cPanel Anda
    
    # Simpan APP_KEY yang sama dari server lokal Anda untuk enkripsi data
    APP_KEY=base64:xxxx...
    ```

### Langkah 6: Membuat Tautan Simbolik (Symbolic Link Storage)

Di shared hosting, Anda tidak dapat menjalankan `php artisan storage:link` secara langsung jika hosting Anda tidak memiliki akses SSH / Terminal. Anda dapat memicu pembuatan symlink ini melalui **Cron Job** cPanel:

1.  Buka menu **Cron Jobs** di cPanel.
2.  Pada kolom **Add New Cron Job**, atur waktu menjadi **Once Per Minute** (atau isi bintang `*` pada menit dan biarkan yang lain kosong).
3.  Masukkan perintah berikut pada kolom command:
    ```bash
    ln -s /home/username/hadirku_app/storage/app/public /home/username/public_html/storage
    ```
    *(Ganti `username` sesuai nama user cPanel Anda).*
4.  Klik **Add New Cron Job**.
5.  Tunggu 1-2 menit hingga cron job tersebut tereksekusi sekali.
6.  Setelah file folder `storage` muncul di `/home/username/public_html/`, **SEGERA HAPUS** tugas Cron Job tersebut agar tidak membebani server secara terus-menerus.

### Langkah 7: Konfigurasi Scheduler Otomatis (Auto-Absent)

Aplikasi memiliki perintah scheduler untuk mengabsenkan siswa yang alpa secara otomatis setiap pukul 23:59. Anda wajib mendaftarkannya pada Cron Job cPanel agar berjalan otomatis setiap menit:

1.  Buka menu **Cron Jobs** di cPanel.
2.  Pilih pengaturan waktu menjadi **Once Per Minute** (`* * * * *`).
3.  Masukkan perintah berikut:
    ```bash
    cd /home/username/hadirku_app && php artisan schedule:run >> /dev/null 2>&1
    ```
    *(Beberapa hosting mengharuskan Anda menuliskan path PHP versi spesifik secara lengkap, misalnya `/usr/local/bin/php` atau `/usr/bin/ea-php82`)*.
4.  Klik **Add New Cron Job** untuk mengaktifkannya.

---

## 🔒 Tambahan: Memaksa Penggunaan SSL/HTTPS

Untuk menjamin kamera selfie dan fitur GPS geolokasi (geofencing) dapat berfungsi secara normal, **aplikasi wajib diakses melalui protokol HTTPS/SSL aman**. 

Tambahkan baris berikut pada berkas `/home/username/public_html/.htaccess` untuk mengarahkan pengguna secara otomatis dari HTTP ke HTTPS:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

---

## 🔍 Troubleshooting (Masalah yang Sering Terjadi)

### 1. Error 500 (Internal Server Error)
*   **Penyebab**: Folder `/home/username/hadirku_app/storage` atau `bootstrap/cache` tidak memiliki izin tulis (write permission).
*   **Solusi**: Ubah hak izin (permission) untuk folder `storage` dan `bootstrap/cache` beserta sub-foldernya di File Manager menjadi **775** atau **755**.

### 2. Gambar / Foto Selfie Tidak Muncul
*   **Penyebab**: Link simbolik storage rusak atau salah menunjuk path direktori.
*   **Solusi**: Hapus folder `storage` di `/home/username/public_html/` terlebih dahulu, lalu ulangi pembuatan symbolic link menggunakan Cron Job (Langkah 6) dengan jalur direktori absolut yang benar.

### 3. Halaman Putih Kosong (White Screen of Death)
*   **Penyebab**: Versi PHP webhosting di bawah PHP 8.2 atau ada ekstensi PHP yang belum aktif.
*   **Solusi**: Masuk ke menu **Select PHP Version** di cPanel, ubah ke **PHP 8.2** atau lebih tinggi, dan pastikan centang ekstensi `gd`, `fileinfo`, `pdo_mysql`, dan `zip`.
