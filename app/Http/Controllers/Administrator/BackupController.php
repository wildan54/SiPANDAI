<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller

{
    public function index()
    {
        $files = Storage::files('SiPANDAI');

        return view('backup.index', compact('files'));
    }

    public function run()
    {
        dispatch(function () {
            \Artisan::call('backup:run');
        });

        return back()->with('success', 'Backup berhasil, silakan refresh halaman untuk melihat hasilnya!');
    }

    public function download($file)
    {
        return Storage::download('SiPANDAI/' . $file);
    }

    public function delete($file)
    {
        Storage::delete('SiPANDAI/' . $file);

        return back()->with('success', 'Backup berhasil dihapus!');
    }
}