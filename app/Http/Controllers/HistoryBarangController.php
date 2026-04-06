<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryBarangController extends Controller
{
    public function index()
    {
        // Mengambil semua asset untuk ditampilkan di gallery card
        $assets = Asset::all();
        return view('history-barang.index', compact('assets'));
    }

    public function show($id)
    {
        $asset = Asset::findOrFail($id);
        $user = auth()->user();

        $historyQuery = PeminjamanDetail::with(['peminjaman.user'])
            ->where('asset_id', $id)
            ->whereHas('peminjaman', function($query) use ($user) {
                $query->whereIn('status', ['Selesai', 'Dipinjam']);

                // Jika bukan jajaran manajemen/admin, maka filter scope datanya
                if (!$user->hasRole(['administrator', 'pimpinan', 'kasubbag', 'operator'])) {
                    
                    $query->whereHas('user', function($q) use ($user) {
                        if ($user->hasRole('peminjam-internal')) {
                            $q->where('subbagian', $user->subbagian);
                        } 
                        elseif ($user->hasRole('peminjam-eksternal')) {
                            $q->where('instansi', $user->instansi);
                        }
                    });
                    
                }
            });

        $history = $historyQuery->get();

        return view('history-barang.show', compact('asset', 'history'));
    }
}