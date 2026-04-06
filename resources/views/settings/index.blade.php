@extends('layouts.app')

@section('content')
<div class="w-full">
    <div class="mb-4">
        <nav class="flex mb-1" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-[10px] font-bold uppercase tracking-wider">
                <li class="text-gray-400">PENGATURAN</li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                        <span class="text-primary">INFORMASI</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h2 class="text-lg font-extrabold text-gray-800 dark:text-white">Informasi Instansi</h2>
    </div>

    <div class="bg-white dark:bg-darkCard rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-4">Logo Instansi</p>
                        <img src="{{ asset('images/' . ($setting->logo ?? 'kpu-logo.png')) }}" alt="Logo" class="h-24 mb-4 object-contain">
                        <input type="file" name="logo" class="text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-2 text-[9px] text-gray-400 italic">Format: PNG/JPG. Maks 2MB</p>
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2 group">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Nama Instansi</label>
                            <input type="text" name="nama_instansi" value="{{ old('nama_instansi', $setting->nama_instansi) }}" class="w-full mt-1 px-3 py-1.5 text-[12px] bg-transparent border border-gray-200 dark:border-gray-600 rounded focus:border-primary focus:ring-0 dark:text-white transition-all">
                        </div>

                        <div class="md:col-span-2 group">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" class="w-full mt-1 px-3 py-1.5 text-[12px] bg-transparent border border-gray-200 dark:border-gray-600 rounded focus:border-primary focus:ring-0 dark:text-white transition-all">{{ old('alamat', $setting->alamat) }}</textarea>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Telepon 1</label>
                                <input type="text" name="telepon1" value="{{ old('telepon1', $setting->telepon1) }}" class="w-full mt-1 px-3 py-1.5 text-[12px] bg-transparent border border-gray-200 dark:border-gray-600 rounded focus:border-primary focus:ring-0 dark:text-white">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Telepon 2 (Opsional)</label>
                                <input type="text" name="telepon2" value="{{ old('telepon2', $setting->telepon2) }}" class="w-full mt-1 px-3 py-1.5 text-[12px] bg-transparent border border-gray-200 dark:border-gray-600 rounded focus:border-primary focus:ring-0 dark:text-white">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Email Instansi</label>
                                <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="w-full mt-1 px-3 py-1.5 text-[12px] bg-transparent border border-gray-200 dark:border-gray-600 rounded focus:border-primary focus:ring-0 dark:text-white">
                            </div>
                        </div>

                        <div class="md:col-span-2 mt-2">
                            <h3 class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase border-b border-blue-100 dark:border-blue-900/50 pb-1 tracking-tight">Pejabat Berwenang (Kasubbag)</h3>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Nama Lengkap Kasubbag</label>
                            <input type="text" name="nama_kasubbag" value="{{ old('nama_kasubbag', $setting->nama_kasubbag) }}" class="w-full mt-1 px-3 py-1.5 text-[12px] bg-transparent border border-gray-200 dark:border-gray-600 rounded">
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">NIP Kasubbag</label>
                            <input type="text" name="nip_kasubbag" value="{{ old('nip_kasubbag', $setting->nip_kasubbag) }}" class="w-full mt-1 px-3 py-1.5 text-[12px] bg-transparent border border-gray-200 dark:border-gray-600 rounded">
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Jabatan</label>
                            <input type="text" name="jabatan_kasubbag" value="{{ old('jabatan_kasubbag', $setting->jabatan_kasubbag) }}" class="w-full mt-1 px-3 py-1.5 text-[12px] bg-transparent border border-gray-200 dark:border-gray-600 rounded">
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 py-3 bg-gray-50 dark:bg-darkCard/50 border-t dark:border-gray-700 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-[11px] font-bold uppercase tracking-tight shadow-sm transition-all active:scale-95">
                    Simpan Informasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
            width: 'auto', 
            padding: '0.5rem 1rem',
            customClass: {
                popup: 'rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm',
                title: 'text-[9px] font-bold tracking-tight' 
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        @if($errors->any())
            Toast.fire({ icon: 'error', title: "Gagal menyimpan, periksa kembali data." });
        @endif
    });
</script>
@endpush