<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutor extends Authenticatable
{
    protected $table = 'tutor';
    protected $primaryKey = 'id_tutor';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'email',
        'pendidikan_terakhir',
        'status_kerja',
        'tanggal_bergabung',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'tanggal_lahir' => 'date',
            'tanggal_bergabung' => 'date',
        ];
    }

    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_tutor', 'id_tutor');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_tutor', 'id_tutor');
    }

    public function honor(): HasMany
    {
        return $this->hasMany(Honor::class, 'id_tutor', 'id_tutor');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'id_tutor', 'id_tutor');
    }

    public function isAktif(): bool
    {
        return $this->status_kerja === 'Aktif';
    }

    public function scopeAktif($query)
    {
        return $query->where('status_kerja', 'Aktif');
    }

    public function scopeTidakAktif($query)
    {
        return $query->where('status_kerja', 'Tidak Aktif');
    }
}
