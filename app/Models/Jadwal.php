<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_tutor',
        'id_sekolah',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id_tutor');
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Menghitung durasi dalam jam.
     */
    public function getDurasiJamAttribute(): float
    {
        $mulai = \Carbon\Carbon::parse($this->jam_mulai);
        $selesai = \Carbon\Carbon::parse($this->jam_selesai);
        return $selesai->diffInMinutes($mulai) / 60;
    }
}
