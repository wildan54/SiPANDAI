<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\User;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    private function checkAdminAccess()
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role !== 'administrator') {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index(Request $request)
    {
        $query = AccessLog::with(['user', 'document'])
            ->where('user_id', '!=', 1); // 🚫 Kecualikan Guest

        // 🔍 Filter User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 🔍 Filter Jenis Aksi
        if ($request->filled('access_type')) {
            $query->where('access_type', $request->access_type);
        }

        // 🔍 Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('access_datetime', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('access_datetime', '<=', $request->end_date);
        }

        $logs = $query->orderByDesc('access_datetime')
            ->paginate(20)
            ->withQueryString();

        // 🚫 Drop user Guest dari dropdown
        $users = User::where('id', '!=', 1)
            ->orderBy('name')
            ->get();

        return view('users.access_logs', compact('logs', 'users'));
    }
}
