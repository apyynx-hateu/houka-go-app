<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'id_penilaian';

    protected $fillable = [
        'id_tutor',
        'id_petinggi',
        'tanggal_penilaian',
        'periode',
        'kualitas_mengajar',
        'catatan_kinerja',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penilaian' => 'date',
        ];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id_tutor');
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(Petinggi::class, 'id_petinggi', 'id_petinggi');
    }
}
