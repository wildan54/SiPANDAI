<?php

namespace App\Services;

use App\Models\AccessLog;
use Illuminate\Support\Facades\Auth;

class AccessLogService
{
    public static function log(string $accessType, ?int $documentId = null): void
    {
        AccessLog::create([
            'user_id'     => Auth::check() ? Auth::id() : null,
            'document_id' => $documentId,
            'access_type' => $accessType,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'referrer'    => request()->headers->get('referer'),
        ]);
    }
}