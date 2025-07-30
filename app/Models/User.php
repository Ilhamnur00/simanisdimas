<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\HasDatabaseNotifications;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasDatabaseNotifications;

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
     * Relasi: User memiliki banyak device
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Relasi tidak langsung: User memiliki banyak maintenance melalui device
     */
    public function maintenances(): HasManyThrough
    {
        return $this->hasManyThrough(Maintenance::class, Device::class);
    }

    /**
     * Helper untuk mengecek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return method_exists($this, 'hasRole') && $this->hasRole('admin');
    }

    public function kendaraans()
    {
        return $this->hasMany(\App\Models\Kendaraan::class);
    }


    /**
     * Helper untuk mengecek apakah user adalah user biasa
     */
    public function isUser(): bool
    {
        return method_exists($this, 'hasRole') && $this->hasRole('user');
    }

    
}
