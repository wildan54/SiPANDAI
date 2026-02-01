<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Cek akses administrator
     */
    private function checkAdminAccess()
    {
        if (!Auth::check() || Auth::user()->role !== 'administrator') {
            abort(403, 'Akses ditolak.');
        }
    }

    /**
     * Tampilkan daftar user
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();

        $search = $request->input('search');

        $units = Unit::orderBy('name')->get();

        $users = User::with('unit')
            ->withCount('documents')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users', 'search','units'));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        $this->checkAdminAccess();

        $units = Unit::orderBy('name')->get();

        return view('users.create', compact('units'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:administrator,editor',
            'unit_id'  => 'required_if:role,editor|nullable|exists:units,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'role'     => $request->role,
            'unit_id'  => $request->role === 'editor' ? $request->unit_id : null,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user
     */
    public function edit(User $user)
    {
        $this->checkAdminAccess();

        $units = Unit::orderBy('name')->get();

        return view('users.edit', compact('user', 'units'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $this->checkAdminAccess();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:administrator,editor',
            'unit_id'  => 'required_if:role,editor|nullable|exists:units,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'role'     => $request->role,
            'unit_id'  => $request->role === 'editor' ? $request->unit_id : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user
     */
    public function destroy(Request $request, User $user)
    {
        $this->checkAdminAccess();

        // Cegah admin hapus diri sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak bisa menghapus diri sendiri.');
        }

        $request->validate([
            'action'    => 'required|in:delete,move',
            'target_id' => 'required_if:action,move|exists:users,id|different:' . $user->id,
        ]);

        if ($user->documents()->count() > 0) {
            if ($request->action === 'delete') {
                $user->documents()->delete();
            } else {
                // Pindahkan kepemilikan dokumen
                $user->documents()->update([
                    'uploaded_by' => $request->target_id
                ]);
            }
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}