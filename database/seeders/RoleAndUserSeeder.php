<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run()
    {
        // 1. Reset cache Spatie agar perubahan permission langsung aktif
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definisi Permission berdasarkan matriks Akses & Aksi (df43a3.png)
        $permissions = [
            'dashboard', 'master-asset', 'peminjaman-internal', 'peminjaman-eksternal',
            'history-barang', 'history-peminjam', 'persetujuan-pimpinan', 'persetujuan-kasubbag',
            'user-management', 'role-akses', 'pengembalian-barang', 
            'edit-data', 'delete-data'
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // 3. Buat Role dan Assign Permission sesuai Matriks df43a3.png
        
        // Administrator
        $roleAdmin = Role::updateOrCreate(['name' => 'administrator']);
        $roleAdmin->syncPermissions(Permission::all());

        // Pimpinan
        $rolePimpinan = Role::updateOrCreate(['name' => 'pimpinan']);
        $rolePimpinan->syncPermissions(['dashboard', 'persetujuan-pimpinan', 'history-barang', 'history-peminjam', 'edit-data', 'delete-data']);

        // Kasubbag
        $roleKasubbag = Role::updateOrCreate(['name' => 'kasubbag']);
        $roleKasubbag->syncPermissions(['dashboard', 'master-asset', 'persetujuan-kasubbag', 'history-barang', 'history-peminjam', 'edit-data', 'delete-data']);

        // Operator
        $roleOperator = Role::updateOrCreate(['name' => 'operator']);
        $roleOperator->syncPermissions(['dashboard', 'master-asset', 'history-barang', 'history-peminjam', 'pengembalian-barang', 'edit-data', 'delete-data']);

        // Peminjam Internal
        $roleInternal = Role::updateOrCreate(['name' => 'peminjam-internal']);
        $roleInternal->syncPermissions(['dashboard', 'peminjaman-internal', 'history-barang', 'history-peminjam', 'edit-data', 'delete-data']);

        // Peminjam Eksternal
        $roleEksternal = Role::updateOrCreate(['name' => 'peminjam-eksternal']);
        $roleEksternal->syncPermissions(['dashboard', 'peminjaman-eksternal', 'history-barang', 'history-peminjam', 'edit-data', 'delete-data']);

        // 4. Buat User Default (Data Anda)
        $adminUser = User::updateOrCreate(
            ['nip_nik' => '19950424'],
            [
                'name' => 'AssetHub',
                'jabatan' => 'Penata Kelola Sistem dan Teknologi Informasi',
                'subbagian' => 'Keuangan, Umum, dan Logistik',
                'instansi' => 'KPU Kabupaten Pasuruan',
                'hp' => '08970349910',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->syncRoles(['administrator']);

        $userInternal = User::updateOrCreate(
            ['nip_nik' => '20000525'],
            [
                'name' => 'David Wijaya Mahendra',
                'jabatan' => 'Penata Kelola Pemilihan Umum Ahli Pertama',
                'subbagian' => 'Parmas dan SDM',
                'instansi' => 'KPU Kabupaten Pasuruan',
                'password' => Hash::make('password'),
            ]
        );
        $userInternal->syncRoles(['peminjam-internal']);
    }
}