@extends('layouts.app')

@section('content')
<div class="container-fluid space-y-6 pb-10">
    {{-- Header & Filter --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Dashboard Overview</h2>
            <p class="text-xs text-slate-500">Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
        </div>
        
        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-1 shadow-sm relative z-20">
            <div class="flex items-center px-1">
                <input type="text" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" readonly class="border-none focus:ring-0 text-[11px] bg-transparent w-24 text-center cursor-pointer dark:text-slate-300 p-1">
                <span class="text-gray-300 text-[10px] px-1 font-bold">s/d</span>
                <input type="text" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" readonly class="border-none focus:ring-0 text-[11px] bg-transparent w-24 text-center cursor-pointer dark:text-slate-300 p-1">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-1.5 rounded-md transition-all shadow-sm ml-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
    </div>

    {{-- TAMPILAN STAFF (Admin, Pimpinan, Kasubbag, Operator) --}}
    @if($isStaff)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Total Asset</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['total_asset'] }}</h3>
                <p class="text-[10px] text-slate-500">{{ $stats['total_unit_stok'] }} Total Unit</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
            <div class="flex-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Total Peminjaman</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['total_pinjam'] }}</h3>
                <div class="flex justify-between text-[10px] mt-1 border-t pt-1 font-medium">
                    <span class="text-blue-600">Internal: {{ $stats['unit_internal_count'] }} ({{ $stats['unit_internal'] }} Unit)</span>
                    <span class="text-orange-600">Eksternal: {{ $stats['unit_eksternal_count'] }} ({{ $stats['unit_eksternal'] }} Unit)</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Sedang Dipinjam</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['sedang_dipinjam'] }}</h3>
                <p class="text-[10px] text-slate-500">Peminjaman Aktif</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Selesai</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stats['total_selesai'] }}</h3>
                <p class="text-[10px] text-slate-500">Peminjaman Selesai</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6">Frekuensi Peminjaman</h3>
            <div id="combinedChart" class="min-h-[300px]"></div>
        </div>
        <div class="lg:col-span-4 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6">Frekuensi Peminjaman Asset</h3>
            <div id="qtyChart" class="min-h-[300px]"></div>
        </div>
    </div>

    {{-- Approval Table --}}
    @if($approvalItems->count() > 0)
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border-2 border-orange-100 dark:border-orange-900/30 overflow-hidden">
        <div class="p-4 bg-orange-50/50 dark:bg-orange-900/10 border-b dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-sm text-orange-800 dark:text-orange-400 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/></svg>
                Perlu Persetujuan Anda
            </h3>
            <span class="bg-orange-200 text-orange-800 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $approvalItems->count() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-semibold">
                    <tr>
                        <th class="p-4">Peminjam</th>
                        <th class="p-4">Kegiatan</th>
                        <th class="p-4">Status Saat Ini</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-700">
                    @foreach($approvalItems as $item)
                    <tr>
                        <td class="p-4">
                            <div class="font-bold">{{ $item->user->name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $item->tipe_peminjaman == 'internal' ? $item->user->subbagian : $item->user->instansi }}</div>
                        </td>
                        <td class="p-4 font-medium">{{ $item->nama_kegiatan }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-[9px] font-bold uppercase">{{ $item->status }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <a href="#" class="text-blue-600 hover:underline font-bold">Detail / Proses</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- TAMPILAN PEMINJAM (Internal & Eksternal) --}}
    @else
    <div class="space-y-4">
        <!-- <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 tracking-wider">Statistik Saya & Unit Kerja</h3> -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Data User --}}
            <div class="grid grid-cols-3 gap-3 p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-800">
                <div class="col-span-3 text-[10px] font-bold text-blue-600 uppercase mb-1">Aktivitas Saya</div>
                <div class="bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                    <p class="text-[9px] text-slate-400 uppercase">Total</p>
                    <h4 class="text-xl font-bold">{{ $stats['user_total'] }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                    <p class="text-[9px] text-slate-400 uppercase">Pinjam</p>
                    <h4 class="text-xl font-bold text-orange-500">{{ $stats['user_dipinjam'] }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                    <p class="text-[9px] text-slate-400 uppercase">Selesai</p>
                    <h4 class="text-xl font-bold text-green-500">{{ $stats['user_selesai'] }}</h4>
                </div>
            </div>

            {{-- Data Unit --}}
            <div class="grid grid-cols-3 gap-3 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="col-span-3 text-[10px] font-bold text-slate-500 uppercase mb-1">Unit: {{ auth()->user()->subbagian ?? auth()->user()->instansi }}</div>
                <div class="bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                    <p class="text-[9px] text-slate-400 uppercase">Total</p>
                    <h4 class="text-xl font-bold">{{ $stats['unit_total'] }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                    <p class="text-[9px] text-slate-400 uppercase">Pinjam</p>
                    <h4 class="text-xl font-bold text-orange-500">{{ $stats['unit_dipinjam'] }}</h4>
                </div>
                <div class="bg-white dark:bg-slate-800 p-3 rounded-xl shadow-sm">
                    <p class="text-[9px] text-slate-400 uppercase">Selesai</p>
                    <h4 class="text-xl font-bold text-green-500">{{ $stats['unit_selesai'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6">Frekuensi Peminjaman Saya</h3>
            <div id="userQtyChart" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-sm text-slate-800 dark:text-white mb-6">Frekuensi Peminjaman Unit Kerja</h3>
            <div id="unitQtyChart" class="min-h-[300px]"></div>
        </div>
    </div>
    @endif

    {{-- Tabel Aktivitas Terakhir (Muncul di semua role) --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mt-6">
        <div class="p-4 border-b dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-sm">
                @if($isStaff)
                    10 Aktivitas Peminjaman Terakhir
                @else
                    10 Aktivitas Terakhir di Unit: {{ auth()->user()->subbagian ?? auth()->user()->instansi }}
                @endif
            </h3>
            <!-- <a href="#" class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">Lihat Semua</a> -->
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Peminjam</th>
                        <th class="p-4">Unit / Instansi</th>
                        <th class="p-4 text-center">Tgl Pinjam</th>
                        <th class="p-4 text-center">Tgl Kembali</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-700">
                    @forelse($latestPeminjaman as $lp)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="p-4 font-bold text-slate-700 dark:text-slate-200">{{ $lp->user->name }}</td>
                        <td class="p-4 text-slate-500">{{ $lp->tipe_peminjaman == 'internal' ? ($lp->user->subbagian ?? '-') : ($lp->user->instansi ?? '-') }}</td>
                        <td class="p-4 text-center">{{ \Carbon\Carbon::parse($lp->tgl_pinjam)->format('d M Y') }}</td>
                        <td class="p-4 text-center">{{ \Carbon\Carbon::parse($lp->tgl_kembali)->format('d M Y') }}</td>
                        <td class="p-4 text-center">
                            @php
                                $statusColor = [
                                    'Selesai' => 'bg-green-100 text-green-700',
                                    'Dipinjam' => 'bg-blue-100 text-blue-700',
                                    'Ditolak' => 'bg-red-100 text-red-700',
                                ][$lp->status] ?? 'bg-yellow-100 text-yellow-700';
                            @endphp
                            <span class="px-2 py-1 rounded-md text-[9px] font-bold uppercase {{ $statusColor }}">
                                {{ $lp->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-10 text-center text-slate-400">Tidak ada aktivitas di unit Anda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flatpickrConfig = {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d M Y",
            disableMobile: "true"
        };
        flatpickr("#start_date", flatpickrConfig);
        flatpickr("#end_date", flatpickrConfig);
    });

    @if($isStaff)
    // 1. Bar Chart: Frekuensi Peminjaman (Staff Only)
    new ApexCharts(document.querySelector("#combinedChart"), {
        series: [{ name: 'Total Pinjam', data: {!! json_encode($unitStats->pluck('total')) !!} }],
        chart: { type: 'bar', height: 300, toolbar: {show: false} },
        colors: ['#0d6efd'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '40%', dataLabels: { position: 'top' } } },
        xaxis: { 
            categories: {!! json_encode($unitStats->pluck('unit_name')) !!},
            labels: { rotate: -45, style: { fontSize: '9px' } }
        }
    }).render();

    // 2. Donut Chart: Top Asset (Staff Only)
    new ApexCharts(document.querySelector("#qtyChart"), {
        series: {!! json_encode($qtyChart->pluck('total')->map(fn($v)=>(int)$v)) !!},
        chart: { type: 'donut', height: 320 },
        labels: {!! json_encode($qtyChart->map(fn($q) => $q->asset->nama_asset ?? 'N/A')) !!},
        legend: { position: 'bottom', fontSize: '10px' },
        colors: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#fd7e14']
    }).render();

    @else
    // CHART KHUSUS PEMINJAM (2 PIE CHART)
    const donutBaseOptions = {
        chart: { type: 'donut', height: 320 },
        legend: { position: 'bottom', fontSize: '10px' },
        colors: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#fd7e14'],
        plotOptions: { pie: { donut: { labels: { show: true, total: { show: true, fontSize: '12px', fontWeight: 'bold' } } } } }
    };

    // Chart Unit
    new ApexCharts(document.querySelector("#unitQtyChart"), {
        ...donutBaseOptions,
        series: {!! json_encode($unitQtyChart->pluck('total')->map(fn($v)=>(int)$v)) !!},
        labels: {!! json_encode($unitQtyChart->map(fn($q) => $q->asset->nama_asset ?? 'N/A')) !!},
    }).render();

    // Chart User
    new ApexCharts(document.querySelector("#userQtyChart"), {
        ...donutBaseOptions,
        series: {!! json_encode($userQtyChart->pluck('total')->map(fn($v)=>(int)$v)) !!},
        labels: {!! json_encode($userQtyChart->map(fn($q) => $q->asset->nama_asset ?? 'N/A')) !!},
    }).render();
    @endif
</script>

<style>
    .flatpickr-calendar {
        font-family: inherit;
        border-radius: 12px !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        border: 1px solid #e2e8f0 !important;
        width: 260px !important; /* Ukuran lebih kecil */
    }
    .flatpickr-day {
        border-radius: 6px !important;
        height: 30px !important;
        line-height: 30px !important;
    }
    .flatpickr-current-month { font-size: 13px !important; }
    .flatpickr-weekday { font-size: 11px !important; }
    
    /* Perbaikan agar Input palsu Flatpickr (altInput) tidak berantakan */
    .flatpickr-input[readonly] {
        cursor: pointer !important;
    }
</style>
@endpush
@endsection