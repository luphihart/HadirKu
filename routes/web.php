<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\DataKelas;
use App\Livewire\Admin\DataMurid;
use App\Livewire\Admin\RekapPresensi;
use App\Livewire\Admin\PersetujuanIzin;
use App\Livewire\Admin\KalenderSekolah;
use App\Livewire\Admin\Pengumuman;
use App\Livewire\Admin\Pengaturan;
use App\Livewire\Admin\Profil as AdminProfil;
use App\Livewire\Murid\Dashboard as MuridDashboard;
use App\Livewire\Murid\Presensi;
use App\Livewire\Murid\PengajuanIzin;
use App\Livewire\Murid\Profil as MuridProfil;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
    Route::get('/login', Login::class)->name('login');
});

// Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/kelas', DataKelas::class)->name('kelas');
    Route::get('/murid', DataMurid::class)->name('murid');
    Route::get('/rekap-presensi', RekapPresensi::class)->name('rekap-presensi');
    Route::get('/persetujuan-izin', PersetujuanIzin::class)->name('persetujuan-izin');
    Route::get('/kalender', KalenderSekolah::class)->name('kalender');
    Route::get('/pengumuman', Pengumuman::class)->name('pengumuman');
    Route::get('/pengaturan', Pengaturan::class)->name('pengaturan');
    Route::get('/profil', AdminProfil::class)->name('profil');
});

// Murid routes
Route::middleware(['auth', 'role:murid'])->prefix('murid')->name('murid.')->group(function () {
    Route::get('/dashboard', MuridDashboard::class)->name('dashboard');
    Route::get('/presensi', Presensi::class)->name('presensi');
    Route::get('/pengajuan-izin', PengajuanIzin::class)->name('pengajuan-izin');
    Route::get('/profil', MuridProfil::class)->name('profil');
});

