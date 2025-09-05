<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentCategoryController extends Controller
{
    public function kategori()
    {
        // Implement your logic here
        return view('documents.kategori');
    }
}