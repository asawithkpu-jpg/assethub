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
                <span class="text-gray-600 font-bold uppercase">peminjaman internal</span>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                <span class="text-primary font-bold uppercase">input peminjaman</span>
            </div>
        </li>
    </ol>
</nav>

<div x-data="peminjamanForm()" class="space-y-6">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Input Peminjaman Internal</h2>
        <div class="h-1 w-20 bg-primary mt-1"></div>
    </div>

    <form @submit.prevent="submitForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white dark:bg-darkCard p-5 rounded-lg border dark:border-gray-700 shadow-sm">
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-4">Informasi Peminjaman</p>
                    
                    <div class="grid grid-cols-1 gap-4 text-[11px]">
                        <div>
                            <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Nama Peminjam</label>
                            <input type="text" value="{{ auth()->user()->name }}" class="w-full bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded px-3 py-2 outline-none" readonly>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">NIP/NIK</label>
                                <input type="text" value="{{ auth()->user()->nip_nik }}" class="w-full bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded px-3 py-2 outline-none font-mono" readonly>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Subbagian</label>
                                <input type="text" value="{{ auth()->user()->subbagian }}" class="w-full bg-gray-50 dark:bg-gray-800 border dark:border-gray-700 rounded px-3 py-2 outline-none" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Nama Kegiatan</label>
                            <textarea x-model="formData.nama_kegiatan" rows="2" class="w-full bg-white dark:bg-gray-800 border dark:border-gray-700 rounded px-3 py-2 focus:ring-1 focus:ring-primary outline-none transition" placeholder="Tuliskan tujuan peminjaman..." required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 text-blue-500">Tgl Pinjam</label>
                                <input type="date" x-model="formData.tgl_pinjam" class="w-full bg-white dark:bg-gray-800 border dark:border-gray-700 rounded px-3 py-2 focus:ring-1 focus:ring-primary outline-none transition uppercase" required>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 text-red-500">Tgl Kembali</label>
                                <input type="date" x-model="formData.tgl_kembali" class="w-full bg-white dark:bg-gray-800 border dark:border-gray-700 rounded px-3 py-2 focus:ring-1 focus:ring-primary outline-none transition uppercase" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white dark:bg-darkCard p-5 rounded-lg border dark:border-gray-700 shadow-sm min-h-[450px] flex flex-col">
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-4">Daftar Barang yang Dipinjam</p>
                    
                    <div class="mb-4 flex flex-col sm:flex-row gap-2">
                        <div class="flex-1 min-w-0" wire:ignore>
                            <select id="selectAsset" class="w-full select2-custom">
                                <option value="">-- Pilih Aset --</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" 
                                            data-nama="{{ $asset->nama_asset }}" 
                                            data-kode="{{ $asset->kode_asset }}"
                                            data-satuan="{{ $asset->satuan }}"
                                            data-stok="{{ $asset->stok_tersedia }}">
                                        {{ $asset->kode_asset }} - {{ $asset->nama_asset }} (Tersedia: {{ $asset->stok_tersedia }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" @click="addBarang()" class="w-full sm:w-auto px-4 py-1 bg-primary text-white rounded text-[10px] font-bold uppercase tracking-wider hover:bg-blue-600 shadow-sm transition h-[32px]">
                            Tambah
                        </button>
                    </div>

                    <div class="flex-1 overflow-x-auto border dark:border-gray-800 rounded">
                        <table class="w-full text-left min-w-[550px]">
                            <thead class="bg-gray-50 dark:bg-gray-800 text-[9px] uppercase font-bold text-gray-500 border-b dark:border-gray-700">
                                <tr>
                                    <th class="px-3 py-2.5">Nama Asset</th>
                                    <th class="px-3 py-2.5 text-center w-20">Qty</th>
                                    <th class="px-3 py-2.5 w-32">Satuan</th>
                                    <th class="px-3 py-2.5 w-16 text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="text-[10px]">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-b dark:border-gray-800">
                                        <td class="py-3 px-3">
                                            <div class="text-[9px] text-primary font-bold" x-text="item.kode"></div>
                                            <div class="font-bold text-gray-800 dark:text-gray-200" x-text="item.nama"></div>
                                            <div class="text-[9px] text-gray-400" x-text="'Stok: ' + item.stok_max"></div>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" min="1" :max="item.stok_max" x-model="item.qty" 
                                                @input="if(parseInt(item.qty) > item.stok_max) { item.qty = item.stok_max; smallToast('warning', 'Maksimal stok: ' + item.stok_max); }"
                                                class="w-full bg-white dark:bg-gray-800 border dark:border-gray-700 rounded px-1 py-1 text-center font-bold">
                                        </td>
                                        <td class="px-3 py-2">
                                            <select x-model="item.satuan" class="w-full bg-white dark:bg-gray-800 border dark:border-gray-700 rounded px-2 py-1 outline-none text-[10px]">
                                                <option value="unit">Unit</option>
                                                <option value="pcs">Pcs</option>
                                                <option value="botol">Botol</option>
                                                <option value="lembar">Lembar</option>
                                                <option value="lain-lain">Lain-lain</option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="items.length === 0">
                                    <td colspan="4" class="px-3 py-10 text-center text-gray-400 italic font-medium">Belum ada barang dipilih</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('peminjaman-internal.index') }}" class="bg-gray-300 rounded px-4 py-2 text-[10px] font-bold uppercase text-gray-800 hover:text-gray-700 transition tracking-widest">Batal</a>
                        <button type="submit" 
                                :disabled="items.length === 0"
                                :class="items.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                                class="text-white px-4 py-2 rounded text-[10px] font-bold uppercase tracking-widest shadow-lg transition transform active:scale-105">
                            Simpan Pengajuan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Select2 Adjustment */
    .select2-container--default .select2-selection--single {
        height: 32px !important;
        font-size: 10px !important;
        border-color: #e5e7eb !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px !important;
    }
    .dark .select2-container--default .select2-selection--single {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
        color: #d1d5db !important;
    }
    .select2-container { width: 100% !important; }

    /* Custom CSS agar SWAL tidak terlalu besar */
    .swal2-popup-custom {
        width: 22rem !important; /* Ukuran lebih kecil/compact */
        padding: 1.25rem !important;
        border-radius: 0.75rem !important;
    }
    .swal2-title-custom {
        font-size: 14px !important;
        font-weight: 800 !important;
        color: #1f2937 !important;
        text-transform: uppercase;
    }
    .swal2-html-custom {
        font-size: 11px !important;
        color: #6b7280 !important;
    }
    .swal2-confirm-custom, .swal2-cancel-custom {
        padding: 0.5rem 1rem !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        margin: 0 4px !important;
    }

    /* Toast styling */
    .swal2-toast-custom {
        width: auto !important;
        padding: 0.5rem 1rem !important;
        font-size: 10px !important;
    }
