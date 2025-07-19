<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'nip',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: User memiliki banyak transaksi barang
     */
    public function transaksiBarang(): HasMany
    {
        return $this->hasMany(TransaksiBarang::class);
    }

    /**
     * Helper untuk mengecek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return method_exists($this, 'hasRole') && $this->hasRole('admin');
    }

    /**
     * Helper untuk mengecek apakah user adalah user biasa
     */
    public function isUser(): bool
    {
        return method_exists($this, 'hasRole') && $this->hasRole('user');
    }
}
