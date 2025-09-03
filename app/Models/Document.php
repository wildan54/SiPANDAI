<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    // kasih tahu Eloquent bahwa created_at = upload_date
    const CREATED_AT = 'upload_date';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'title',
        'description',
        'document_type_id',
        'unit_id',
        'slug',
        'meta_title',
        'meta_description',
    ];

    protected $guarded = [
        'id',
        'file_path',
        'file_size',
        'file_type',
        'upload_date',
        'uploaded_by',
    ];

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
}