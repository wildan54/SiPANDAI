<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Unit;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik
        $totalDocuments = Document::count();
        $totalCategories = DocumentCategory::count();
        $totalTypes = DocumentType::count();
        $totalUnits = Unit::count();
        
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
        $latestTypes = Document::latest()->take(5)->get();
        
        // Chart kategori
        $chartKategoriLabels = DocumentCategory::pluck('name'); // nama kategori
        $chartKategoriData = DocumentCategory::withCount('documents')->pluck('documents_count'); // jumlah dokumen per kategori


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
            'chartKategoriData'
        ));
    }
}