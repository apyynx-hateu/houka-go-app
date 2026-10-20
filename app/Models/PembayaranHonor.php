<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranHonor extends Model
{
    protected $table = 'pembayaran_honor';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_honor',
        'tanggal_pembayaran',
        'jumlah',
        'nomor_rekening',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pembayaran' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    public function honor(): BelongsTo
    {
        return $this->belongsTo(Honor::class, 'id_honor', 'id_honor');
    }

    /**
     * Menampilkan nomor rekening yang disamarkan.
     */
    public function getNomorRekeningSamarAttribute(): string
    {
        if (empty($this->nomor_rekening)) {
            return '-';
        }
        $len = strlen($this->nomor_rekening);
        if ($len <= 4) {
            return str_repeat('*', $len);
        }
        return str_repeat('*', $len - 4) . substr($this->nomor_rekening, -4);
    }
}
