@extends('layouts.app')

@section('content')
<div x-data="{ 
    openModal: false, 
    editMode: false, 
    currentAsset: {
        stok_tersedia: 0,
        rusak_ringan: 0,
        rusak_berat: 0,
        status: 'aktif'
    },
    async updateCode(kat) {
        if(!kat || this.editMode) return; // Jangan ganti kode kalau mode edit
        try {
            const response = await fetch(`/assets/get-next-code/${kat}`);
            const data = await response.json();
            this.currentAsset.kode_asset = data.code;
        } catch (error) {
            console.error('Gagal mengambil kode:', error);
        }
    }
}">
    <nav class="flex mb-3" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-[10px] font-medium uppercase tracking-wider">
            <li class="inline-flex items-center">
                <span class="text-gray-600 font-bold">Inventaris & Layanan</span>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                    <span class="text-primary font-bold">Master Asset</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">Master Asset</h2>
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest leading-tight">Daftar Inventaris Barang</p>
        </div>
        <button @click="openModal = true; editMode = false; currentAsset = {stok_tersedia:0, rusak_ringan:0, rusak_berat:0, status:'aktif'}" class="bg-primary hover:bg-blue-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest shrink-0">
            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah Asset
        </button>
    </div>

    <div class="bg-white dark:bg-darkCard rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="p-4 DatatableCompactWrapper"> <table id="assetTable" class="w-full text-[11px] text-left border-collapse dataTable no-footer">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase text-[9px] font-bold tracking-widest border-b dark:border-gray-700">
                    <tr>
                        <th class="px-3 py-2.5 w-[35%]">Asset & Identitas</th>
                        <th class="px-3 py-2.5">Kategori</th>
                        <th class="px-3 py-2.5 text-center">Tersedia</th>
                        <th class="px-3 py-2.5 text-center">R. Ringan</th>
                        <th class="px-3 py-2.5 text-center">R. Berat</th>
                        <th class="px-3 py-2.5 text-center">Status</th> <th class="px-3 py-2.5 text-center w-20">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @foreach($assets as $asset)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition text-[11px]">
                        <td class="px-3 py-2.5">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded bg-gray-100 dark:bg-gray-700 flex-shrink-0 border dark:border-gray-600 overflow-hidden shadow-inner cursor-pointer hover:ring-2 hover:ring-primary transition-all group"
                                    @if($asset->foto) 
                                        @click="$dispatch('preview-image', '{{ asset('storage/'.$asset->foto) }}')" 
                                    @endif>
                                    
                                    @if($asset->foto)
                                        <img src="{{ asset('storage/'.$asset->foto) }}" class="object-cover w-full h-full group-hover:scale-110 transition-transform">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <p class="font-bold text-gray-700 dark:text-gray-100 uppercase leading-tight">{{ $asset->nama_asset }}</p>
                                    <p class="text-[9px] font-mono font-bold text-primary mt-0.5 tracking-tighter">{{ $asset->kode_asset }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-2.5">
                            <span class="text-[9px] font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded italic leading-none whitespace-nowrap">{{ $asset->kategori }}</span>
                        </td>
                        <td class="px-3 py-2.5 text-center font-bold text-green-600">{{ $asset->stok_tersedia }}</td>
                        <td class="px-3 py-2.5 text-center font-bold text-orange-500">{{ $asset->rusak_ringan }}</td>
                        <td class="px-3 py-2.5 text-center font-bold text-red-500">{{ $asset->rusak_berat }}</td>
                        <td class="px-3 py-2.5 text-center"> @if($asset->status == 'aktif')
                                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-[8px] font-bold uppercase tracking-wider border border-green-200 dark:border-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-[8px] font-bold uppercase tracking-wider border border-red-200 dark:border-red-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5">
                            <div class="flex justify-center space-x-1">
                                <button @click="openModal = true; editMode = true; currentAsset = {{ $asset }}" class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"></path></svg>
                                </button>
                                <button onclick="confirmDelete({{ $asset->id }})" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"></path></svg>
                                </button>
                                <form id="delete-form-{{ $asset->id }}" action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div x-data="{ open: false, src: '' }" 
        @preview-image.window="open = true; src = $event.detail"
        x-show="open" 
        x-cloak
        class="fixed inset-0 z- flex items-center justify-center p-4">
        
        <div x-show="open" x-transition.opacity @click="open = false" 
            class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

        <div x-show="open" x-transition.scale.95 @click="open = false" 
            class="relative max-w-full max-h-full cursor-zoom-out">
            <img :src="src" class="rounded shadow-2xl max-h-[90vh] mx-auto border-2 border-white dark:border-gray-700">
            
            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-black/50 text-white text-[10px] px-2 py-1 rounded opacity-0 hover:opacity-100 transition-opacity">
                Klik di mana saja untuk menutup
            </div>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z- overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" class="fixed inset-0 transition-opacity" @click="openModal = false">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white dark:bg-darkCard rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md w-full border dark:border-gray-700">
                <form :action="editMode ? '/assets/' + currentAsset.id : '{{ route('assets.store') }}'" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-4 py-3 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                        <span class="text-[10px] font-bold text-gray-600 dark:text-white uppercase tracking-widest" x-text="editMode ? 'Update Data Asset' : 'Input Asset Baru'"></span>
                        <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"></path></svg></button>
                    </div>

                    <div class="p-5 space-y-3.5">
                        <div class="space-y-3.5 text-[9px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">
                            
                            <div>
                                <label class="block mb-1">Kategori Asset</label>
                                <select name="kategori" x-model="currentAsset.kategori" @change="updateCode($event.target.value)" required class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-medium focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Peralatan Kantor dan Mesin">Peralatan Kantor & Mesin</option>
                                    <option value="Logistik Pemilu">Logistik Pemilu</option>
                                </select>
                            </div>

                            <div>
                                <label class="block mb-1 text-primary">Kode Asset</label>
                                <input type="text" name="kode_asset" x-model="currentAsset.kode_asset" placeholder="Pilih Kategori Dulu..." class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-xs font-medium tracking-widest">
                            </div>

                            <div>
                                <label class="block mb-1">Nama Barang / Asset</label>
                                <input type="text" name="nama_asset" x-model="currentAsset.nama_asset" required class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-medium focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition">
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2.5">
                                <div>
                                    <label class="block mb-1 text-green-600">Stok Baik</label>
                                    <input type="number" name="stok_tersedia" x-model="currentAsset.stok_tersedia" required class="w-full px-2 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-semibold text-center">
                                </div>
                                <div>
                                    <label class="block mb-1 text-orange-500 whitespace-nowrap">Rusak Ringan</label>
                                    <input type="number" name="rusak_ringan" x-model="currentAsset.rusak_ringan" class="w-full px-2 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-semibold text-center">
                                </div>
                                <div>
                                    <label class="block mb-1 text-red-500 whitespace-nowrap">Rusak Berat</label>
                                    <input type="number" name="rusak_berat" x-model="currentAsset.rusak_berat" class="w-full px-2 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-semibold text-center">
                                </div>
                            </div>

                            <div>
                                <label class="block mb-1">Lokasi Penyimpanan</label>
                                <input type="text" name="lokasi" x-model="currentAsset.lokasi" class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition">
                            </div>

                            <div>
                                <label class="block mb-1">Status Asset</label>
                                <select name="status" x-model="currentAsset.status" required class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-semibold focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            <div>
                                <label class="block mb-1">Foto Asset</label>
                                <input type="file" name="foto" class="block w-full text-[9px] text-gray-400 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-50 file:text-primary file:font-bold hover:file:bg-blue-100 transition file:cursor-pointer cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700 flex justify-end space-x-2 shrink-0">
                        <button type="button" @click="openModal = false" class="px-3 py-1.5 rounded text-[10px] font-bold text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 uppercase transition">Batal</button>
                        <button type="submit" class="px-4 py-1.5 rounded bg-primary hover:bg-blue-600 text-white text-[10px] font-bold shadow-sm uppercase tracking-wider transition">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // FIX DATATABLE COMPACT (Gambar 1)
        $('#assetTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']], // Urutkan asset terbaru diatas
            language: {
                search: "",
                searchPlaceholder: "Cari data asset..."
            },
            // Custom layout dom agar rapi dan kecil
            dom: '<"flex justify-between items-center gap-4 mb-3"lf>rt<"flex justify-between items-center mt-3 text-xs"ip>'
        });

        // FIX NOTIF TOASTR (Poin 4) - Di pojok kanan bawah
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end', // Pojok kanan bawah
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-xl bg-white dark:bg-darkCard',
                title: 'text-xs font-bold text-gray-700 dark:text-gray-100',
                icon: 'text-xs'
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif
        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
        @endif
    });

    // FIX KONFIRM HAPUS MUNGIL (Poin 4) - Di tengah ukuran kecil
    function confirmDelete(id) {
        Swal.fire({
            title: 'HAPUS ASSET?',
            text: "Data akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4680ff', // Biru primary
            cancelButtonColor: '#f44236', // Merah able pro
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            width: '280px', // Perkecil ukuran box
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl',
                title: 'text-sm font-bold pt-4',
                htmlContainer: 'text-xs pt-1 pb-2',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form hapus yang tersembunyi
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush