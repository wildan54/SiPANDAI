<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    public $timestamps = false;

    use HasFactory;

    // Eloquent: created_at = upload_date
    const CREATED_AT = 'upload_date';
    const UPDATED_AT = 'updated_at';

    /**
     * ==========================
     * STATUS CONSTANTS
     * ==========================
     */
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_REJECTED  = 'rejected';

    /**
     * ==========================
     * VISIBILITY CONSTANTS
     * ==========================
     */
    public const VISIBILITY_PRIVATE = 'private';
    public const VISIBILITY_PUBLIC  = 'public';

    /**
     * ==========================
     * MASS ASSIGNMENT
     * ==========================
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'document_type_id',
        'unit_id',
        'slug',
        'meta_title',
        'meta_description',
        'year',
        'file_source',
        'file_path',
        'file_size',
        'file_type',
        'file_embed',
        'uploaded_by',
        'upload_date',
        'status',
        'visibility',
    ];

    protected $casts = [
        'upload_date' => 'datetime',
    ];

    /**
     * ==========================
     * MODEL EVENTS UNtUK SLUG OTOMATIS
     * ==========================
     */
    protected static function booted()
    {
        // Saat akan INSERT
        static::creating(function ($document) {
            if (empty($document->slug)) {
                $document->slug = Str::slug($document->title);
            }
        });

        // Setelah INSERT (ID sudah ada)
        static::created(function ($document) {
            if (static::where('slug', $document->slug)->count() > 1) {
                $document->update([
                    'slug' => $document->slug . '-' . $document->id
                ]);
            }
        });

        static::deleting(function ($document) {

            // hanya untuk file upload
            if (
                $document->file_source === 'upload'
                && $document->file_path
                && Storage::disk('public')->exists($document->file_path)
            ) {
                Storage::disk('public')->delete($document->file_path);
            }

        });
    }


    /**
     * ==========================
     * RELATIONSHIPS
     * ==========================
     */
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

    public function approvals()
    {
        return $this->hasMany(DocumentApproval::class, 'document_id');
    }

    public function latestApproval()
    {
        return $this->hasOne(DocumentApproval::class, 'document_id')->latestOfMany();
    }

    /**
     * ==========================
     * QUERY SCOPES
     * ==========================
     */
    public function scopePublic($query)
    {
        return $query->where('status', self::STATUS_APPROVED)
                     ->where('visibility', self::VISIBILITY_PUBLIC);
    }

    public function scopePrivate($query)
    {
        return $query->where('visibility', self::VISIBILITY_PRIVATE);
    }

    /**
     * ==========================
     * ATTRIBUTE HELPERS
     * ==========================
     */
    public function isEmbed(): bool
    {
        return $this->file_source === 'embed' && !empty($this->file_embed);
    }

    public function isUpload(): bool
    {
        return $this->file_source === 'upload' && !empty($this->file_path);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