</style>
@endsection

@push('scripts')
<script>
    function peminjamanForm() {
        return {
            items: [],
            formData: {
                nama_kegiatan: '',
                tgl_pinjam: '',
                tgl_kembali: ''
            },
            
            smallToast(icon, title) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500,
                    customClass: {
                        popup: 'swal2-toast-custom'
                    }
                });
            },

            addBarang() {
                let select = document.getElementById('selectAsset');
                let id = select.value;
                if (!id) return;
                
                let option = select.options[select.selectedIndex];
                let nama = option.getAttribute('data-nama');
                let kode = option.getAttribute('data-kode');
                let satuan = option.getAttribute('data-satuan');
                let stok = parseInt(option.getAttribute('data-stok'));

                if (this.items.find(i => i.id === id)) {
                    this.smallToast('warning', 'Barang sudah ada di daftar');
                    return;
                }

                if (stok <= 0) {
                    this.smallToast('error', 'Stok sedang kosong');
                    return;
                }

                this.items.push({ id: id, nama: nama, kode: kode, satuan: satuan, qty: 1, stok_max: stok });
                $(select).val(null).trigger('change');
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            async submitForm() {
                const result = await Swal.fire({
                    title: 'Simpan Pengajuan?',
                    text: "Pastikan data sudah benar.",
                    icon: 'question',
                    iconColor: '#0d6efd',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'swal2-popup-custom',
                        title: 'swal2-title-custom',
                        htmlContainer: 'swal2-html-custom',
                        confirmButton: 'swal2-confirm-custom bg-blue-600 hover:bg-blue-700 text-white rounded shadow-sm',
                        cancelButton: 'swal2-cancel-custom bg-gray-500 hover:bg-gray-600 text-white rounded shadow-sm'
                    }
                });

                if (result.isConfirmed) {
                    try {
                        let response = await fetch("{{ route('peminjaman-internal.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ...this.formData,
                                items: this.items
                            })
                        });

                        let res = await response.json();
                        if (res.success) {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Berhasil', 
                                text: res.message, 
                                timer: 2000, 
                                showConfirmButton: false,
                                customClass: { popup: 'swal2-popup-custom', title: 'swal2-title-custom', htmlContainer: 'swal2-html-custom' }
                            }).then(() => window.location.href = "{{ route('peminjaman-internal.index') }}");
                        } else {
                            throw new Error(res.message);
                        }
                    } catch (error) {
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Gagal', 
                            text: error.message,
                            customClass: { popup: 'swal2-popup-custom', title: 'swal2-title-custom', htmlContainer: 'swal2-html-custom' }
                        });
                    }
                }
            }
        }
    }

    $(document).ready(function() {
        $('#selectAsset').select2({
            placeholder: "-- Pilih Asset --",
            allowClear: true
        });
    });
</script>
@endpush