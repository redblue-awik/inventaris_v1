<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('user', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'departemen' => 'nullable|string|max:255',
            'role' => 'required|in:admin,staf,gudang',
        ],
        [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar, Tolong gunakan nama lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role harus salah satu dari: admin, staf, gudang.',
        ]
        );

        User::create($request->all());
        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'departemen' => 'nullable|string|max:255',
            'role' => 'required|in:admin,staf,gudang',
        ]);

        $updateData = $request->all();
        if (empty($updateData['password'])) {
            unset($updateData['password']);
        }

        $user->update($updateData);
        return back()->with('success', 'Data User berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}
