<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $table = 'document_categories';

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    /**
     * Relasi ke dokumen lewat type_documents.
     */
    public function documents()
    {
        return $this->hasManyThrough(
            Document::class,       // model akhir
            DocumentType::class,   // model perantara
            'document_category_id', // FK di type_documents → document_categories.id
            'document_type_id',     // FK di documents → type_documents.id
            'id',                   // PK di document_categories
            'id'                    // PK di type_documents
        );
    }
}