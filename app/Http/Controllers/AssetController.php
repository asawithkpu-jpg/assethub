<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::latest()->get();
        return view('assets.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_asset'    => 'required',
            'kode_asset'    => 'required|unique:tb_assets,kode_asset',
            'kategori'      => 'required',
            'stok_tersedia' => 'required|numeric',
            'rusak_ringan'  => 'required|numeric',
            'rusak_berat'   => 'required|numeric',
            'lokasi'        => 'required',
            'status'        => 'required|in:aktif,nonaktif',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // Membuat nama file: KODE-NAMA.ekstensi (Contoh: PKM-0001-Infocus-Proyektor.jpg)
            // Str::slug digunakan agar spasi jadi tanda hubung dan menghapus karakter unik
            $namaFileCustom = $request->kode_asset . '-' . Str::slug($request->nama_asset) . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke folder 'barang' dengan nama kustom tersebut
            $data['foto'] = $file->storeAs('barang', $namaFileCustom, 'public');
        }

        Asset::create($data);
        return redirect()->back()->with('success', 'Asset berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);
        
        $request->validate([
            'nama_asset'    => 'required',
            'kode_asset'    => 'required|unique:tb_assets,kode_asset,'.$id,
            'kategori'      => 'required',
            'stok_tersedia' => 'required|numeric',
            'rusak_ringan'  => 'required|numeric',
            'rusak_berat'   => 'required|numeric',
            'lokasi'        => 'required',
            'status'        => 'required|in:aktif,nonaktif',
            // 'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            'foto'          => 'nullable|image|max:2048'
        ]);

        // Ambil semua data input kecuali foto dulu
        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage
            if ($asset->foto) {
                Storage::disk('public')->delete($asset->foto);
            }

            $file = $request->file('foto');
            $namaFileCustom = $request->kode_asset . '-' . Str::slug($request->nama_asset) . '.' . $file->getClientOriginalExtension();
            
            // Simpan file baru
            $path = $file->storeAs('barang', $namaFileCustom, 'public');
            $data['foto'] = $path;
        }

        $asset->update($data);
        return redirect()->back()->with('success', 'Asset berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        
        // Cek apakah asset pernah dipinjam
        $pernahDipinjam = DB::table('tb_peminjaman_detail')->where('asset_id', $id)->exists();

        if ($pernahDipinjam) {
            // Jika ada histori, jangan hapus fisik, cukup nonaktifkan
            $asset->update(['status' => 'nonaktif']);
            return redirect()->back()->with('success', 'Asset memiliki histori peminjaman, status diubah menjadi Nonaktif.');
        }

        // Jika benar-benar data baru dan belum pernah dipinjam, boleh hapus fisik
        if ($asset->foto) Storage::disk('public')->delete($asset->foto);
        $asset->delete();
        
        return redirect()->back()->with('success', 'Asset berhasil dihapus permanen.');
    }

    // API untuk mendapatkan kode otomatis (digunakan Alpinejs di View)
    public function getNextCode($kategori)
    {
        $prefix = ($kategori == "Peralatan Kantor dan Mesin") ? "PKM" : "LP";
        
        // Cari asset terakhir berdasarkan kategori untuk menentukan nomor urut
        $lastAsset = Asset::where('kategori', $kategori)
                          ->where('kode_asset', 'like', $prefix . '-%')
                          ->orderBy('kode_asset', 'desc') // Urutkan berdasarkan kode terbesar
                          ->first();

        if ($lastAsset) {
            // Ambil 4 angka terakhir, contoh PKM-0005 diambil 5
            $lastNumber = (int) substr($lastAsset->kode_asset, -4);
            // Tambah 1 dan format jadi 4 digit, contoh 5 jadi 0006
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada barang di kategori ini
            $newNumber = "0001";
        }

        return response()->json(['code' => $prefix . '-' . $newNumber]);
    }
}