<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'tb_peminjaman'; 
    protected $guarded = [];

    // Menggunakan relasi rincian yang sudah Anda buat
    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}