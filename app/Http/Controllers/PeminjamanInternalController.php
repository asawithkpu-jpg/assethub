<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\PeminjamanNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Asset;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use Carbon\Carbon;

class PeminjamanInternalController extends Controller
{
    public function index()
    {
        // Inisialisasi query dengan relasi yang dibutuhkan
        $query = Peminjaman::with(['user', 'details.asset'])
            ->where('tipe_peminjaman', 'internal')
            ->latest();

        if (!auth()->user()->hasAnyRole(['administrator', 'kasubbag', 'pimpinan', 'operator'])) {
            $query->where('user_id', auth()->id());
        }

        $peminjamans = $query->get();

        return view('internal-peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $assets = Asset::where('status', 'aktif')
                    ->where('stok_tersedia', '>', 0)
                    ->get();
        return view('internal-peminjaman.create', compact('assets'));
    }

    public function store(Request $request)
    {
        // 0. Validasi Input
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tgl_pinjam'    => 'required|date',
            'tgl_kembali'   => 'required|date|after_or_equal:tgl_pinjam',
            'items'         => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Generate Kode Peminjaman: no/INV/ROMAN/YEAR
            $count = Peminjaman::whereYear('created_at', date('Y'))->count() + 1;
            $noUrut = str_pad($count, 3, '0', STR_PAD_LEFT);
            $romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $kode = $noUrut . '/INV/' . $romans[date('n')] . '/' . date('Y');

            // 2. Simpan Header
            $peminjaman = Peminjaman::create([
                'kode_peminjaman' => $kode,
                'tipe_peminjaman' => 'internal',
                'user_id'         => Auth::id(),
                'nama_kegiatan'   => $request->nama_kegiatan,
                'tgl_pinjam'      => $request->tgl_pinjam,
                'tgl_kembali'     => $request->tgl_kembali,
                'status'          => 'Menunggu acc Kasubbag', 
                'created_by'      => Auth::id(),
            ]);

            // 3. Simpan Detail menggunakan relasi agar lebih aman
            foreach ($request->items as $item) {
                // Gunakan PeminjamanDetail::create atau $peminjaman->rincian()->create
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'asset_id'      => $item['id'],
                    'qty'           => $item['qty'],
                    'satuan'        => $item['satuan'] ?? 'unit', // Default ke 'unit' jika tidak ada
                ]);
            }

            DB::commit();

            // try {
            //     $kasubbag = User::role('kasubbag')->get();
                
            //     // Pastikan variabel ini didefinisikan SEBELUM dipanggil di Notification::send
            //     $payload = [
            //         'id'      => $peminjaman->id,
            //         'title'   => 'Pengajuan Peminjaman Baru',
            //         'message' => auth()->user()->name . " mengajukan " . $peminjaman->kode_peminjaman,
            //         'type'    => 'internal',
            //         'url'     => route('peminjaman-internal.show', $peminjaman->id)
            //     ];

            //     if ($kasubbag->isNotEmpty()) {
            //         Notification::send($kasubbag, new PeminjamanNotification($payload));
            //     }
            // } catch (\Exception $e) {
            //     // Jika error, cek storage/logs/laravel.log untuk pesan detailnya
            //     \Log::error("Gagal kirim notifikasi: " . $e->getMessage());
            // }

