<?php

namespace App\Services;

use App\Models\AccessLog;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class AccessLogService
{
    public static function log(string $accessType, ?Document $document = null): void
    {
        AccessLog::create([
            'user_id'        => Auth::id(),
            'document_id'    => $document?->id,
            'document_title' => $document?->title, // ⬅️ snapshot judul
            'access_type'    => $accessType,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'referrer'       => request()->headers->get('referer'),
            'access_datetime'=> now(),
        ]);
    }
}
