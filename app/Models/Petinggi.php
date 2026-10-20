<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petinggi extends Authenticatable
{
    protected $table = 'petinggi';
    protected $primaryKey = 'id_petinggi';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'jabatan',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'id_petinggi', 'id_petinggi');
    }

    public function permintaanLaporan(): HasMany
    {
        return $this->hasMany(PermintaanLaporan::class, 'id_petinggi', 'id_petinggi');
    }

    public function isAktif(): bool
    {
        return $this->status_aktif === 'Aktif';
    }

    public function hasRole(string $role): bool
    {
        return $this->jabatan === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->jabatan, $roles);
    }
}
