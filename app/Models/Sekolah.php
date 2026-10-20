<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sekolah extends Model
{
    protected $table = 'sekolah';
    protected $primaryKey = 'id_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'ruangan',
        'kapasitas',
        'status_aktif',
    ];

    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_sekolah', 'id_sekolah');
    }

    public function isAktif(): bool
    {
        return $this->status_aktif === 'Aktif';
    }

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', 'Aktif');
    }
}
