<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessLog extends Model
{
    use HasFactory;

    public $timestamps = false; // nonaktifkan default created_at & updated_at

    protected $fillable = [
        'user_id',
        'document_id',
        'access_type',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    // isi otomatis kolom access_datetime
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->access_datetime)) {
                $model->access_datetime = now();
            }
        });
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    // 🔒 Proteksi supaya log tidak bisa diubah
    public function update(array $attributes = [], array $options = [])
    {
        throw new \Exception("AccessLog entries cannot be updated.");
    }

    // 🔒 Proteksi supaya log tidak bisa dihapus
    public function delete()
    {
        throw new \Exception("AccessLog entries cannot be deleted.");
    }
}
