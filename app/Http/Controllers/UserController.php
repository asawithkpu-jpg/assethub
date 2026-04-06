<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nip_nik' => 'required|unique:tb_users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'nip_nik' => $request->nip_nik,
            'subbagian' => $request->subbagian,
            'jabatan' => $request->jabatan,  
            'instansi' => $request->instansi,
            'hp' => $request->hp,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'nip_nik' => 'required|unique:tb_users,nip_nik,'.$id,
            'role' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'nip_nik' => $request->nip_nik,
            'subbagian' => $request->subbagian,
            'jabatan' => $request->jabatan,  
            'instansi' => $request->instansi,
            'hp' => $request->hp,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

    public function profileEdit()
    {
        // Mengambil data user yang sedang login
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = User::findOrFail(auth()->id());

        $request->validate([
            'name' => 'required',
            'nip_nik' => 'required|unique:tb_users,nip_nik,'.$user->id,
            'password' => 'nullable|min:6|confirmed', 
        ], [
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.'
        ]);

        try {
            $data = [
                'name' => $request->name,
                'nip_nik' => $request->nip_nik,
                'subbagian' => $request->subbagian,
                'jabatan' => $request->jabatan,  
                'instansi' => $request->instansi,
                'hp' => $request->hp,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->back()->with('success', 'Berhasil menyimpan perubahan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data');
        }
    }
}