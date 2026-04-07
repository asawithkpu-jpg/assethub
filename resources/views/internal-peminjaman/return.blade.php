@extends('layouts.app')

@section('content')
<div class="w-full">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-[10px] font-medium uppercase tracking-wider">
                    <li class="inline-flex items-center text-gray-500 font-bold uppercase">Inventaris & Layanan</li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                            <span class="font-bold uppercase text-gray-500">peminjaman internal</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                            <span class="text-primary font-bold uppercase">proses pengembalian</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight uppercase">{{ $peminjaman->kode_peminjaman }}</h2>
        </div>
    </div>

    <div class="bg-white dark:bg-darkCard rounded-lg shadow-sm border dark:border-gray-700 p-6">
        {{-- Header Form --}}
        <div class="flex justify-between items-center border-b dark:border-gray-800 pb-4 mb-6">
            <h3 class="text-[11px] uppercase font-bold text-gray-400 tracking-widest">Informasi Peminjaman</h3>
            <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-wider bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                {{ str_replace('_', ' ', $peminjaman->status) }}
            </span>
        </div>

        {{-- Detail Peminjam --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-[11px] mb-8">
            <div class="space-y-3">
                <div>
                    <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Peminjam</span>
                    <span class="text-gray-800 dark:text-gray-200 font-bold">{{ $peminjaman->user->name }} ({{ $peminjaman->user->nip_nik }})</span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Jabatan - Subbagian</span>
                    <span class="text-gray-800 dark:text-gray-200 font-bold">{{ $peminjaman->user->jabatan }} - {{ $peminjaman->user->subbagian }}</span>
                </div>
            </div>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Kegiatan</span>
                    <span class="text-gray-800 dark:text-gray-200 font-bold">{{ $peminjaman->nama_kegiatan }}</span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Masa Pinjam</span>
                    <span class="text-gray-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d M Y') }}</span>
                </div>
            </div>
            
            {{-- Card Tanggal Kembali Riil (Komponen Tambahan) --}}
            <div class="bg-blue-50/50 dark:bg-blue-900/10 p-4 rounded-lg border border-blue-100 dark:border-blue-800/30">
                <label class="text-primary uppercase font-bold text-[9px] block mb-2 tracking-wider">Tanggal Kembali Riil</label>
                <input type="date" form="formKembali" name="tgl_kembali_real" 
                       value="{{ date('Y-m-d') }}"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded text-[11px] font-bold text-gray-700 dark:text-white focus:ring-primary focus:border-primary shadow-sm" required>
                <p class="text-[8px] text-gray-400 mt-2 italic">* Masukkan tanggal saat barang benar-benar diterima.</p>
            </div>
        </div>

        {{-- Tabel Asset --}}
        <div class="overflow-x-auto">
            <table class="w-full text-[11px] mb-8 border dark:border-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50 uppercase font-bold text-gray-500 text-[9px] tracking-widest">
                    <tr>
                        <th class="px-4 py-2.5 text-left border-b dark:border-gray-700">nama asset</th>
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700">pinjam</th>
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700 w-20">baik</th>
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700 w-20">r. ringan</th>
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700 w-20">r. berat</th>
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700 w-20">hilang</th>
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700">foto keluar</th>
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700">foto kembali</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                    @foreach($peminjaman->details as $detail)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3 font-bold">{{ $detail->asset->nama_asset }}</td>
                        <td class="px-4 py-3 text-center font-bold text-blue-600 dark:text-blue-400 bg-blue-50/30">{{ $detail->qty }}</td>
                        
                        <td class="px-2 py-3">
                            <input type="number" form="formKembali" name="items[{{ $detail->id }}][baik]" value="{{ $detail->qty }}" min="0" max="{{ $detail->qty }}" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-1 py-1 text-center text-[10px] focus:ring-1 focus:ring-primary" required>
                        </td>
                        <td class="px-2 py-3">
                            <input type="number" form="formKembali" name="items[{{ $detail->id }}][rusak_ringan]" value="0" min="0" max="{{ $detail->qty }}" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-1 py-1 text-center text-[10px] focus:ring-1 focus:ring-orange-400" required>
                        </td>
                        <td class="px-2 py-3">
                            <input type="number" form="formKembali" name="items[{{ $detail->id }}][rusak_berat]" value="0" min="0" max="{{ $detail->qty }}" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-1 py-1 text-center text-[10px] focus:ring-1 focus:ring-red-400" required>
                        </td>
                        <td class="px-2 py-3">
                            <input type="number" form="formKembali" name="items[{{ $detail->id }}][hilang]" value="0" min="0" max="{{ $detail->qty }}" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded px-1 py-1 text-center text-[10px] focus:ring-1 focus:ring-gray-400" required>
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if($detail->foto_keluar)
                                <a href="{{ asset('storage/internal/' . $detail->foto_keluar) }}" target="_blank" class="text-primary hover:underline font-bold text-[9px] uppercase tracking-tighter">Lihat Foto</a>
                            @else
                                <span class="text-gray-400 italic">tidak ada</span>
                            @endif
                        </td>
                        
                        <td class="px-4 py-3 text-center">
                            @if($detail->foto_kembali)
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/internal/' . $detail->foto_kembali) }}" target="_blank" class="text-primary hover:underline font-bold text-[9px] uppercase tracking-tighter">Lihat Foto</a>
                                    <button type="button" onclick="confirmDeleteFotoKembali('{{ $detail->id }}')" class="text-red-500 hover:text-red-700 transition" title="Hapus Foto">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            @else
                                <form action="{{ route('peminjaman-internal.upload-foto-kembali', $detail->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="cursor-pointer bg-primary hover:bg-primary/90 text-white text-[9px] font-bold px-2 py-1 rounded transition uppercase shadow-sm">
                                        Unggah
                                        <input type="file" name="foto_kembali" class="hidden" accept="image/*" capture="camera" onchange="submitDirectUpload(this)">
                                    </label>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Form Utama --}}
        <form action="{{ route('peminjaman-internal.process-return', $peminjaman->id) }}" method="POST" id="formKembali">
            @csrf
            <div class="flex flex-wrap justify-end gap-2 pt-6 border-t dark:border-gray-800">
                <a href="{{ route('peminjaman-internal.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition">Batal</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition shadow-md">Simpan Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitDirectUpload(input) {
        if (input.files && input.files) {
            Swal.fire({
                title: 'MENGUNGGAH...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                showConfirmButton: false,
                width: '320px',
                customClass: {
                    popup: 'rounded-lg border dark:border-gray-700 shadow-2xl bg-white dark:bg-gray-800',
                    title: 'text-sm font-bold pt-4 text-primary',
                    htmlContainer: 'text-[11px] dark:text-gray-300 pb-4',
                },
                didOpen: () => { Swal.showLoading(); }
            });
            input.closest('form').submit();
        }
    }

    function confirmDeleteFotoKembali(detailId) {
        Swal.fire({
            title: 'HAPUS FOTO?',
            text: "Foto akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            width: '320px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl bg-white dark:bg-gray-800',
                title: 'text-sm font-bold pt-4',
                htmlContainer: 'text-[11px] dark:text-gray-300',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/peminjaman-internal/delete-foto-kembali/${detailId}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    document.getElementById('formKembali').onsubmit = function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'SIMPAN DATA?',
            text: "Pastikan semua kondisi asset dan tanggal kembali sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            confirmButtonText: 'YA, SIMPAN',
            cancelButtonText: 'CEK LAGI',
            width: '320px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl bg-white dark:bg-gray-800',
                title: 'text-sm font-bold pt-4',
                htmlContainer: 'text-[11px] dark:text-gray-300',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) { this.submit(); }
        });
    };

    @if(session('success'))
        Swal.fire({
            title: 'BERHASIL',
            text: "{{ session('success') }}",
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            width: '320px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-800',
                title: 'text-sm font-bold pt-4 text-green-600',
                htmlContainer: 'text-[11px]'
            }
        });
    @endif
</script>
@endsection