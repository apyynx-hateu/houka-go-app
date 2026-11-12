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
        'jumlah_sesi',
        'tarif_per_sesi',
        'fee_check_in',
        'fee_transport',
        'penyesuaian_honor',
        'total_honor',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'periode_bulan' => 'date',
            'jumlah_sesi' => 'integer',
            'tarif_per_sesi' => 'decimal:2',
            'fee_check_in' => 'decimal:2',
            'fee_transport' => 'decimal:2',
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
        return ($this->jumlah_sesi * $this->tarif_per_sesi) + $this->fee_check_in + $this->fee_transport + $this->penyesuaian_honor;
    }
}
