@extends('layouts.app')

@section('content')
<div class="w-full">
    {{-- Breadcrumb --}}
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-[10px] font-medium uppercase tracking-wider">
            <li class="inline-flex items-center text-gray-500 font-bold uppercase">Laporan</li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                    <span class="font-bold uppercase text-gray-500">History Barang</span>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                    <span class="text-primary font-bold uppercase decoration-2 underline-offset-4 tracking-widest">Detail Log</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">
            {{ $asset->nama_asset }} <span class="text-gray-400 font-medium text-sm">({{ $asset->kode_asset }})</span>
        </h2>
        {{-- Penambahan Info Scope Data --}}
        <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">
            @if(auth()->user()->hasRole(['administrator', 'pimpinan', 'kasubbag', 'operator']))
                Riwayat Semua Peminjaman
            @elseif(auth()->user()->hasRole('peminjam-internal'))
                Riwayat Peminjaman Subbagian: {{ auth()->user()->subbagian }}
            @elseif(auth()->user()->hasRole('peminjam-eksternal'))
                Riwayat Peminjaman Instansi: {{ auth()->user()->instansi }}
            @endif
        </p>
    </div>

    <div class="bg-white dark:bg-darkCard rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="overflow-x-auto">
            <table id="historyTable" class="w-full text-sm border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-[10px] uppercase tracking-widest text-gray-400 border-b dark:border-gray-700">
                        <th class="px-4 py-4 font-bold text-left">Identitas Peminjam</th>
                        <th class="px-4 py-4 font-bold text-center">Tipe</th>
                        <th class="px-4 py-4 font-bold text-center">Rentang Pinjam</th>
                        <th class="px-4 py-4 font-bold text-center text-blue-600">Qty</th>
                        <th class="px-4 py-4 font-bold text-center text-green-600">Kembali Baik</th>
                        <th class="px-4 py-4 font-bold text-center text-orange-500">Kembali R. Ringan</th>
                        <th class="px-4 py-4 font-bold text-center text-red-500">Kembali R. Berat</th>
                        <th class="px-4 py-4 font-bold text-center text-gray-500">Kembali Hilang</th>
                        <th class="px-4 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $log)
                    <tr class="bg-white dark:bg-darkCard hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 shadow-sm">
                        <td class="px-4 py-4 border-y first:border-l last:border-r dark:border-gray-700 first:rounded-l-lg last:rounded-r-lg">
                            <div class="font-bold text-gray-700 dark:text-gray-200 text-[12px]">{{ $log->peminjaman->user->name }}</div>
                            <div class="text-[10px] text-gray-400 font-medium italic">
                                {{ $log->peminjaman->tipe_peminjaman == 'internal' ? $log->peminjaman->user->subbagian : $log->peminjaman->user->instansi }}
                            </div>
                            <div class="text-[9px] font-medium">
                                @if($log->peminjaman->tipe_peminjaman == 'eksternal')
                                    <span class="font-bold">No Surat: </span>{{ $log->peminjaman->nomor_surat}} ( {{ \Carbon\Carbon::parse($log->peminjaman->tgl_surat)->format('d M Y') }} )
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center border-y dark:border-gray-700">
                            @if($log->peminjaman->tipe_peminjaman == 'internal')
                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400 font-bold text-[10px] tracking-tighter border border-green-100 dark:border-green-800">Internal</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 font-bold text-[10px] tracking-tighter border border-blue-100 dark:border-blue-800">Eksternal</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center border-y dark:border-gray-700">
                            <div class="text-[10px] font-bold text-gray-600 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($log->peminjaman->tgl_pinjam)->format('d M Y') }} - {{ \Carbon\Carbon::parse($log->peminjaman->tgl_kembali)->format('d M Y') }}
                            </div>
                            @if($log->peminjaman->tgl_kembali_real)
                                <div class="text-[9px] text-green-500 font-bold mt-1">Kembali Riil: {{ \Carbon\Carbon::parse($log->peminjaman->tgl_kembali_real)->format('d M Y') }}</div>
                            @else
                                <div class="text-[9px] text-red-400 font-bold mt-1 tracking-tighter">Belum Kembali</div>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center border-y dark:border-gray-700">
                            <span class="text-[11px] text-blue-600">{{ $log->qty }}</span>
                        </td>
                        <td class="px-4 py-4 text-center border-y dark:border-gray-700">
                            <span class="text-[11px] text-green-600">{{ $log->kembali_baik ?? 0 }}</span>
                        </td>
                        <td class="px-4 py-4 text-center border-y dark:border-gray-700">
                            <span class="text-[11px] text-orange-500">{{ $log->kembali_rusak_ringan ?? 0 }}</span>
                        </td>
                        <td class="px-4 py-4 text-center border-y dark:border-gray-700">
                            <span class="text-[11px] text-red-500">{{ $log->kembali_rusak_berat ?? 0 }}</span>
                        </td>
                        <td class="px-4 py-4 text-center border-y dark:border-gray-700">
                            <span class="text-[11px] text-gray-500">{{ $log->kembali_hilang ?? 0 }}</span>
                        </td>
                        <td class="px-4 py-4 text-center border-y first:border-l last:border-r dark:border-gray-700 first:rounded-l-lg last:rounded-r-lg">
                            @php
                                $route = ($log->peminjaman->tipe_peminjaman == 'internal') 
                                    ? route('peminjaman-internal.show', $log->peminjaman_id) 
                                    : route('peminjaman-eksternal.show', $log->peminjaman_id);
                            @endphp
                            <div class="flex justify-center space-x-2">
                                <a href="{{ $route }}" class="p-2 bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white rounded-lg transition-all border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800" title="Lihat Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#historyTable').DataTable({
            "pageLength": 25,
            "order": [[2, "desc"]],
            "language": {
                "search": "",
                "searchPlaceholder": "Cari data log...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
            "drawCallback": function() {
                // Menyamakan styling pagination dengan Master Asset
                $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 mx-1 text-[11px] font-bold rounded-lg border dark:border-gray-700 transition-all');
                $('.dataTables_paginate .paginate_button.current').addClass('bg-primary text-white border-primary');
                $('.dataTables_filter input').addClass('text-xs border-gray-100 dark:border-gray-700 dark:bg-gray-800 rounded-xl w-64 focus:ring-primary focus:border-primary px-4 py-2');
                $('.dataTables_length select').addClass('text-xs border-gray-100 dark:border-gray-700 dark:bg-gray-800 rounded-xl focus:ring-primary mx-2');
            }
        });
    });
</script>
@endpush

<style>
    /* Styling agar DataTable Table-Spacing berfungsi baik */
    #historyTable {
        border-collapse: separate !important;
        border-spacing: 0 10px !important;
    }
    #historyTable thead th {
        border: none !important;
    }
    .dataTables_wrapper .dataTables_info {
        font-[10px] font-bold text-gray-400 uppercase tracking-widest;
    }
    /* Menghilangkan border default datatable */
    table.dataTable.no-footer {
        border-bottom: none !important;
    }
</style>
@endsection