@extends('layouts.app')

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
                            <span class="font-bold">Peminjaman Internal</span>
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
            <a href="{{ route('peminjaman-internal.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                kembali
            </a>
            <a href="{{ route('peminjaman-internal.pdf', $peminjaman->id) }}" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest">
                pdf
            </a>
            <a href="{{ route('peminjaman-internal.word', $peminjaman->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest">
                word
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-darkCard rounded-lg shadow-sm border dark:border-gray-700 p-6">
        <div class="flex justify-between items-center border-b dark:border-gray-800 pb-4 mb-6">
            <h3 class="text-[11px] uppercase font-bold text-gray-400 tracking-widest">Informasi Peminjaman</h3>
            <span class="px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-wider 
                {{ $peminjaman->status == 'Menunggu acc Kasubbag' ? 'bg-orange-100 text-orange-600' : ($peminjaman->status == 'Ditolak' ? 'bg-red-100 text-red-600' : ($peminjaman->status == 'Batal' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600')) }}">
                {{ str_replace('_', ' ', $peminjaman->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-[11px] mb-8">
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
                    <span class="text-gray-400 uppercase font-bold text-[9px] block mb-1">Durasi Pinjam</span>
                    <span class="text-gray-800 dark:text-gray-200 font-bold">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[11px] mb-8 border dark:border-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50 uppercase font-bold text-gray-500 text-[9px] tracking-widest">
                    <tr>
                        <th class="px-4 py-2.5 text-left border-b dark:border-gray-700">nama asset</th>
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700">qty</th>
                        <th class="px-4 py-2.5 text-left border-b dark:border-gray-700">satuan</th>
                        @can('proses-peminjaman')
                        <th class="px-4 py-2.5 text-center border-b dark:border-gray-700 w-48">foto keluar</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-800">
                    @foreach($peminjaman->details as $detail)
                    <tr class="dark:text-gray-300 hover:bg-gray-50/50 transition">
                        <td class="px-4 py-2.5 font-bold">{{ $detail->asset->nama_asset }}</td>
                        <td class="px-4 py-2.5 text-center font-bold">{{ $detail->qty }}</td>
                        <td class="px-4 py-2.5 italic text-gray-400 lowercase">{{ $detail->satuan }}</td>
                        @can('proses-peminjaman')
                        <td class="px-4 py-2.5 text-center">
                            @if($detail->foto_keluar)
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/internal/' . $detail->foto_keluar) }}" target="_blank" class="text-primary hover:underline font-bold text-[9px] uppercase">
                                        Lihat Foto
                                    </a>
                                    <button type="button" onclick="confirmDeleteFoto('{{ $detail->id }}')" class="text-red-500 hover:text-red-700 transition" title="Hapus Foto">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            @else
                                <form action="{{ route('peminjaman-internal.upload-foto', $detail->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="foto_keluar" accept="image/*" capture="camera" 
                                        class="text-[9px] w-full block w-full text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[9px] file:font-bold file:bg-primary file:text-white hover:file:bg-primary/80" 
                                        required onchange="this.form.submit()">
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
            @if($peminjaman->status == 'Menunggu acc Kasubbag')
                @can('persetujuan-kasubbag')
                    <button onclick="handleReject()" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition">Tolak</button>
                    <form action="{{ route('peminjaman-internal.approve', $peminjaman->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition">Setujui</button>
                    </form>
                @endcan
            @endif

            @can('proses-peminjaman')
                @if($peminjaman->status == 'Disetujui Kasubbag')
                    <form action="{{ route('peminjaman-internal.update-status', [$peminjaman->id, 'Siap Diambil']) }}" method="POST">
                        @csrf
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition">Siap Diambil</button>
                    </form>
                @elseif($peminjaman->status == 'Siap Diambil')
                    <form action="{{ route('peminjaman-internal.update-status', [$peminjaman->id, 'Dipinjam']) }}" method="POST">
                        @csrf
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition">Dipinjam</button>
                    </form>
                @elseif($peminjaman->status == 'Dipinjam')
                    <a href="{{ route('peminjaman-internal.return-page', $peminjaman->id) }}" 
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition">
                    Proses Pengembalian
                    </a>
                @endif
            @endcan
        </div>
        
        @if($peminjaman->status == 'Ditolak')
            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20 rounded text-red-600 text-[10px]">
                <strong class="uppercase tracking-tighter mr-2">alasan penolakan:</strong> <span class="italic font-medium lowercase">{{ $peminjaman->catatan_penolakan ?? '-' }}</span>
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDeleteFoto(detailId) {
        Swal.fire({
            title: 'HAPUS FOTO?',
            text: "Foto akan dihapus dari server.",
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
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/peminjaman-internal/delete-foto/${detailId}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function handleReject() {
        Swal.fire({
            title: 'TOLAK PEMINJAMAN?',
            text: "Berikan alasan penolakan singkat:",
            input: 'textarea',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'YA, TOLAK',
            cancelButtonText: 'BATAL',
            width: '320px',
            customClass: {
                htmlContainer: 'text-[15px] mt-1',
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl',
                title: 'text-sm font-bold pt-4',
                input: 'text-xs',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('peminjaman-internal.reject', $peminjaman->id) }}";
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="catatan" value="${result.value}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: "{{ session('success') }}",
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
    @endif
</script>
@endsection