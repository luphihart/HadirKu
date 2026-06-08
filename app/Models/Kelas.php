<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas'];

    public function murid(): HasMany
    {
        return $this->hasMany(User::class, 'kelas_id')->where('role', 'murid');
    }

    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'target_kelas_id');
    }
}
