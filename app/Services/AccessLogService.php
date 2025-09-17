<?php

namespace App\Services;

use App\Models\AccessLog;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class AccessLogService
{
    protected const GUEST_USER_ID = 1; // ID user dummy Guest

    public static function log(string $accessType, ?Document $document = null): void
    {
        AccessLog::create([
            'user_id'        => Auth::id() ?? self::GUEST_USER_ID,
            'document_id'    => $document?->id,
            'document_title' => $document?->title, // snapshot judul
            'access_type'    => $accessType,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'referrer'       => request()->headers->get('referer'),
            'access_datetime'=> now(),
        ]);
    }
}