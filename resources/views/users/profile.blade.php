@extends('layouts.app')

@section('content')
<div class="p-4 md:p-6 w-full">
    <div class="mb-5">
        <nav class="flex mb-1" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-[9px] font-bold uppercase tracking-widest text-gray-400 hover:text-blue-600 transition-colors">
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-blue-600">Pengaturan Profile</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h2 class="text-lg font-extrabold text-gray-800 dark:text-white tracking-tight">Pengaturan Profile</h2>
    </div>

    <div class="w-full bg-white dark:bg-darkCard rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-5 md:p-7">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-5">
                    
                    <div class="md:col-span-2 flex items-center space-x-2 mb-1">
                        <span class="h-[2px] w-4 bg-blue-600"></span>
                        <h3 class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Informasi Pengguna</h3>
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                            class="w-full px-0 py-1 text-[11px] font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-600 focus:border-blue-600 focus:ring-0 dark:text-white transition-all">
                        @error('name') <span class="text-[9px] text-red-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-1">NIP / NIK / NIS</label>
                        <input type="text" name="nip_nik" value="{{ old('nip_nik', $user->nip_nik) }}" 
                            class="w-full px-0 py-1 text-[11px] font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-600 focus:border-blue-600 focus:ring-0 dark:text-white transition-all">
                        @error('nip_nik') <span class="text-[9px] text-red-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-1">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" 
                            class="w-full px-0 py-1 text-[11px] font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-600 focus:border-blue-600 focus:ring-0 dark:text-white transition-all">
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-1">No. HP</label>
                        <input type="text" name="hp" value="{{ old('hp', $user->hp) }}" 
                            class="w-full px-0 py-1 text-[11px] font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-600 focus:border-blue-600 focus:ring-0 dark:text-white transition-all">
                    </div>

                    <div class="md:col-span-2 group">
                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-1">Unit Kerja / Instansi</label>
                        <input type="text" name="instansi" value="{{ old('instansi', $user->instansi) }}" 
                            class="w-full px-0 py-1 text-[11px] font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-600 focus:border-blue-600 focus:ring-0 dark:text-white transition-all">
                    </div>

                    <div class="md:col-span-2 flex items-center space-x-2 mt-4 mb-1">
                        <span class="h-[2px] w-4 bg-red-500"></span>
                        <h3 class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Keamanan Akun</h3>
                        <span class="hidden sm:block text-[9px] text-gray-400 italic">Biarkan password kosong jika tidak ingin mengubah.</span>
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-1 group-focus-within:text-red-500 transition-colors">Password Baru</label>
                        <input type="password" name="password" 
                            class="w-full px-0 py-1 text-[11px] font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-600 focus:border-red-500 focus:ring-0 dark:text-white transition-all" placeholder="••••••••">
                        @error('password') <span class="text-[9px] text-red-500 font-bold italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-bold text-gray-500 uppercase mb-1 group-focus-within:text-red-500 transition-colors">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" 
                            class="w-full px-0 py-1 text-[11px] font-semibold bg-transparent border-0 border-b border-gray-200 dark:border-gray-600 focus:border-red-500 focus:ring-0 dark:text-white transition-all" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="px-5 py-3 bg-gray-50 dark:bg-darkCard/30 border-t dark:border-gray-700 flex justify-end items-center space-x-3">
                <span class="hidden sm:block text-[9px] text-gray-400 italic">Periksa kembali data Anda sebelum menyimpan.</span>
                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold uppercase tracking-tighter rounded transition-all shadow-sm active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SweetAlert2 Logic --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000, 
            timerProgressBar: true,
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
            width: 'auto', 
            padding: '0.5rem 1rem',
            customClass: {
                popup: 'rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm',
                title: 'text-[9px] font-bold tracking-tight' 
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
        @endif

        @if($errors->any())
            Toast.fire({
                icon: 'error',
                title: "{{ $errors->first() }}"
            });
        @endif
    });
</script>
@endpush
@endsection