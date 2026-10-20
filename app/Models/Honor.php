<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Honor extends Model
{
    protected $table = 'honor';
    protected $primaryKey = 'id_honor';

    protected $fillable = [
        'id_tutor',
        'periode_bulan',
        'tarif_per_jam',
        'jumlah_siswa',
        'total_jam_mengajar',
        'penyesuaian_honor',
        'total_honor',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'periode_bulan' => 'date',
            'tarif_per_jam' => 'decimal:2',
            'total_jam_mengajar' => 'decimal:2',
            'penyesuaian_honor' => 'decimal:2',
            'total_honor' => 'decimal:2',
        ];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id_tutor');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranHonor::class, 'id_honor', 'id_honor');
    }

    public function pembayaranBerhasil(): HasMany
    {
        return $this->hasMany(PembayaranHonor::class, 'id_honor', 'id_honor')
                     ->where('status', 'Berhasil');
    }

    public function getTotalDibayarAttribute(): float
    {
        return (float) $this->pembayaranBerhasil()->sum('jumlah');
    }

    public function getSisaPembayaranAttribute(): float
    {
        return (float) $this->total_honor - $this->total_dibayar;
    }

    public function hitungTotal(): float
    {
        return ($this->tarif_per_jam * $this->total_jam_mengajar) + $this->penyesuaian_honor;
    }
}