            return response()->json([
                'success' => true, 
                'message' => 'Pengajuan ' . $kode . ' berhasil dikirim!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Return JSON agar tidak muncul error Unexpected Token <
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id, $newStatus)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('details')->findOrFail($id);
            
            // Logika stok saat barang diambil (dipinjam)
            if ($newStatus === 'Dipinjam' && $peminjaman->status !== 'Dipinjam') {
                foreach ($peminjaman->details as $detail) {
                    $asset = Asset::findOrFail($detail->asset_id);
                    if ($asset->stok_tersedia < $detail->qty) {
                        throw new \Exception("stok asset {$asset->nama_asset} tidak mencukupi.");
                    }
                    $asset->decrement('stok_tersedia', $detail->qty);
                    $asset->increment('stok_dipinjam', $detail->qty);
                }
            }

            $peminjaman->update(['status' => $newStatus]);
               
            // Notifikasi saat status berubah menjadi Dipinjam
            // if ($newStatus === 'Dipinjam') {
            //     $peminjaman->user->notify(new PeminjamanNotification([
            //         'id' => $peminjaman->id,
            //         'title' => 'Barang Telah Diambil',
            //         'message' => "Aset untuk " . $peminjaman->kode_peminjaman . " sudah dipinjam.",
            //         'type' => 'internal',
            //         'url' => route('peminjaman-internal.show', $peminjaman->id)
            //     ]));
            // }

            DB::commit();
            
            // Redirect dengan pesan sukses untuk Toastr
            return redirect()->route('peminjaman-internal.show', $id)
                ->with('success', 'status peminjaman berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        // Ambil data peminjaman beserta detail asetnya
        $peminjaman = Peminjaman::with(['user', 'details.asset'])->findOrFail($id);

        /**
         * Proteksi Akses:
         * Izinkan akses HANYA JIKA:
         * 1. User adalah pemilik data tersebut ($peminjaman->user_id == auth()->id())
         * 2. ATAU User memiliki role admin/kasubag/pimpinan
         */
        if ($peminjaman->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['administrator', 'kasubbag', 'pimpinan', 'operator'])) {
            // Berikan error 403 Forbidden jika tidak berhak
            abort(403, 'Anda tidak memiliki izin untuk melihat halaman ini.');
        }

        return view('internal-peminjaman.show', compact('peminjaman'));
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'Disetujui Kasubbag']);

        // Notifikasi ke peminjam bahwa pengajuan disetujui
        // $operators = User::role('operator')->get();
        // Notification::send($operators, new PeminjamanNotification([
        //     'id' => $peminjaman->id,
        //     'title' => 'Peminjaman Disetujui',
        //     'message' => "Segera siapkan aset untuk peminjaman: " . $peminjaman->kode_peminjaman . " oleh " . $peminjaman->user->name,
        //     'type' => 'internal',
        //     'url' => route('peminjaman-internal.show', $peminjaman->id)
        // ]));
        
        return redirect()->route('peminjaman-internal.show', $id)
            ->with('success', 'peminjaman telah disetujui kasubbag');
    }

    public function reject(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'Ditolak',
            'catatan_penolakan' => $request->catatan
        ]);

        // Notifikasi ke peminjam bahwa pengajuan ditolak beserta catatan penolakan
        // $peminjaman->user->notify(new PeminjamanNotification([
        //     'id' => $peminjaman->id,
        //     'title' => 'Pengajuan Ditolak',
        //     'message' => "Maaf, pengajuan " . $peminjaman->kode_peminjaman . " ditolak: " . $request->catatan,
        //     'type' => 'rejected',
        //     'url' => route('peminjaman-internal.show', $peminjaman->id)
        // ]));
        
        return redirect()->route('peminjaman-internal.show', $id)
            ->with('success', 'pengajuan telah ditolak');
    }

    public function uploadFotoBarang(Request $request, $detailId)
    {
        $request->validate([
            'foto_keluar' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $detail = PeminjamanDetail::with('peminjaman')->findOrFail($detailId);

        if ($request->hasFile('foto_keluar')) {
            $file = $request->file('foto_keluar');
            
            // Membersihkan kode peminjaman dari karakter '/' agar tidak merusak path
            $kodePinjam = str_replace('/', '-', $detail->peminjaman->kode_peminjaman);
            $tanggal = date('Ymd');
            $noUrut = $detailId;
            
            // Penamaan sesuai permintaan Anda: kodepeminjaman_tanggal_nourut_keluar.ext
            $filename = "{$kodePinjam}_{$tanggal}_{$noUrut}_keluar." . $file->getClientOriginalExtension();
            
            // Simpan ke storage/app/public/internal
            $file->storeAs('internal', $filename, 'public');
            
            $detail->update(['foto_keluar' => $filename]);
        }

        return back()->with('success', 'Foto barang berhasil diunggah');
    }

    public function cancel($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Pastikan hanya bisa dibatalkan jika status masih menunggu
        if ($peminjaman->status === 'Menunggu acc Kasubbag') {
            $peminjaman->update(['status' => 'Batal']);
            return back()->with('success', 'Pengajuan berhasil dibatalkan');
        }

        return back()->with('error', 'Pengajuan tidak dapat dibatalkan karena sudah diproses.');
    }

    public function deleteFoto($detailId)
    {
        $detail = PeminjamanDetail::findOrFail($detailId);
        
        if ($detail->foto_keluar) {
            // Hapus file dari storage/app/public/internal
            Storage::disk('public')->delete('internal/' . $detail->foto_keluar);
            
            // Update kolom di database menjadi null
            $detail->update(['foto_keluar' => null]);
        }

        return back()->with('success', 'Foto berhasil dihapus');
    }

    public function returnPage($id)
    {
        $peminjaman = Peminjaman::with('details.asset')->findOrFail($id);
        return view('internal-peminjaman.return', compact('peminjaman'));
    }

    public function processReturn(Request $request, $id)
    {
        // 1. Tambahkan validasi tanggal
        $request->validate([
            'tgl_kembali_real' => 'required|date',
            'items' => 'required|array'
        ]);

        $peminjaman = Peminjaman::with('details.asset')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            foreach ($peminjaman->details as $detail) {
                $input = $request->items[$detail->id];
                
                // Validasi jumlah
                $totalKembali = $input['baik'] + $input['rusak_ringan'] + $input['rusak_berat'];
                if ($totalKembali != $detail->qty) {
                    throw new \Exception("Total untuk {$detail->asset->nama_asset} tidak sesuai.");
                }

                // 2. Update Detail Peminjaman
                $detail->update([
                    'kembali_baik' => $input['baik'],
                    'kembali_rusak_ringan' => $input['rusak_ringan'],
                    'kembali_rusak_berat' => $input['rusak_berat'],
                ]);

                // 3. Update Stok di tb_assets
                $asset = $detail->asset;
                $asset->decrement('stok_dipinjam', $detail->qty);
                $asset->increment('stok_tersedia', $input['baik']);
                $asset->increment('rusak_ringan', $input['rusak_ringan']);
                $asset->increment('rusak_berat', $input['rusak_berat']);
            }

            // 4. Update status DAN tanggal kembali riil
            $peminjaman->update([
                'status' => 'Selesai',
                'tgl_kembali_real' => $request->tgl_kembali_real // Simpan tanggal dari input
            ]);

            // Notifikasi ke operator dan kasubbag bahwa pengembalian sudah selesai
            // $notifiedUsers = User::role(['operator', 'kasubbag'])->get();
            // Notification::send($notifiedUsers, new PeminjamanNotification([
            //     'id' => $peminjaman->id,
            //     'title' => 'Pengembalian Selesai',
            //     'message' => "Aset " . $peminjaman->kode_peminjaman . " telah dikembalikan.",
            //     'type' => 'internal',
            //     'url' => route('peminjaman-internal.show', $peminjaman->id)
            // ]));
            
            DB::commit();
            return redirect()->route('peminjaman-internal.index')->with('success', 'Berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function uploadFotoKembali(Request $request, $detailId)
    {
        $request->validate([
            'foto_kembali' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        // Load detail beserta data peminjaman induknya
        $detail = PeminjamanDetail::with('peminjaman')->findOrFail($detailId);

        if ($request->hasFile('foto_kembali')) {
            // Hapus foto lama jika ada
            if ($detail->foto_kembali) {
                Storage::disk('public')->delete('internal/' . $detail->foto_kembali);
            }

            $file = $request->file('foto_kembali');
            
            // Persiapan variabel untuk penamaan
            $kodePinjam = str_replace('/', '-', $detail->peminjaman->kode_peminjaman);
            $tanggal = date('Ymd');
            $noUrut = $detail->id; // Menggunakan ID detail sebagai nomor urut unik

            // Format: kodepeminjaman_tanggal_id_kembali.ext
            $filename = "{$kodePinjam}_{$tanggal}_{$noUrut}_kembali." . $file->getClientOriginalExtension();
            
            $file->storeAs('internal', $filename, 'public');
            
            $detail->update(['foto_kembali' => $filename]);
        }

        return back()->with('success', 'Foto kembali berhasil diunggah');
    }

    public function deleteFotoKembali($detailId)
    {
        $detail = PeminjamanDetail::findOrFail($detailId);
        
        if ($detail->foto_kembali) {
            Storage::disk('public')->delete('internal/' . $detail->foto_kembali);
            $detail->update(['foto_kembali' => null]);
        }

        return back()->with('success', 'Foto kembali berhasil dihapus');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Hanya boleh dihapus jika statusnya 'Batal'
        if ($peminjaman->status !== 'Batal') {
            return back()->with('error', 'Hanya data dengan status Batal yang dapat dihapus permanen.');
        }

        DB::beginTransaction();
        try {
            // Hapus detail terlebih dahulu baru header
            $peminjaman->details()->delete();
            $peminjaman->delete();

            DB::commit();
            return redirect()->route('peminjaman-internal.index')->with('success', 'Data peminjaman berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        $peminjaman = Peminjaman::with(['user', 'details.asset'])->findOrFail($id);
        $setting = Setting::first();

        \Carbon\Carbon::setLocale('id');
        
        $logoBase64 = null;
        // Gunakan logo dari database (image_7a5266.png kolom 7)
        // Jika tidak ada di DB, gunakan default kpu-logo.png
        $imageName = $setting->logo ?? 'kpu-logo.png'; 
        $path = public_path('images/' . $imageName);

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $details = $peminjaman->details;
        $filler = 15 - $details->count();

        $pdf = Pdf::loadView('internal-peminjaman.export-pdf', compact('peminjaman', 'setting', 'filler', 'logoBase64'));

        // Ukuran F4 (8.5 x 13 inch)
        $pdf->setPaper([0, 0, (8.5 * 72), (13 * 72)], 'portrait');
        
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true, // Aktifkan ini
            'isImageEnabled' => true,  // Paksa true
        ]);

        $fileName = 'Permohonan-Pinjam-' . str_replace(['/', '\\'], '-', $peminjaman->kode_peminjaman) . '.pdf';

        return $pdf->stream($fileName);
    }

    public function exportWord($id)
    {
        $peminjaman = Peminjaman::with(['user', 'details.asset'])->findOrFail($id);
        $setting = Setting::first(); // Sesuaikan dengan cara Anda mengambil data setting
        \Carbon\Carbon::setLocale('id');

        // Menyiapkan logo dalam format Base64 agar embed di Word
        $logoBase64 = null;
        $imageName = $setting->logo ?? 'kpu-logo.png'; 
        $path = public_path('images/' . $imageName);
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $details = $peminjaman->details;
        $filler = 15 - $details->count();

        // Nama file sesuai referensi Anda
        $safeKode = str_replace(['/', '\\'], '-', $peminjaman->kode_peminjaman);
        $filename = "Permohonan_Pinjam_" . $safeKode . ".doc";

        // Header untuk memaksa download sebagai file Word
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('internal-peminjaman.export-word', compact('peminjaman', 'setting', 'filler', 'logoBase64'));
    }
}