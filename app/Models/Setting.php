<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Karena nama tabel Anda menggunakan awalan 'tb_', 
    // kita harus mendefinisikannya secara eksplisit
    protected $table = 'tb_settings';

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'nama_instansi',
        'alamat',
        'telepon1',
        'telepon2',
        'email',
        'logo',
        'nama_kasubbag',
        'nip_kasubbag',
        'jabatan_kasubbag',
    ];
}