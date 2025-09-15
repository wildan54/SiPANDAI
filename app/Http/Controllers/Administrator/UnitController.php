<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{

        public function index()
    {
        $units = Unit::withCount('documents')
             ->latest()
             ->get();

        return view('bidang.index', compact('units'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:100|unique:units,name',
            'slug' => 'nullable|string|max:100|unique:units,slug',
            'description' => 'nullable|string',
        ]);

        // Membuat data baru, slug otomatis jika tidak diisi
        Unit::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('bidang.index')
                         ->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('bidang.modal_edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:100|unique:units,name,' . $unit->id,
            'slug' => 'nullable|string|max:100|unique:units,slug,' . $unit->id,
            'description' => 'nullable|string',
        ]);

        // Update data
        $unit->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('bidang.index')
                         ->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('bidang.index')
                         ->with('success', 'Bidang dan semua dokumen terkait berhasil dihapus.');
    }
}