@extends('layouts.app')

@php
    $getRomawi = function($month) {
        $map = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        return $map[(int)$month] ?? 'I';
    };
@endphp

@section('content')
<div class="w-full">
    {{-- Breadcrumb & Header Action --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-[10px] font-medium uppercase tracking-wider">
                    <li class="inline-flex items-center text-gray-600 font-bold">Inventaris & Layanan</li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                            <span class="font-bold text-gray-600">Peminjaman Eksternal</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                            <span class="text-primary font-bold">Detail Peminjaman</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight uppercase">{{ $peminjaman->kode_peminjaman }}</h2>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('peminjaman-eksternal.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                kembali
            </a>
            <a href="{{ route('eksternal-peminjaman.export-pdf', $peminjaman->id) }}" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest">
                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                pdf
            </a>
            <a href="{{ route('eksternal-peminjaman.export-word', $peminjaman->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest">
                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                word
            </a>
        </div>
    </div>

    @can('proses-peminjaman')
        @if($peminjaman->status != 'Selesai' && $peminjaman->status != 'Batal')
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 rounded-lg mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="px-3 py-2 bg-blue-600 rounded-lg text-white font-bold text-xs uppercase tracking-tighter">#</div>
                <div>
                    <h4 class="text-[11px] font-bold text-blue-900 dark:text-blue-300 uppercase leading-none">Penyesuaian No Peminjaman</h4>
                    <p class="text-[9px] text-blue-700 dark:text-blue-400 mt-1 uppercase">Ubah nomor urut depan jika diperlukan</p>
                </div>
            </div>
            <form action="{{ route('peminjaman-eksternal.update-kode', $peminjaman->id) }}" method="POST" class="flex items-center gap-2" id="formUpdateKode">
                @csrf
                <input type="text" name="prefix_kode" value="{{ explode('/', $peminjaman->kode_peminjaman)[0] }}" class="w-12 bg-white dark:bg-gray-800 border dark:border-blue-800 rounded px-2 py-1 text-[11px] font-bold text-center">
                <span class="text-[10px] font-bold text-gray-400">/ B.INV / KPU.KAB.PAS / {{ $getRomawi(date('m', strtotime($peminjaman->tgl_pinjam))) }} / {{ date('Y', strtotime($peminjaman->tgl_pinjam)) }}</span>
                <button type="button" onclick="submitUpdateKode()" class="bg-blue-600 text-white px-3 py-1 rounded text-[10px] font-bold uppercase tracking-tighter shadow-sm hover:bg-blue-700 transition-colors tracking-widest">Update</button>
            </form>
        </div>
        @endif
    @endcan

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white dark:bg-darkCard rounded-lg shadow-sm border dark:border-gray-700 p-6">
                <div class="flex justify-between items-center border-b dark:border-gray-800 pb-4 mb-6">
                    <h3 class="text-[11px] uppercase font-bold text-gray-400 tracking-widest">Informasi Peminjaman</h3>
                    <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-wider 
                        {{ in_array($peminjaman->status, ['Menunggu acc Pimpinan', 'Menunggu acc Kasubbag']) ? 'bg-orange-100 text-orange-600' : ($peminjaman->status == 'Ditolak' ? 'bg-red-100 text-red-600' : ($peminjaman->status == 'Batal' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600')) }}">
                        {{ str_replace('_', ' ', $peminjaman->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-[11px] mb-8">
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Peminjam</span>
                            <span class="text-gray-800 dark:text-gray-200 font-bold">{{ $peminjaman->user->name ?? '-' }} ({{ $peminjaman->user->nip_nik ?? '-' }})</span>
                        </div>
                        <div>
                            <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Jabatan - Instansi</span>
                            <span class="text-gray-800 dark:text-gray-200 font-bold">{{ $peminjaman->user->jabatan ?? '-' }} - {{ $peminjaman->user->instansi ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Kegiatan</span>
                            <span class="text-gray-800 dark:text-gray-200 font-bold">{{ $peminjaman->nama_kegiatan }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Durasi Pinjam</span>
                            <span class="text-gray-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-[11px] mb-8 border dark:border-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 uppercase font-bold text-gray-500 text-[9px] tracking-widest">
                            <tr>
                                <th class="px-4 py-2.5 text-left border-b dark:border-gray-700 uppercase tracking-widest">nama asset</th>
                                <th class="px-4 py-2.5 text-center border-b dark:border-gray-700 uppercase tracking-widest">jumlah</th>
                                <th class="px-4 py-2.5 text-left border-b dark:border-gray-700 uppercase tracking-widest">satuan</th>
                                @can('proses-peminjaman')
                                <th class="px-4 py-2.5 text-center border-b dark:border-gray-700 w-48 uppercase tracking-widest">foto keluar</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-800">
                            @foreach($peminjaman->details as $detail)
                            <tr class="dark:text-gray-300 hover:bg-gray-50/50 transition">
                                <td class="px-4 py-2.5 font-bold">{{ $detail->asset->nama_asset }}</td>
                                <td class="px-4 py-2.5 text-center font-bold text-primary">{{ $detail->qty }}</td>
                                <td class="px-4 py-2.5 italic text-gray-400 uppercase text-[9px] tracking-widest">{{ $detail->satuan }}</td>
                                @can('proses-peminjaman')
                                <td class="px-4 py-2.5 text-center">
                                    @if($detail->foto_keluar)
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ asset('storage/eksternal/foto/' . $detail->foto_keluar) }}" target="_blank" class="text-primary hover:underline font-bold text-[9px] uppercase tracking-tighter">Lihat Foto</a>
                                            <button type="button" onclick="confirmDeleteFoto('{{ $detail->id }}')" class="text-red-500 hover:text-red-700 transition" title="Hapus Foto">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    @else
                                        <form action="{{ route('peminjaman-eksternal.upload-foto', $detail->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center justify-center form-upload">
                                            @csrf
                                            <label class="cursor-pointer bg-primary/10 hover:bg-primary/20 text-primary px-2 py-0.5 rounded border border-primary/30 flex items-center gap-1.5 transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span class="text-[9px] font-bold uppercase tracking-widest">Unggah</span>
                                                <input type="file" name="foto_keluar" accept="image/*" capture="camera" class="hidden" onchange="handleUpload(this)">
                                            </label>
                                        </form>
                                    @endif
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap justify-end gap-2 pt-6 border-t dark:border-gray-800">
                    @can('persetujuan-pimpinan')
                        @if($peminjaman->status == 'Menunggu acc Pimpinan')
                            <button onclick="rejectAction('pimpinan')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition shadow-sm">Tolak</button>
                            <form action="{{ route('peminjaman-eksternal.approve-pimpinan', $peminjaman->id) }}" method="POST" class="form-action">
                                @csrf
                                <button type="button" onclick="submitWithLoading(this)" class="bg-primary hover:bg-blue-700 text-white px-4 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition shadow-sm uppercase tracking-widest">Setujui</button>
                            </form>
                        @endif
                    @endcan

                    @can('persetujuan-kasubbag')
                        @if($peminjaman->status == 'Menunggu acc Kasubbag')
                            <button onclick="rejectAction('kasubbag')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition shadow-sm">Tolak</button>
                            <form action="{{ route('peminjaman-eksternal.approve-kasubbag', $peminjaman->id) }}" method="POST" class="form-action">
                                @csrf
                                <button type="button" onclick="submitWithLoading(this)" class="bg-primary hover:bg-blue-700 text-white px-4 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition shadow-sm uppercase tracking-widest">Setujui</button>
                            </form>
                        @endif
                    @endcan

                    @can('proses-peminjaman')
                        @if($peminjaman->status == 'Disetujui Kasubbag')
                             <form action="{{ route('peminjaman-eksternal.update-status', [$peminjaman->id, 'Siap Diambil']) }}" method="POST" class="form-action">
                                @csrf
                                <button type="button" onclick="submitWithLoading(this)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition shadow-sm uppercase tracking-widest">Siap Diambil</button>
                            </form>
                        @elseif($peminjaman->status == 'Siap Diambil')
                            <form action="{{ route('peminjaman-eksternal.update-status', [$peminjaman->id, 'Dipinjam']) }}" method="POST">
                                @csrf
                                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition">Dipinjam</button>
                            </form>
                        @elseif($peminjaman->status == 'Dipinjam')
                            <a href="{{ route('peminjaman-eksternal.return', $peminjaman->id) }}" 
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition">
                            Proses Pengembalian
                            </a>
                        @endif
                    @endcan
                </div>
                
                @if($peminjaman->status == 'Ditolak')
                    <div class="p-3 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20 rounded text-red-600 text-[10px]">
                        <strong class="uppercase tracking-tighter mr-1">alasan penolakan:</strong> <span class="italic font-medium lowercase">{{ $peminjaman->catatan_penolakan ?? '-' }}</span>
                    </div>
                @endif

                <div class="mt-4 mb-8 border-t border-dashed dark:border-gray-800 pt-6">
                    <h3 class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-3">Dokumen Scan</h3>
                    
                    @if($peminjaman->file_form_pinjam)
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                onclick="openPreviewModal('{{ asset('storage/' . ($peminjaman->tipe_peminjaman == 'internal' ? 'internal/dokumen/' : 'eksternal/dokumen/') . $peminjaman->file_form_pinjam) }}')" 
                                class="flex items-center gap-1.5 bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 px-2.5 py-1.5 rounded-md border border-blue-100 dark:border-blue-800 hover:bg-blue-100 transition shadow-sm text-[9px] font-bold uppercase tracking-tight">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Preview
                            </button>
    
                            <form action="{{ route($peminjaman->tipe_peminjaman == 'internal' ? 'peminjaman-internal.upload-form-pinjam' : 'peminjaman-eksternal.upload-form-pinjam', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" class="relative">
                                @csrf
                                <input type="file" name="file_form_pinjam" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="this.form.submit()">
                                <button type="button" class="bg-gray-50 dark:bg-gray-800 text-gray-500 border dark:border-gray-700 px-2.5 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-tight hover:bg-gray-100 transition">
                                    Ganti
                                </button>
                            </form>
    
                            <button type="button" onclick="confirmDeleteDoc()" 
                                class="text-gray-400 hover:text-red-500 p-1.5 transition-colors" title="Hapus Dokumen">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @else
                        <form action="{{ route($peminjaman->tipe_peminjaman == 'internal' ? 'peminjaman-internal.upload-form-pinjam' : 'peminjaman-eksternal.upload-form-pinjam', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" class="max-w-xs">
                            @csrf
                            <div class="flex items-center gap-2">
                                <input type="file" name="file_form_pinjam" accept="application/pdf" required
                                    class="block w-full text-[9px] text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-[9px] file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition cursor-pointer">
                                <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-tighter shadow-sm hover:bg-indigo-700">Unggah</button>
                            </div>
                            <p class="text-[8px] text-gray-400 mt-1.5 font-medium italic">* PDF, Maks 2MB</p>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="bg-white dark:bg-darkCard rounded-lg border dark:border-gray-700 shadow-sm overflow-hidden sticky top-6">
                <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <div>
                        <span class="text-[10px] font-bold text-primary uppercase tracking-widest block leading-none tracking-widest">Dokumen Pendukung</span>
                        <span class="text-[10px] font-bold mt-1.5 block tracking-widest">No Surat: {{ $peminjaman->nomor_surat ?? '-' }}</span>
                    </div>
                    <a href="{{ asset('storage/eksternal/surat/'.$peminjaman->file_surat) }}" download class="bg-white dark:bg-gray-800 text-primary hover:text-blue-600 p-2 rounded-lg border dark:border-gray-700 shadow-sm transition" title="Download PDF">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                </div>
                <div class="p-2 bg-gray-200 dark:bg-gray-900 aspect-[3/4] w-full relative">
                    <embed src="{{ asset('storage/eksternal/surat/'.$peminjaman->file_surat) }}#toolbar=0" type="application/pdf" width="100%" height="100%" class="rounded shadow-inner">
                </div>
            </div>
        </div>

        {{-- Modal Preview PDF --}}
        <div id="pdfModal" class="fixed inset-0 z- hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closePreviewModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-middle bg-white dark:bg-slate-900 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="flex justify-between items-center p-4 border-b dark:border-gray-800">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-tight">Preview Dokumen</h3>
                        <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="h-[70vh]">
                        <iframe id="pdfFrame" src="" class="w-full h-full" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Swal Compact Helper
    const showLoading = (msg = 'Mohon Tunggu...') => {
        Swal.fire({
            title: msg,
            allowOutsideClick: false,
            width: '280px',
            padding: '1rem',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-xl',
                title: 'text-[11px] font-bold uppercase tracking-widest'
            },
            didOpen: () => { Swal.showLoading(); }
        });
    };

    function submitUpdateKode() {
        showLoading('Updating Kode...');
        document.getElementById('formUpdateKode').submit();
    }

    function handleUpload(input) {
        if(input.files && input.files) {
            showLoading('Mengunggah Foto...');
            input.form.submit();
        }
    }

    function submitWithLoading(btn) {
        showLoading('Memproses...');
        btn.closest('form').submit();
    }

    function confirmDeleteFoto(detailId) {
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
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl mt-10',
                title: 'text-[11px] font-bold pt-4 uppercase tracking-widest',
                htmlContainer: 'text-[10px] pb-4',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Menghapus Foto...');
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/peminjaman-eksternal/delete-foto/${detailId}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function rejectAction(role) {
        let route = role === 'pimpinan' ? "{{ route('peminjaman-eksternal.reject-pimpinan', $peminjaman->id) }}" : "{{ route('peminjaman-eksternal.reject-kasubbag', $peminjaman->id) }}";
        
        Swal.fire({
            title: 'ALASAN PENOLAKAN',
            input: 'textarea',
            inputPlaceholder: 'Tulis alasan penolakan di sini...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'YA, TOLAK',
            cancelButtonText: 'BATAL',
            width: '320px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xl mt-10',
                title: 'text-[11px] font-bold pt-4 text-red-600 uppercase tracking-widest',
                input: 'text-[11px] rounded border dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if(!result.value) {
                    Swal.fire({ title: 'Gagal', text: 'Alasan harus diisi!', icon: 'error', width: '300px', position: 'top' });
                    return;
                }
                showLoading('Mengirim Penolakan...');
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = route;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="catatan" value="${result.value}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            width: '280px',
            position: 'bottom',
            padding: '1rem',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-xl mt-10',
                title: 'text-[11px] font-bold uppercase tracking-widest text-green-600'
            }
        });
    @endif

    // file form pinjam
    function openPreviewModal(url) {
        document.getElementById('pdfFrame').src = url;
        document.getElementById('pdfModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // prevent scroll
    }

    function closePreviewModal() {
        document.getElementById('pdfModal').classList.add('hidden');
        document.getElementById('pdfFrame').src = '';
        document.body.style.overflow = 'auto';
    }

    function confirmDeleteDoc() {
        Swal.fire({
            title: 'HAPUS DOKUMEN?',
            text: "File PDF akan dihapus permanen dari server.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            width: '320px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl',
                title: 'text-sm font-bold pt-4',
                htmlContainer: 'text-[15px]',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Buat form dinamis untuk mengirimkan request DELETE
                let form = document.createElement('form');
                form.method = 'POST';
                // Tentukan route berdasarkan tipe (bisa dikirim lewat parameter atau logic blade)
                form.action = "{{ route($peminjaman->tipe_peminjaman == 'internal' ? 'peminjaman-internal.delete-form-pinjam' : 'peminjaman-eksternal.delete-form-pinjam', $peminjaman->id) }}";
                
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection