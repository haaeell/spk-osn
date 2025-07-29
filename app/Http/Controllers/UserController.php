<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'kategori_mapel' => 'nullable'
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'kategori_mapel' => $data['kategori_mapel']
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
            'kategori_mapel' => 'nullable'
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update([
            'name' => $data['nama'],
            'email' => $data['email'],
            'role' => $data['role'],
            'kategori_mapel' => $data['kategori_mapel']
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
