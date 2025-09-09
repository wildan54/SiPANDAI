<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    // Eloquent: created_at = upload_date
    const CREATED_AT = 'upload_date';
    const UPDATED_AT = 'updated_at';

    // Mass assignment
    protected $fillable = [
        'title',
        'description',
        'document_type_id',
        'unit_id',
        'slug',
        'meta_title',
        'meta_description',
        'year',
        'file_source',   // 'embed' atau 'upload'
        'file_embed',    // link cloud jika embed
        'uploaded_by',   // <- tambahkan ini
    ];

    protected $guarded = [
        'id',
        'file_path',     // opsional jika upload
        'file_size',
        'file_type',
        'upload_date',   // diisi otomatis
    ];

    // RELASI
    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class, 'document_id');
    }

    // ATTRIBUTE HELPER
    public function isEmbed(): bool
    {
        return $this->file_source === 'embed' && !empty($this->file_embed);
    }

    public function isUpload(): bool
    {
        return $this->file_source === 'upload' && !empty($this->file_path);
    }
}