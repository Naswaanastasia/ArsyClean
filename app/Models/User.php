<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';

    protected $primaryKey = 'id_user';

    // Tabel tidak memiliki kolom updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'kode_user',
        'nama_user',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
        'status',
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

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_user', 'id_user');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_user', 'id_user');
    }
}
