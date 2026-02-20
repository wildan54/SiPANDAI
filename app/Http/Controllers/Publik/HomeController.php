<?php

namespace App\Http\Controllers\Publik;

use App\Models\Unit;
use App\Models\Document;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use App\Models\DocumentCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        /**
         * ===============================
         * STATISTIK
         * ===============================
         */

        // Total dokumen publik & approved
        $total_documents = Document::where('visibility', 'public')
            ->where('status', 'approved')
            ->count();

        // Total unit
        $total_units = Unit::count();

        // Total unduhan (access_logs type = download)
        $total_downloads = AccessLog::where('access_type', 'download')->count();

        /**
         * ===============================
         * KATEGORI DOKUMEN (Document Types)
         * ===============================
         */
        $featured_categories = DocumentCategory::withCount([
                'documents as documents_count' => function ($q) {
                    $q->where('visibility', 'public')
                      ->where('status', 'approved');
                }
            ])
            ->orderBy('name')
            ->get();

        /**
         * ===============================
         * DOKUMEN TERBARU
         * ===============================
         */
        $latest_documents = Document::with('unit')
            ->where('visibility', 'public')
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        /**
         * ===============================
         * DASAR HUKUM PUBLIKASI
         * ===============================
         */
        $legalBases = [
            1 => [ // id document_types
                
            ],
            2 => [
                
            ],
            3 => [
                
            ],
            4 => [
                
            ],
        ];

        /**
         * ===============================
         * POPULER (Kategori Terbanyak Download)
         * ===============================
         */
        $popularTypes = \App\Models\DocumentType::select(
                'document_types.id',
                'document_types.name',
                'document_types.slug',
                DB::raw('COUNT(access_logs.id) as total_downloads')
            )
            ->join('documents', function ($join) {
                $join->on('documents.document_type_id', '=', 'document_types.id')
                    ->where('documents.visibility', 'public')
                    ->where('documents.status', 'approved');
            })
            ->join('access_logs', function ($join) {
                $join->on('access_logs.document_id', '=', 'documents.id')
                    ->where('access_logs.access_type', 'download');
            })
            ->groupBy(
                'document_types.id',
                'document_types.name',
                'document_types.slug'
            )
            ->orderByDesc('total_downloads')
            ->take(3)
            ->get();


        return view('public.home', compact(
            'total_documents',
            'total_units',
            'total_downloads',
            'featured_categories',
            'latest_documents',
            'legalBases',
            'popularTypes'
        ));
    }

    public function search(Request $request)
{
    $request->validate([
        'keyword' => 'required|string|min:3'
    ]);

    $keyword = $request->keyword;

    $documents = Document::public()
        ->with(['unit', 'type'])
        ->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        })
        ->orderBy('year', 'desc')
        ->limit(20)
        ->get();

    return redirect()->route('public.documents.index', [
        'q' => $keyword,
        'from' => 'hero'
    ]);
}

}