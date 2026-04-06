@extends('layouts.app')

@section('content')
<div class="w-full">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-[10px] font-medium uppercase tracking-wider">
            <li class="inline-flex items-center text-gray-500 font-bold uppercase">Laporan</li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                    <span class="font-bold uppercase text-gray-500">History Peminjam</span>
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
    
    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">
            {{ $identifier }} 
            <span class=" uppercase ml-2 px-2 py-0.5 text-[10px] rounded-md {{ $type == 'internal' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                {{ $type }}
            </span>
        </h2>
        <p class="text-[10px] text-gray-500 uppercase tracking-widest">Riwayat Transaksi Peminjaman</p>
    </div>

    <div class="bg-white dark:bg-darkCard rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="overflow-x-auto">
            <table id="historyTable" class="w-full text-sm border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-[10px] uppercase tracking-widest text-gray-400">
                        <th class="px-4 py-4 font-bold text-left">Peminjaman</th>
                        <th class="px-4 py-4 font-bold text-center">Rentang Pinjam</th>
                        <th class="px-4 py-4 font-bold text-left">Barang / Asset (Qty)</th>
                        <th class="px-4 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $loan)
                    <tr class="bg-white dark:bg-darkCard hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 shadow-sm">
                        {{-- Kolom Peminjaman --}}
                        <td class="px-4 py-4 border-y first:border-l last:border-r dark:border-gray-700 first:rounded-l-lg last:rounded-r-lg">
                            <div class="text-primary font-bold text-[10px] mb-1">{{ $loan->kode_peminjaman }}</div>
                            <div class="font-bold text-gray-700 dark:text-gray-200 text-[12px] uppercase">{{ $loan->user->name }}</div>
                            @if($type == 'eksternal')
                            <div class="text-[9px] font-medium">
                                <span class="font-bold">No Surat: </span>{{ $loan->nomor_surat}} ( {{ \Carbon\Carbon::parse($loan->tgl_surat)->format('d M Y') }} )
                            </div>
                            @endif
                        </td>

                        {{-- Kolom Rentang Pinjam --}}
                        <td class="px-4 py-4 text-center border-y dark:border-gray-700">
                            <div class="text-[10px] font-bold text-gray-600 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($loan->tgl_pinjam)->format('d M Y') }} - {{ \Carbon\Carbon::parse($loan->tgl_kembali)->format('d M Y') }}
                            </div>
                            @if($loan->tgl_kembali_real)
                                <div class="text-[10px] text-green-500 font-bold mt-1">
                                    Kembali Riil: {{ \Carbon\Carbon::parse($loan->tgl_kembali_real)->format('d M Y') }}
                                </div>
                            @else
                                <div class="text-[10px] text-red-500 font-bold mt-1 tracking-tighter italic">Belum Kembali</div>
                            @endif
                        </td>

                        {{-- Kolom Barang / Asset (Ringkasan) --}}
                        <td class="px-4 py-4 border-y dark:border-gray-700">
                            <div class="space-y-2">
                                @foreach($loan->details as $detail)
                                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 p-2 rounded-lg border border-gray-100 dark:border-gray-700">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-gray-400">{{ $detail->asset->kode_asset }}</span>
                                        <span class="text-[10px] font-bold text-gray-700 dark:text-gray-200">{{ $detail->asset->nama_asset }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3 ml-4">
                                        <div class="flex flex-col items-center">
                                            <span class="text-[8px] text-gray-400">Dipinjam</span>
                                            <span class="text-[10px] font-bold text-blue-600">{{ $detail->qty }}</span>
                                        </div>
                                        <div class="flex flex-col items-center border-l dark:border-gray-700 pl-3">
                                            <span class="text-[8px] text-gray-400">Kondisi Kembali</span>
                                            <div class="flex space-x-2">
                                                <span class="text-[10px] font-bold text-green-600" title="Baik">{{ $detail->kembali_baik ?? 0 }}</span>
                                                <span class="text-[10px] font-bold text-orange-500" title="Rusak Ringan">{{ $detail->kembali_rusak_ringan ?? 0 }}</span>
                                                <span class="text-[10px] font-bold text-red-500" title="Rusak Berat">{{ $detail->kembali_rusak_berat ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="px-4 py-4 text-center border-y first:border-l last:border-r dark:border-gray-700 first:rounded-l-lg last:rounded-r-lg">
                            @php
                                $showRoute = ($type == 'internal') 
                                    ? route('peminjaman-internal.show', $loan->id) 
                                    : route('peminjaman-eksternal.show', $loan->id);
                            @endphp
                            <a href="{{ $showRoute }}" class="p-2 bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white rounded-lg transition-all border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800 inline-block shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
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
            "pageLength": 10,
            "order": [[1, "desc"]],
            "language": {
                "search": "",
                "searchPlaceholder": "Cari data peminjam...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "dom": '<"flex flex-col md:flex-row justify-between items-center gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
            "drawCallback": function() {
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
    #historyTable { border-collapse: separate !important; border-spacing: 0 10px !important; }
    #historyTable thead th { border: none !important; }
    .dataTables_wrapper .dataTables_info { font-[10px] font-bold text-gray-400 uppercase tracking-widest; }
    table.dataTable.no-footer { border-bottom: none !important; }
</style>
@endsection