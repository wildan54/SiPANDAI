<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentType extends Model
{
    use HasFactory;

    protected $table = 'document_types';

    protected $fillable = [
        'name',
        'description',
        'slug',
        'document_category_id',
    ];

    /**
     * Relasi ke kategori dokumen.
     */
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    /**
     * Relasi ke dokumen.
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'document_type_id');
    }
}