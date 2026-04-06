@extends('layouts.app')

@section('content')
<div x-data="peminjamanForm()" class="space-y-6">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Input Peminjaman Eksternal</h2>
        <div class="h-1 w-20 bg-primary mt-1"></div>
    </div>

    <form @submit.prevent="submitForm" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white dark:bg-darkCard p-5 rounded-lg border dark:border-gray-700 shadow-sm">
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-4 border-b dark:border-gray-800 pb-2">Informasi Peminjam</p>
                    
                    <div class="grid grid-cols-1 gap-4 text-[11px]">
                        <div>
                            <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Nama Peminjam</label>
                            <input type="text" value="{{ auth()->user()->name }}" class="w-full bg-gray-100 dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none dark:text-gray-400 font-bold" readonly>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">NIP / NIK / NIS</label>
                                <input type="text" x-model="formData.nip_nik" class="w-full dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200">
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">No. HP / Telepon</label>
                                <input type="text" x-model="formData.hp" placeholder="0812..." class="w-full dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Jabatan</label>
                                <input type="text" x-model="formData.jabatan" placeholder="Contoh: Ketua Umum" class="w-full dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200">
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Instansi / Organisasi</label>
                                <input type="text" x-model="formData.instansi" placeholder="Nama Instansi" class="w-full dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200">
                            </div>
                        </div>
                        <div class="border-t dark:border-gray-800 pt-2 mt-2">
                            <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 text-[10px]">Detail Kegiatan</label>
                            <textarea x-model="formData.nama_kegiatan" rows="2" placeholder="Sebutkan tujuan kegiatan secara detail..." class="w-full dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">No. Surat</label>
                                <input type="text" x-model="formData.nomor_surat" placeholder="No. Surat Masuk" class="w-full bg-gray-50 dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200">
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Tgl Surat</label>
                                <input type="date" x-model="formData.tgl_surat" class="w-full bg-gray-50 dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200">
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Unggah PDF Surat (Maks 5MB)</label>
                            <input type="file" @change="handleFileUpload" accept="application/pdf"
                                   class="w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-primary file:text-white hover:file:bg-blue-600 cursor-pointer">
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-darkCard p-5 rounded-lg border dark:border-gray-700 shadow-sm">
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-4 border-b dark:border-gray-800 pb-2">Waktu Peminjaman</p>
                    <div class="grid grid-cols-2 gap-4 text-[11px]">
                        <div>
                            <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Tgl Pinjam</label>
                            <input type="date" x-model="formData.tgl_pinjam" class="w-full bg-gray-50 dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Tgl Kembali</label>
                            <input type="date" x-model="formData.tgl_kembali" class="w-full bg-gray-50 dark:bg-gray-900 border dark:border-gray-700 rounded px-3 py-2 outline-none focus:border-primary transition dark:text-gray-200">
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white dark:bg-darkCard p-5 rounded-lg border dark:border-gray-700 shadow-sm h-full">
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-4 border-b dark:border-gray-800 pb-2 text-right">Daftar Asset yang Dipinjam</p>
                    
                    <div class="flex flex-col md:flex-row gap-2 mb-6" wire:ignore>
                        <div class="flex-1 min-w-0"> {{-- min-w-0 penting agar Select2 tidak menjebol parent --}}
                            <select id="selectAsset" class="w-full">
                                <option value="">-- Cari Kode atau Nama Asset --</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" 
                                            data-nama="{{ $asset->nama_asset }}" 
                                            data-kode="{{ $asset->kode_asset }}" 
                                            data-stok="{{ $asset->stok_tersedia }}" 
                                            data-satuan="{{ $asset->satuan }}">
                                        {{ $asset->kode_asset }} - {{ $asset->nama_asset }} (Tersedia: {{ $asset->stok_tersedia }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" @click="addItem" class="w-full md:w-auto bg-primary text-white px-4 py-2 md:py-1 rounded text-[10px] font-bold uppercase tracking-wider hover:bg-blue-600 transition shadow-sm shrink-0 h-[38px] md:h-auto">
                            Tambah
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] uppercase text-gray-500 border-b dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                                    <th class="py-3 px-3 font-bold">Nama Asset</th>
                                    <th class="py-3 px-3 font-bold w-24 text-center">Qty</th>
                                    <th class="py-3 px-3 font-bold w-32 text-center">Satuan</th>
                                    <th class="py-3 px-3 font-bold w-16 text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="text-[11px]">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                        <td class="py-3 px-3">
                                            <div class="text-[9px] text-primary font-bold" x-text="item.kode_asset"></div>
                                            <div class="font-bold text-gray-800 dark:text-gray-200" x-text="item.nama_asset"></div>
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
                                        <td class="py-3 px-3 text-center">
                                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 transition">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="items.length === 0">
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-gray-400 italic text-[10px] uppercase tracking-widest bg-gray-50/20 dark:bg-gray-800/10">Belum ada asset yang dipilih</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 border-t dark:border-gray-800 pt-6">
                        <a href="{{ route('peminjaman-eksternal.index') }}" class="bg-gray-300 rounded px-4 py-2 text-[10px] font-bold uppercase text-gray-800 hover:text-gray-700 transition tracking-widest">Batal</a>
                        <button type="submit" 
                            :disabled="items.length === 0"
                            :class="items.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                            class="text-white px-6 py-2 rounded text-[10px] font-bold uppercase tracking-widest shadow-lg transition-all">
                            Simpan Pengajuan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Toast styling */
    .swal2-toast-custom {
        width: auto !important;
        padding: 0.5rem 1rem !important;
        font-size: 10px !important;
    }
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
</style>
@endsection

@push('scripts')
<script>
    function peminjamanForm() {
        return {
            items: [],
            formData: {
                nip_nik: '{{ auth()->user()->nip_nik }}',
                hp: '{{ auth()->user()->hp }}',
                jabatan: '{{ auth()->user()->jabatan }}',
                instansi: '{{ auth()->user()->instansi }}',
                nama_kegiatan: '',
                nomor_surat: '',
                tgl_surat: '',
                tgl_pinjam: '',
                tgl_kembali: '',
                file_surat: null
            },

            smallToast(icon, title) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'swal2-toast-custom'
                    }
                });
            },
            
            handleFileUpload(e) {
                const fileInput = e.target;
                if (fileInput.files.length > 0) {
                    // AMBIL INDEX - Ini kuncinya
                    this.formData.file_surat = fileInput.files[0];
                    console.log("File yang siap dikirim:", this.formData.file_surat);
                }
            },

            addItem() {
                let select = $('#selectAsset');
                let id = select.val();
                if (!id) return;

                let selectedOption = select.find(':selected');
                
                if (this.items.find(i => i.asset_id == id)) {
                    Swal.fire({ icon: 'warning', title: 'Sudah ada di daftar', toast: true, position: 'top', showConfirmButton: false, timer: 2000, customClass: { popup: 'swal2-toast-custom' } });
                    return;
                }

                this.items.push({
                    asset_id: id,
                    nama_asset: selectedOption.data('nama'),
                    kode_asset: selectedOption.data('kode'),
                    stok_max: selectedOption.data('stok'),
                    satuan: selectedOption.data('satuan') || 'unit',
                    qty: 1
                });
                
                select.val(null).trigger('change');
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            async submitForm() {
                if (this.items.length === 0) {
                    Swal.fire({ icon: 'error', title: 'Pilih minimal 1 asset', toast: true, position: 'top', showConfirmButton: false, timer: 2000, customClass: { popup: 'swal2-toast-custom' } });
                    return;
                }

                if (!this.formData.file_surat || this.formData.file_surat.length === 0) {
                    Swal.fire({ icon: 'error', title: 'Surat PDF wajib diunggah', toast: true, position: 'top', showConfirmButton: false, timer: 2000, customClass: { popup: 'swal2-toast-custom' } });
                    return;
                }

                const data = new FormData();
                data.append('nip_nik', this.formData.nip_nik);
                data.append('hp', this.formData.hp);
                data.append('jabatan', this.formData.jabatan);
                data.append('instansi', this.formData.instansi);
                data.append('nama_kegiatan', this.formData.nama_kegiatan);
                data.append('nomor_surat', this.formData.nomor_surat);
                data.append('tgl_surat', this.formData.tgl_surat);
                data.append('tgl_pinjam', this.formData.tgl_pinjam);
                data.append('tgl_kembali', this.formData.tgl_kembali);
                
                // Perbaikan pengiriman file
                data.append('file_surat', this.formData.file_surat);
                
                this.items.forEach((item, index) => {
                    data.append(`items[${index}][asset_id]`, item.asset_id);
                    data.append(`items[${index}][qty]`, item.qty);
                    data.append(`items[${index}][satuan]`, item.satuan);
                });

                Swal.fire({ 
                    title: 'Menyimpan...', 
                    allowOutsideClick: false, 
                    didOpen: () => Swal.showLoading(),
                    customClass: {
                        popup: 'swal2-popup-custom',
                        title: 'swal2-title-custom',
                        htmlContainer: 'swal2-html-custom',
                        confirmButton: 'swal2-confirm-custom bg-blue-600 hover:bg-blue-700 text-white rounded shadow-sm',
                        cancelButton: 'swal2-cancel-custom bg-gray-500 hover:bg-gray-600 text-white rounded shadow-sm'
                    }
                 });

                try {
                    let response = await fetch("{{ route('peminjaman-eksternal.store') }}", {
                        method: "POST",
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json' },
                        body: data
                    });

                    let result = await response.json();

                    if (response.status === 422) {
                        // Jika masih error validasi, tampilkan detailnya di console
                        console.error("Detail Error Validasi:", result.errors);
                        Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: result.message, customClass: {
                            popup: 'swal2-popup-custom',
                            title: 'swal2-title-custom',
                            htmlContainer: 'swal2-html-custom',
                            confirmButton: 'swal2-confirm-custom bg-blue-600 hover:bg-blue-700 text-white rounded shadow-sm',
                            cancelButton: 'swal2-cancel-custom bg-gray-500 hover:bg-gray-600 text-white rounded shadow-sm'
                        } });
                        return;
                    }

                    if (result.success) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Berhasil', 
                            text: result.message,
                            customClass: {
                                popup: 'swal2-popup-custom',
                                title: 'swal2-title-custom',
                                htmlContainer: 'swal2-html-custom',
                                confirmButton: 'swal2-confirm-custom bg-blue-600 hover:bg-blue-700 text-white rounded shadow-sm',
                                cancelButton: 'swal2-cancel-custom bg-gray-500 hover:bg-gray-600 text-white rounded shadow-sm'
                            }
                        }).then(() => {
                            window.location.href = "{{ route('peminjaman-eksternal.index') }}";
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, customClass: {
                            popup: 'swal2-popup-custom',
                            title: 'swal2-title-custom',
                            htmlContainer: 'swal2-html-custom',
                            confirmButton: 'swal2-confirm-custom bg-blue-600 hover:bg-blue-700 text-white rounded shadow-sm',
                            cancelButton: 'swal2-cancel-custom bg-gray-500 hover:bg-gray-600 text-white rounded shadow-sm'
                        } });
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem',
                            customClass: {
                            popup: 'swal2-popup-custom',
                            title: 'swal2-title-custom',
                            htmlContainer: 'swal2-html-custom',
                            confirmButton: 'swal2-confirm-custom bg-blue-600 hover:bg-blue-700 text-white rounded shadow-sm',
                            cancelButton: 'swal2-cancel-custom bg-gray-500 hover:bg-gray-600 text-white rounded shadow-sm'
                        }
                     });
                }
            }
        }
    }

    $(document).ready(function() {
        $('#selectAsset').select2({
            placeholder: "-- Cari Kode atau Nama Asset --",
            allowClear: true,
            width: '100%',
            dropdownParent: $('[wire\\:ignore]')
        });
    });
</script>
@endpush