@extends('layouts.app')

@section('content')
<nav class="flex mb-3" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-[10px] font-medium uppercase tracking-wider">
        <li class="inline-flex items-center">
            <span class="text-gray-600 font-bold uppercase">inventaris & layanan</span>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                <span class="text-primary font-bold uppercase">peminjaman eksternal</span>
            </div>
        </li>
    </ol>
</nav>

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">Peminjaman Eksternal</h2>
        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest leading-tight">Daftar Peminjaman Pihak Luar</p>
    </div>
    <a href="{{ route('peminjaman-eksternal.create') }}" class="bg-primary hover:bg-blue-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest shrink-0">
        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        tambah peminjaman
    </a>
</div>

<div class="bg-white dark:bg-darkCard rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
    <div class="p-4 overflow-x-auto">
        <table id="tableEksternal" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 text-[10px] uppercase tracking-wider text-gray-600 dark:text-gray-400 border-b dark:border-gray-700">
                    <th class="px-3 py-3 font-bold">Peminjam & Kegiatan</th>
                    <th class="px-3 py-3 font-bold">No. Surat</th>
                    <th class="px-3 py-3 font-bold text-center">Jadwal Pinjam</th>
                    <th class="px-3 py-3 font-bold">Asset</th>
                    <th class="px-3 py-3 font-bold text-center">Status</th>
                    <th class="px-3 py-3 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[11px]">
                @foreach($peminjamans as $item)
                <tr class="border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                    <td class="px-3 py-3">
                        <span class="font-bold text-primary block mb-0.5">{{ $item->kode_peminjaman }}</span>
                        <div class="font-bold text-gray-800 dark:text-gray-200 uppercase leading-tight">{{ $item->user->name }}</div>
                        <div class="text-[10px] text-gray-500">Kegiatan : {{ $item->nama_kegiatan }}</div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="font-medium text-gray-700 dark:text-gray-300">{{ $item->nomor_surat }}</div>
                        <div class="text-[9px] text-gray-400">{{ \Carbon\Carbon::parse($item->tgl_surat)->format('d M Y') }}</div>
                    </td>
                    <td class="px-3 py-3 text-center">
                        <div class="font-bold text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M y') }}</div>
                        <div class="text-[9px] text-gray-400 uppercase">s/d</div>
                        <div class="font-bold text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M y') }}</div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach($item->details as $detail)
                                <span class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-1.5 py-0.5 rounded text-[9px] font-bold border border-blue-100 dark:border-blue-800/50">
                                    {{ $detail->asset->nama_asset }} ({{ $detail->qty }})
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-3 py-3 text-center">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold tracking-tighter uppercase
                            {{ $item->status == 'Menunggu acc Pimpinan' || $item->status == 'Menunggu acc Kasubbag' ? 'bg-orange-100 text-orange-600' : 
                               ($item->status == 'Ditolak' ? 'bg-red-100 text-red-600' : 
                               ($item->status == 'Batal' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600')) }}">
                            {{ str_replace('_', ' ', $item->status) }}
                        </span>
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('peminjaman-eksternal.show', $item->id) }}" class="text-gray-400 hover:text-primary transition" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>

                            @if(in_array($item->status, ['Menunggu acc Pimpinan', 'Menunggu acc Kasubbag', 'Disetujui Kasubbag', 'Siap Diambil']))
                            <button onclick="confirmCancel('{{ $item->id }}')" class="text-gray-400 hover:text-orange-500 transition" title="Batalkan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            </button>
                            @endif

                            @can('delete-data')
                                @if($item->status == 'Batal')
                                <button onclick="confirmDelete('{{ $item->id }}')" class="text-gray-400 hover:text-red-500 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tableEksternal').DataTable({
            responsive: true,
            language: { 
                search: "", 
                searchPlaceholder: "Cari data peminjaman...",
                lengthMenu: "_MENU_",
            },
            pageLength: 10
        });
    });

    function confirmCancel(id) {
        Swal.fire({
            title: 'BATALKAN PENGAJUAN?',
            text: "Data yang dibatalkan tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            confirmButtonText: 'YA, BATALKAN',
            cancelButtonText: 'TIDAK',
            width: '320px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl bg-white dark:bg-gray-800',
                title: 'text-sm font-bold pt-4 text-orange-600',
                htmlContainer: 'text-[11px] mt-1',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/peminjaman-eksternal/${id}/cancel`;
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'HAPUS DATA?',
            text: "Hapus data permanen dari sistem.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            width: '320px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl bg-white dark:bg-gray-800',
                title: 'text-sm font-bold pt-4 text-red-600',
                htmlContainer: 'text-[11px] mt-1',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/peminjaman-eksternal/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        width: 'auto',
        padding: '0.4rem 0.8rem',
        customClass: {
            popup: 'flex items-center rounded-md border dark:border-gray-700 shadow-md bg-white dark:bg-gray-800 my-2 mx-4',
            icon: 'm-0 mr-2 scale-75',
            title: 'text-[10px] font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap p-0 m-0'
        }
    });

    @if(session('success'))
        Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
    @endif
    @if(session('error'))
        Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
    @endif
</script>
@endpush