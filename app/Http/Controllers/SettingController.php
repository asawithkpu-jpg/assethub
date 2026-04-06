<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Mengambil data pertama atau membuat instance baru jika kosong
        $setting = Setting::first() ?? new Setting();
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first() ?? new Setting();

        $request->validate([
            'nama_instansi' => 'required',
            'telepon1' => 'nullable',
            'telepon2' => 'nullable',
            'email' => 'required|email',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Logika upload logo ke public/images/
            $file = $request->file('logo');
            $filename = 'kpu-logo.png'; // Sesuai permintaan lokasi file Anda
            $file->move(public_path('images'), $filename);
            $data['logo'] = $filename;
        }

        $setting->fill($data)->save();

        return back()->with('success', 'Informasi instansi berhasil diperbarui!');
    }
}