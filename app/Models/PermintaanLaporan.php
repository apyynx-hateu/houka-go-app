<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanLaporan extends Model
{
    protected $table = 'permintaan_laporan';
    protected $primaryKey = 'id_permintaan';

    protected $fillable = [
        'id_petinggi',
        'waktu_permintaan',
        'jenis',
        'periode_awal',
        'periode_akhir',
        'keterangan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'waktu_permintaan' => 'datetime',
            'periode_awal' => 'date',
            'periode_akhir' => 'date',
        ];
    }

    public function peminta(): BelongsTo
    {
        return $this->belongsTo(Petinggi::class, 'id_petinggi', 'id_petinggi');
    }
}
