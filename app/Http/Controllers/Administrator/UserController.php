<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Cek akses administrator
     */
    private function checkAdminAccess()
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role !== 'administrator') {
            abort(403, 'Akses ditolak.');
        }
    }

    /**
     * Tampilkan daftar user.
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $search = $request->input('search');

        $users = User::query()
            ->withCount('documents') // hitung jumlah dokumen
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Form tambah user.
     */
    public function create()
    {
        $this->checkAdminAccess();
        return view('users.create');
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:administrator,editor',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user.
     */
    public function edit(User $user)
    {
        $this->checkAdminAccess();
        return view('users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        $this->checkAdminAccess();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:administrator,editor',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only(['name', 'username', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user dengan opsi hapus/pindahkan dokumen.
     */
    public function destroy(Request $request, User $user)
    {
        $this->checkAdminAccess();

        // 🔒 Cegah admin menghapus dirinya sendiri
        if ($user->id === optional(auth()->user)->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus diri sendiri.');
        }
        // Validasi pilihan hapus atau pindahkan
        $request->validate([
            'action'    => 'required|in:delete,move',
            'target_id' => 'required_if:action,move|exists:users,id',
        ]);

        $action = $request->input('action'); // 'delete' atau 'move'
        $targetUserId = $request->input('target_id'); // jika move

        if ($user->documents()->count() > 0) {
            if ($action === 'delete') {
                // Hapus semua dokumen terkait
                $user->documents()->delete();
            } elseif ($action === 'move') {
                // Pindahkan dokumen ke user lain
                $user->documents()->update(['user_id' => $targetUserId]);
            }
        }

        // AccessLog ikut terhapus otomatis lewat hook di model User
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
