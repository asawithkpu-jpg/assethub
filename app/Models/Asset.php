<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model {
    protected $table = 'tb_assets';
    
    // Pastikan status dimasukkan di sini
    protected $fillable = [
        'kode_asset', 'nama_asset', 'foto', 'kategori', 
        'stok_tersedia', 'stok_dipinjam', 'rusak_ringan', 
        'rusak_berat', 'lokasi', 'status'
    ];
}