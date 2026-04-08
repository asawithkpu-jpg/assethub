<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter Tanggal (Default: Bulan Berjalan)
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();

        $user = auth()->user();
        
        // Cek Role
        $isAdmin = $user->hasRole('administrator');
        $isPimpinan = $user->hasRole('pimpinan');
        $isKasubbag = $user->hasRole('kasubbag');
        $isOperator = $user->hasRole('operator');
        $isStaff = $isAdmin || $isPimpinan || $isKasubbag || $isOperator;

        // Inisialisasi variabel agar tidak error di view
        $unitQtyChart = collect();
        $userQtyChart = collect();
        $qtyChart = collect();
        $unitStats = collect();
        $approvalItems = collect();
        $returnSchedules = collect();

        if ($isStaff) {
            // Card Stats Staff
            $stats = [
                'total_asset' => Asset::count(),
                'total_unit_stok' => Asset::sum('stok_tersedia') + Asset::sum('stok_dipinjam'),
                'total_pinjam' => Peminjaman::whereBetween('tgl_pinjam', [$startDate, $endDate])->count(),
                'unit_internal' => PeminjamanDetail::whereHas('peminjaman', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('tgl_pinjam', [$startDate, $endDate])->where('tipe_peminjaman', 'internal');
                })->sum('qty'),
                'unit_internal_count' => Peminjaman::where('tipe_peminjaman', 'internal')->whereBetween('tgl_pinjam', [$startDate, $endDate])->count(),
                'unit_eksternal' => PeminjamanDetail::whereHas('peminjaman', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('tgl_pinjam', [$startDate, $endDate])->where('tipe_peminjaman', 'eksternal');
                })->sum('qty'),
                'unit_eksternal_count' => Peminjaman::where('tipe_peminjaman', 'eksternal')->whereBetween('tgl_pinjam', [$startDate, $endDate])->count(),
                'sedang_dipinjam' => Peminjaman::where('status', 'Dipinjam')->count(),
                'total_selesai' => Peminjaman::where('status', 'Selesai')->whereBetween('tgl_pinjam', [$startDate, $endDate])->count(),
            ];

            // alert untuk jadwal pengembalian hari ini dan besok
            if ($user->can('proses-peminjaman') || $user->can('persetujuan-kasubbag')) {
                $today = Carbon::today();
                $tomorrow = Carbon::tomorrow();
                
                $returnSchedules = Peminjaman::with(['user', 'details.asset'])
                    ->where('status', 'Dipinjam')
                    ->whereDate('tgl_kembali', '<=', $today) // Hari ini atau sudah lewat (terlambat)
                    ->orWhere(function($q) use ($tomorrow) {
                        $q->where('status', 'Dipinjam')
                        ->whereDate('tgl_kembali', $tomorrow); // H+1 dari hari ini (atau H-1 dari sisi tgl_kembali)
                    })
                    ->orderBy('tgl_kembali', 'asc')
                    ->get();
            }

            // Chart Bar: Frekuensi per Unit (Subbag/Instansi)
            $unitStats = Peminjaman::whereBetween('tgl_pinjam', [$startDate, $endDate])
                ->join('tb_users', 'tb_peminjaman.user_id', '=', 'tb_users.id')
                ->select(DB::raw("CASE WHEN tipe_peminjaman = 'internal' THEN tb_users.subbagian ELSE tb_users.instansi END as unit_name"), DB::raw('count(*) as total'))
                ->groupBy('unit_name')->orderBy('total', 'desc')->get();

            // Chart Donut: Top 5 Asset
            $qtyChart = PeminjamanDetail::select('asset_id', DB::raw('SUM(qty) as total'))
                ->whereHas('peminjaman', fn($q) => $q->whereBetween('tgl_pinjam', [$startDate, $endDate]))
                ->with('asset')->groupBy('asset_id')->orderBy('total', 'desc')->take(5)->get();

            // Logika Approval Table
            if ($isAdmin || $isPimpinan || $isOperator) {
                $approvalItems = $approvalItems->merge(Peminjaman::with('user')->where('status', 'Menunggu acc Pimpinan')->get());
            }
            if ($isAdmin || $isKasubbag || $isOperator) {
                $approvalItems = $approvalItems->merge(Peminjaman::with('user')->where('status', 'Menunggu acc Kasubbag')->get());
            }
            if ($isAdmin || $isOperator) {
                $approvalItems = $approvalItems->merge(Peminjaman::with('user')->where('status', 'Disetujui Kasubbag')->get());
            }
            $approvalItems = $approvalItems->unique('id');

        } else {
            // LOGIKA PEMINJAM (Split Data User vs Unit)
            // $unitField = ($user->tipe_peminjaman == 'internal') ? 'subbagian' : 'instansi';
            $unitField = ($user->subbagian != null) ? 'subbagian' : 'instansi';
            $unitValue = $user->$unitField;

            $stats = [
                // Data Spesifik User
                'user_total' => Peminjaman::where('user_id', $user->id)->whereBetween('tgl_pinjam', [$startDate, $endDate])->count(),
                'user_dipinjam' => Peminjaman::where('user_id', $user->id)->where('status', 'Dipinjam')->count(),
                'user_selesai' => Peminjaman::where('user_id', $user->id)->where('status', 'Selesai')->whereBetween('tgl_pinjam', [$startDate, $endDate])->count(),
                
                // Data Satu Subbagian / Instansi
                'unit_total' => Peminjaman::join('tb_users', 'tb_peminjaman.user_id', '=', 'tb_users.id')
                                ->where('tb_users.'.$unitField, $unitValue)
                                ->whereBetween('tgl_pinjam', [$startDate, $endDate])->count(),
                'unit_dipinjam' => Peminjaman::join('tb_users', 'tb_peminjaman.user_id', '=', 'tb_users.id')
                                ->where('tb_users.'.$unitField, $unitValue)
                                ->where('status', 'Dipinjam')->count(),
                'unit_selesai' => Peminjaman::join('tb_users', 'tb_peminjaman.user_id', '=', 'tb_users.id')
                                ->where('tb_users.'.$unitField, $unitValue)
                                ->where('status', 'Selesai')->whereBetween('tgl_pinjam', [$startDate, $endDate])->count(),
            ];

            // Pie Chart Unit (Subbagian/Instansi)
            $unitQtyChart = PeminjamanDetail::whereHas('peminjaman', function($q) use ($unitField, $unitValue, $startDate, $endDate) {
                    $q->join('tb_users', 'tb_peminjaman.user_id', '=', 'tb_users.id')
                      ->where('tb_users.'.$unitField, $unitValue)
                      ->whereBetween('tgl_pinjam', [$startDate, $endDate]);
                })
                ->select('asset_id', DB::raw('SUM(qty) as total'))
                ->with('asset')->groupBy('asset_id')->orderBy('total', 'desc')->take(5)->get();

            // Pie Chart User Terkait
            $userQtyChart = PeminjamanDetail::whereHas('peminjaman', function($q) use ($user, $startDate, $endDate) {
                    $q->where('user_id', $user->id)->whereBetween('tgl_pinjam', [$startDate, $endDate]);
                })
                ->select('asset_id', DB::raw('SUM(qty) as total'))
                ->with('asset')->groupBy('asset_id')->orderBy('total', 'desc')->take(5)->get();
        }

        // 10 Aktivitas Terakhir
        $queryLatest = Peminjaman::with('user');

        if (!$isStaff) {
            // Filter berdasarkan unit kerja user (Subbagian atau Instansi)
            // $unitField = ($user->tipe_peminjaman == 'internal') ? 'subbagian' : 'instansi';
            $unitField = ($user->subbagian != null) ? 'subbagian' : 'instansi';
            $unitValue = $user->$unitField;

            $queryLatest->whereHas('user', function($q) use ($unitField, $unitValue) {
                $q->where($unitField, $unitValue);
            });
        }

        $latestPeminjaman = $queryLatest->orderBy('tgl_pinjam', 'desc')
            ->take(10)->get();

        return view('dashboard', compact(
            'stats', 'qtyChart', 'unitStats', 'latestPeminjaman', 
            'approvalItems', 'isStaff', 'startDate', 'endDate',
            'unitQtyChart', 'userQtyChart', 'returnSchedules'
        ));
    }

    public function manualBook()
    {
        return view('manual-book'); 
    }
}