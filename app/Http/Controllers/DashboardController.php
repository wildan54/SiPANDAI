<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $totalDocuments   = Document::count();
        $totalCategories  = DocumentCategory::count();
        $totalTypes       = DocumentType::count();
        $totalUnits       = Unit::count();
        
        // Dokumen baru minggu ini
        $newDocumentsThisWeek = Document::whereBetween('upload_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        
        // 5 Dokumen terbaru
        $latestDocuments = Document::latest()->take(5)->get();
        
        // 5 Kategori terbaru
        $latestCategories = DocumentCategory::latest()->take(5)->get();
        
        // 5 Tipe terbaru
        $latestTypes = DocumentType::latest()->take(5)->get();
        
        // Chart kategori
        $chartKategoriLabels = DocumentCategory::orderBy('id')->pluck('name');
        $chartKategoriData   = DocumentCategory::withCount('documents')
                                              ->orderBy('id')
                                              ->pluck('documents_count');

        // Statistik akses user_id = 1
        $totalAccessUser1 = DB::table('access_logs')
            ->where('user_id', 1)
            ->count();

        // Top 5 dokumen dilihat user 1 (hanya yang ada judulnya)
        $topViewedDocsUser1 = DB::table('access_logs')
            ->select('document_id', 'document_title', DB::raw('COUNT(*) as total_view'))
            ->where('user_id', 1)
            ->where('access_type', 'view')
            ->whereNotNull('document_title')          // judul tidak null
            ->where('document_title', '<>', '')       // judul tidak kosong
            ->groupBy('document_id', 'document_title')
            ->orderByDesc('total_view')
            ->limit(5)
            ->get();


        // Top 5 dokumen diunduh user 1
        $topDownloadedDocsUser1 = DB::table('access_logs')
            ->select('document_id', 'document_title', DB::raw('COUNT(*) as total_download'))
            ->where('user_id', 1)
            ->where('access_type', 'download')
            ->groupBy('document_id', 'document_title')
            ->orderByDesc('total_download')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalDocuments',
            'totalCategories',
            'totalTypes',
            'totalUnits',
            'newDocumentsThisWeek',
            'latestDocuments',
            'latestCategories',
            'latestTypes',
            'chartKategoriLabels',
            'chartKategoriData',
            'totalAccessUser1',
            'topViewedDocsUser1',
            'topDownloadedDocsUser1'
        ));
    }
}