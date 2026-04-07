<?php

namespace App\Http\Controllers;

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

class PeminjamanEksternalController extends Controller
{
    public function index()
    {
        $query = Peminjaman::with(['user', 'details.asset'])
            ->where('tipe_peminjaman', 'eksternal')
            ->latest();

        // Spatie & Ownership Filter
        if (!auth()->user()->hasAnyRole(['administrator', 'kasubbag', 'pimpinan', 'operator'])) {
            $query->where('user_id', auth()->id());
        }

        $peminjamans = $query->get();
        return view('eksternal-peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $assets = Asset::where('status', 'aktif')->where('stok_tersedia', '>', 0)->get();
        return view('eksternal-peminjaman.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip_nik' => 'required',
            'hp' => 'required',
            'jabatan' => 'required',
            'instansi' => 'required',
            'nama_kegiatan' => 'required',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'nomor_surat' => 'required',
            'tgl_surat' => 'required|date',
            'file_surat' => 'required|mimes:pdf|max:5120',
            'items' => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        try {
            // 1. UPDATE DATA USER LOGIN
            $user = auth()->user();
            $user->update([
                'nip_nik'   => $request->nip_nik,
                'hp'        => $request->hp,
                'jabatan'   => $request->jabatan,
                'instansi'  => $request->instansi,
            ]);

            // 1. Generate Kode Unik Sementara
            $bulanRomawi = $this->getRomawi(date('m', strtotime($request->tgl_pinjam)));
            $tahun = date('Y', strtotime($request->tgl_pinjam));
            $randomPrefix = rand(1000, 9999);
            $kode = "{$randomPrefix}/B.INV/KPU.KAB.PAS/{$bulanRomawi}/{$tahun}";

            // 2. Upload PDF
            $file = $request->file('file_surat');
            $noSuratClean = str_replace(['/', '\\'], '-', $request->nomor_surat);
            $fileName = $request->tgl_pinjam . '_' . 
                        $request->instansi . '_' . 
                        $noSuratClean . '_' . 
                        $request->tgl_surat . '.' . 
                        $file->getClientOriginalExtension();
            $file->storeAs('eksternal/surat', $fileName, 'public');

            // 3. Simpan Header
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'kode_peminjaman' => $kode,
                'nama_kegiatan' => $request->nama_kegiatan,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'nomor_surat' => $request->nomor_surat,
                'tgl_surat' => $request->tgl_surat,
                'file_surat' => $fileName,
                'tipe_peminjaman' => 'eksternal',
                'status' => 'Menunggu acc Pimpinan'
            ]);

            // 4. Simpan Detail
            foreach ($request->items as $item) {
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'asset_id' => $item['asset_id'],
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan']
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pengajuan eksternal berhasil dikirim']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'details.asset'])->findOrFail($id);
        return view('eksternal-peminjaman.show', compact('peminjaman'));
    }

    public function updateKode(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        if($peminjaman->status == 'Selesai') return back()->with('error', 'Data selesai tidak bisa diedit');

        $parts = explode('/', $peminjaman->kode_peminjaman);
        array_shift($parts); 
        $suffix = implode('/', $parts);
        
        $peminjaman->update(['kode_peminjaman' => $request->prefix_kode . '/' . $suffix]);
        return back()->with('success', 'Nomor urut berhasil diperbarui');
    }

    public function approvePimpinan($id)
    {
        Peminjaman::findOrFail($id)->update(['status' => 'Menunggu acc Kasubbag']);
        return redirect()->route('peminjaman-eksternal.index')->with('success', 'Disetujui Pimpinan, menunggu Kasubbag');
    }

    public function rejectPimpinan(Request $request, $id)
    {
        Peminjaman::findOrFail($id)->update([
            'status' => 'Ditolak',
            'catatan_penolakan' => $request->catatan
        ]);
        return back()->with('success', 'Pengajuan ditolak Pimpinan');
    }

    public function approveKasubbag($id)
    {
        Peminjaman::findOrFail($id)->update(['status' => 'Disetujui Kasubbag']);
        return redirect()->route('peminjaman-eksternal.index')->with('success', 'Persetujuan kasubbag berhasil');
    }

    public function rejectKasubbag(Request $request, $id)
    {
        Peminjaman::findOrFail($id)->update([
            'status' => 'Ditolak',
            'catatan_penolakan' => $request->catatan
        ]);
        return back()->with('success', 'Pengajuan ditolak Kasubbag');
    }

    public function cancel($id)
    {
        $p = Peminjaman::findOrFail($id);
        if($p->status == 'Dipinjam') return back()->with('error', 'Sudah dipinjam tidak bisa batal');
        $p->update(['status' => 'Batal']);
        return back()->with('success', 'Pengajuan berhasil dibatalkan');
    }

    public function destroy($id)
    {
        $p = Peminjaman::findOrFail($id);
        if($p->status != 'Menunggu acc Pimpinan' && $p->status != 'Batal') {
            return back()->with('error', 'Hanya pengajuan awal atau batal yang bisa dihapus');
        }
        
        if($p->file_surat) Storage::disk('public')->delete('eksternal/surat/'.$p->file_surat);
        $p->details()->delete();
        $p->delete();
        return redirect()->route('peminjaman-eksternal.index')->with('success', 'Data berhasil dihapus');
    }

    private function getRomawi($month) {
        $map = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        return $map[(int)$month] ?? 'I';
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
            DB::commit();
            
            // Redirect dengan pesan sukses untuk Toastr
            return redirect()->route('peminjaman-eksternal.show', $id)
                ->with('success', 'status peminjaman berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // --- Method Return & Foto (Sama dengan internal tapi folder storage berbeda) ---
    public function returnForm($id) {
        $peminjaman = Peminjaman::with('details.asset')->findOrFail($id);
        return view('eksternal-peminjaman.return', compact('peminjaman'));
    }

    public function processReturn(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'tgl_kembali_real' => 'required|date',
            'items' => 'required|array'
        ]);

        // Sesuaikan model dengan yang digunakan untuk Eksternal
        $peminjaman = Peminjaman::with('details.asset')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            foreach ($peminjaman->details as $detail) {
                // Mengambil input berdasarkan ID detail dari array items
                $input = $request->items[$detail->id];
                
                // Validasi jumlah total pengembalian harus sama dengan jumlah pinjam (qty)
                $totalKembali = $input['baik'] + $input['rusak_ringan'] + $input['rusak_berat'] + $input['hilang'];
                if ($totalKembali != $detail->qty) {
                    throw new \Exception("Total pengembalian untuk {$detail->asset->nama_asset} tidak sesuai dengan jumlah pinjam.");
                }

                // 2. Update Detail Peminjaman (Menggunakan nama kolom yang benar sesuai tb_peminjaman_detail)
                $detail->update([
                    'kembali_baik' => $input['baik'],
                    'kembali_rusak_ringan' => $input['rusak_ringan'],
                    'kembali_rusak_berat' => $input['rusak_berat'],
                    'kembali_hilang' => $input['hilang'],
                ]);

                // 3. Update Stok di tb_assets
                $asset = $detail->asset;
                // Kurangi stok yang sedang dipinjam
                $asset->decrement('stok_dipinjam', $detail->qty);
                // Tambahkan kembali ke stok tersedia hanya yang kondisinya baik
                $asset->increment('stok_tersedia', $input['baik']);
                // Tambahkan ke akumulasi rusak jika ada
                $asset->increment('rusak_ringan', $input['rusak_ringan']);
                $asset->increment('rusak_berat', $input['rusak_berat']);
                $asset->increment('hilang', $input['hilang']);
            }

            // 4. Update status dan tanggal kembali riil di tabel utama
            $peminjaman->update([
                'status' => 'Selesai',
                'tgl_kembali_real' => $request->tgl_kembali_real
            ]);
            
            DB::commit();
            return redirect()->route('peminjaman-eksternal.index', $id)->with('success', 'Proses pengembalian berhasil disimpan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto_keluar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $detail = PeminjamanDetail::with('peminjaman')->findOrFail($id);

        if ($request->hasFile('foto_keluar')) {
            // Hapus foto lama jika ada
            if ($detail->foto_keluar && Storage::disk('public')->exists('eksternal/foto/' . $detail->foto_keluar)) {
                Storage::disk('public')->delete('eksternal/foto/' . $detail->foto_keluar);
            }

            $file = $request->file('foto_keluar');
            
            // Logika Nama File: KodePeminjaman_Tanggal_NoUrutBaris_keluar
            // Ubah / menjadi - pada kode peminjaman
            $cleanKode = str_replace('/', '-', $detail->peminjaman->kode_peminjaman);
            $tanggal = date('Ymd');
            $nama_file = $cleanKode . '_' . $tanggal . '_' . $detail->id . '_keluar.' . $file->getClientOriginalExtension();

            // Simpan ke storage/eksternal/foto/
            $file->storeAs('eksternal/foto', $nama_file, 'public');

            $detail->update(['foto_keluar' => $nama_file]);
        }

        return back()->with('success', 'Foto berhasil diunggah');
    }

    public function deleteFoto($id)
    {
        $detail = PeminjamanDetail::findOrFail($id);

        if ($detail->foto_keluar && Storage::disk('public')->exists('eksternal/foto/' . $detail->foto_keluar)) {
            Storage::disk('public')->delete('eksternal/foto/' . $detail->foto_keluar);
        }

        $detail->update(['foto_keluar' => null]);

        return back()->with('success', 'Foto berhasil dihapus');
    }

    public function uploadFotoKembali(Request $request, $id)
    {
        $request->validate([
            'foto_kembali' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $detail = PeminjamanDetail::with('peminjaman')->findOrFail($id);

        if ($request->hasFile('foto_kembali')) {
            // 1. Hapus foto lama jika ada
            if ($detail->foto_kembali) {
                Storage::disk('public')->delete('eksternal/foto/' . $detail->foto_kembali);
            }

            // 2. Olah komponen nama file
            $kodeClean = str_replace('/', '-', $detail->peminjaman->kode_peminjaman);
            $tglPinjam = \Carbon\Carbon::parse($detail->peminjaman->tgl_pinjam)->format('Ymd');
            $noUrut = $detail->id;

            // 3. Susun nama file baru: KodePeminjaman_tanggalpinjam_nourutbaris_kembali
            $file = $request->file('foto_kembali');
            $extension = $file->getClientOriginalExtension();
            $filename = "{$kodeClean}_{$tglPinjam}_{$noUrut}_kembali.{$extension}";

            // 4. Simpan foto
            $file->storeAs('eksternal/foto', $filename, 'public');

            // 5. Update database
            $detail->update([
                'foto_kembali' => $filename
            ]);
        }

        return back()->with('success', 'Foto pengembalian berhasil diunggah.');
    }

    public function deleteFotoKembali($id)
    {
        $detail = PeminjamanDetail::findOrFail($id);

        if ($detail->foto_kembali) {
            // Hapus dari storage
            Storage::disk('public')->delete('eksternal/foto/' . $detail->foto_kembali);
            
            // Hapus dari database
            $detail->update([
                'foto_kembali' => null
            ]);

            return back()->with('success', 'Foto pengembalian berhasil dihapus.');
        }

        return back()->with('error', 'Foto tidak ditemukan.');
    }

    public function exportPdf($id)
    {
        $peminjaman = Peminjaman::with(['details.asset', 'user'])->findOrFail($id);
        $setting = Setting::first(); 
        \Carbon\Carbon::setLocale('id');

        // Persiapan Logo
        $logoBase64 = null;
        $path = public_path('images/' . ($setting->logo ?? 'kpu-logo.png'));
        if (file_exists($path)) {
            $logoData = base64_encode(file_get_contents($path));
            $logoBase64 = 'data:image/png;base64,' . $logoData;
        }

        // Logic filler baris (menyesuaikan agar tabel terlihat penuh seperti Gambar 1)
        $filler = max(0, 10 - $peminjaman->details->count());

        // Bersihkan karakter "/" agar tidak error
        $safeFilename = str_replace(['/', '\\'], '-', $peminjaman->kode_peminjaman);

        $pdf = \Pdf::loadView('eksternal-peminjaman.export-pdf', compact('peminjaman', 'setting', 'logoBase64', 'filler'))
                ->setPaper([0, 0, (8.5 * 72), (13 * 72)], 'portrait'); 

        return $pdf->stream('Tanda-Terima-' . $safeFilename . '.pdf');
    }

    public function exportWord($id)
    {
        $peminjaman = Peminjaman::with(['details.asset', 'user'])->findOrFail($id);
        $setting = Setting::first();
        \Carbon\Carbon::setLocale('id');

        $logoBase64 = null;
        $path = public_path('images/' . ($setting->logo ?? 'kpu-logo.png'));
        if (file_exists($path)) {
            $logoData = base64_encode(file_get_contents($path));
            $logoBase64 = 'data:image/png;base64,' . $logoData;
        }

        $filler = max(0, 10 - $peminjaman->details->count());
        $safeFilename = str_replace(['/', '\\'], '-', $peminjaman->kode_peminjaman);

        $headers = [
            "Content-type" => "application/vnd.ms-word",
            "Content-Disposition" => "attachment;Filename=Tanda-Terima-" . $safeFilename . ".doc",
            "Pragma" => "no-cache",
            "Expires" => "0"
        ];

        // Mengarahkan ke view export-word
        return response()->view('eksternal-peminjaman.export-word', compact('peminjaman', 'setting', 'logoBase64', 'filler'), 200, $headers);
    }

    public function uploadFormPinjam(Request $request, $id)
    {
        $request->validate([
            'file_form_pinjam' => 'required|mimes:pdf|max:2048',
        ]);

        $peminjaman = Peminjaman::with('user')->findOrFail($id);

        // Format Nama: tglpinjam_instansi_nosurat.pdf
        $tgl = Carbon::parse($peminjaman->tgl_pinjam)->format('Ymd');
        $instansi = str_replace(['/', '\\'], '-', $peminjaman->user->instansi);
        $noSurat = str_replace(['/', '\\'], '-', $peminjaman->kode_peminjaman);

        $fileName = "{$tgl}_{$instansi}_{$noSurat}.pdf";
        $path = 'eksternal/dokumen';

        if ($peminjaman->file_form_pinjam) {
            Storage::disk('public')->delete($path . '/' . $peminjaman->file_form_pinjam);
        }

        $request->file('file_form_pinjam')->storeAs($path, $fileName, 'public');

        $peminjaman->update(['file_form_pinjam' => $fileName]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function deleteFormPinjam($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->file_form_pinjam) {
            Storage::disk('public')->delete('eksternal/dokumen/' . $peminjaman->file_form_pinjam);
            $peminjaman->update(['file_form_pinjam' => null]);
        }

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}