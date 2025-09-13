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
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'last_active',
        'role',
    ];

    /**
     * Kolom yang disembunyikan ketika serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password'    => 'hashed',
            'last_active' => 'datetime',
        ];
    }

    /**
     * Cek apakah user sedang online.
     */
    public function isOnline(): bool
    {
        return $this->last_active && $this->last_active->greaterThan(now()->subMinutes(5));
    }

    /**
     * Relasi ke access logs.
     */
    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class, 'uploaded_by');
    }

    /**
     * Relasi ke dokumen.
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    /**
     * Hapus access log saat user dihapus.
     */
    protected static function booted()
    {
        static::deleting(function ($user) {
            foreach ($user->accessLogs as $log) {
                $log->forceDelete(); // bypass proteksi delete() di AccessLog
            }
        });
    }
}
