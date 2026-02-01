<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi mass-assignment.
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'last_active',
        'role',
        'unit_id', // ✅ ganti dari 'unit'
    ];

    /**
     * Kolom yang disembunyikan ketika serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected $casts = [
        'password'    => 'hashed',
        'last_active' => 'datetime',
    ];

    /**
     * =====================
     * RELATIONS
     * =====================
     */

    /**
     * User belongs to Unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Relasi ke access logs
     */
    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class, 'user_id');
    }

    /**
     * Relasi ke dokumen yang diupload
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    /**
     * =====================
     * HELPERS
     * =====================
     */

    /**
     * Cek apakah user sedang online
     */
    public function isOnline(): bool
    {
        return $this->last_active &&
               $this->last_active->greaterThan(now()->subMinutes(5));
    }

    /**
     * Helper role
     */
    public function isAdmin(): bool
    {
        return $this->role === 'administrator';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    /**
     * =====================
     * MODEL EVENTS
     * =====================
     */

    /**
     * Hapus access log saat user dihapus
     */
    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->accessLogs()->forceDelete();
        });
    }
}