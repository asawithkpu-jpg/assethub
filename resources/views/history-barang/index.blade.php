@extends('layouts.app')

@section('content')
<div class="w-full">
    {{-- Breadcrumb --}}
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-[10px] font-medium uppercase tracking-wider">
            <li class="text-gray-500 font-bold">Laporan</li>
            <li class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                <span class="text-primary font-bold tracking-widest">History Barang</span>
            </li>
        </ol>
    </nav>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">History Barang</h2>
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest leading-tight">Riwayat Peminjaman per Barang</p>
        </div>
        {{-- Live Search ala Datatable --}}
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Cari nama atau kode barang..." 
                class="pl-10 pr-4 py-2 border dark:border-gray-700 dark:bg-darkCard rounded-lg text-[11px] focus:ring-primary w-64 transition-all">
            <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5" id="assetGrid">
        @foreach($assets as $asset)
        <div class="asset-card bg-white dark:bg-darkCard rounded-xl border dark:border-gray-700 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <div class="p-4">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-[9px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded uppercase tracking-widest">{{ $asset->kode_asset }}</span>
                    <a href="{{ route('history-barang.show', $asset->id) }}" 
                       class="p-1.5 bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors shadow-sm border dark:border-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                </div>
                <h3 class="asset-name text-xs font-bold text-gray-800 dark:text-white leading-tight mb-1">{{ $asset->nama_asset }}</h3>
                <p class="text-[10px] text-gray-500 italic">{{ $asset->kategori?? 'Tanpa Kategori' }}</p>
            </div>
            <div class="px-4 py-2 bg-gray-50/50 dark:bg-gray-800/50 border-t dark:border-gray-700 text-right">
                <span class="text-[9px] font-medium text-gray-400 uppercase tracking-tighter">Klik icon mata untuk lihat history</span>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    // Script Live Search ala Datatable
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('.asset-card');
        
        cards.forEach(card => {
            let name = card.querySelector('.asset-name').textContent.toLowerCase();
            let code = card.querySelector('.tracking-widest').textContent.toLowerCase();
            if (name.includes(filter) || code.includes(filter)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
</script>
@endpush
@endsection