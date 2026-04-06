<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryPeminjamController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // REDIRECT OTOMATIS JIKA ROLE PEMINJAM
        if ($user->hasRole('peminjam-internal')) {
            return redirect()->route('history-peminjam.show', ['type' => 'internal', 'identifier' => $user->subbagian]);
        } 
        elseif ($user->hasRole('peminjam-eksternal')) {
            return redirect()->route('history-peminjam.show', ['type' => 'eksternal', 'identifier' => $user->instansi]);
        }

        // UNTUK ADMIN/MANAJEMEN: Ambil daftar Subbagian (Internal)
        $internalGroups = User::whereNotNull('subbagian')
            ->select('subbagian as name', DB::raw("'internal' as type"))
            ->groupBy('subbagian')
            ->get();

        // UNTUK ADMIN/MANAJEMEN: Ambil daftar Instansi (Eksternal)
        $externalGroups = User::whereNotNull('instansi')
            // TAMBAHKAN BARIS DI BAWAH INI
            ->where('instansi', '!=', 'KPU Kabupaten Pasuruan') 
            ->select('instansi as name', DB::raw("'eksternal' as type"))
            ->groupBy('instansi')
            ->get();

        $groups = $internalGroups->concat($externalGroups);

        return view('history-peminjam.index', compact('groups'));
    }

    public function show($type, $identifier)
    {
        $user = auth()->user();

        // Proteksi akses
        if (!$user->hasRole(['administrator', 'pimpinan', 'kasubbag', 'operator'])) {
            if ($type == 'internal' && $user->subbagian !== $identifier) abort(403);
            if ($type == 'eksternal' && $user->instansi !== $identifier) abort(403);
        }

        // Ambil data Peminjaman (bukan PeminjamanDetail) agar 1 baris = 1 transaksi
        $history = Peminjaman::with(['user', 'details.asset'])
            ->whereIn('status', ['Selesai', 'Dipinjam'])
            ->where('tipe_peminjaman', $type)
            ->whereHas('user', function($q) use ($type, $identifier) {
                if ($type == 'internal') {
                    $q->where('subbagian', $identifier);
                } else {
                    $q->where('instansi', $identifier);
                }
            })
            ->latest()
            ->get();

        return view('history-peminjam.show', compact('history', 'type', 'identifier'));
    }
}