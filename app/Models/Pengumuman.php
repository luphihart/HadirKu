<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'user_id', 'judul', 'konten', 'target',
        'target_kelas_id', 'target_murid_id', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function targetKelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'target_kelas_id');
    }

    public function targetMurid(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_murid_id');
    }

    public function scopeForMurid($query, User $murid)
    {
        return $query->where('is_active', true)
            ->where(function ($q) use ($murid) {
                $q->where('target', 'semua')
                  ->orWhere(function ($q2) use ($murid) {
                      $q2->where('target', 'kelas')
                         ->where('target_kelas_id', $murid->kelas_id);
                  })
                  ->orWhere(function ($q2) use ($murid) {
                      $q2->where('target', 'murid')
                         ->where('target_murid_id', $murid->id);
                  });
            });
    }
}
