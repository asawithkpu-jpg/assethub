@extends('layouts.app')

@section('content')
<div x-data="{ openModal: false, editMode: false, currentUser: { id: '', name: '', nip_nik: '', subbagian: '', jabatan: '', instansi: '', hp: '', role: '', password: '' } }">
    {{-- Breadcrumb --}}
    <nav class="flex mb-3" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-[10px] font-medium uppercase tracking-wider">
            <li class="inline-flex items-center text-gray-600 font-bold">PENGATURAN</li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                    <span class="text-primary font-bold">MANAJEMEN USER</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">Manajemen User</h2>
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest leading-tight">DAFTAR PEGAWAI & OPERATOR SISTEM</p>
        </div>
        <button @click="openModal = true; editMode = false; currentUser = { id: '', name: '', nip_nik: '', subbagian: '', role: '', password: '' }" class="bg-primary hover:bg-blue-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest shrink-0">
            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Tambah User
        </button>
    </div>

    <div class="bg-white dark:bg-darkCard rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="p-4 DatatableCompactWrapper">
            <table id="userTable" class="w-full text-[11px] text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase text-[9px] font-bold tracking-widest border-b dark:border-gray-700">
                    <tr>
                        <th class="px-3 py-2.5 w-[30%] text-gray-500">PEGAWAI / IDENTITAS</th>
                        <th class="px-3 py-2.5 text-gray-500">JABATAN / SUBBAGIAN / INSTANSI</th>
                        <th class="px-3 py-2.5 text-gray-500">ROLE</th>
                        <th class="px-3 py-2.5 text-center w-20 text-gray-500">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                        <td class="px-3 py-2.5">
                            <p class="font-bold text-gray-700 dark:text-gray-100 leading-tight">{{ $user->name }}</p>
                            <p class="text-[9px] font-bold text-primary mt-0.5 tracking-tighter">{{ $user->nip_nik }}</p>
                        </td>
                        <td class="px-3 py-2.5">
                            <p class="font-bold text-gray-700 dark:text-gray-100 leading-tight">{{ $user->jabatan }}</p>
                            <p class="text-gray-700 dark:text-gray-100 leading-tight">{{ $user->subbagian }}</p>
                            <p class="text-gray-700 dark:text-gray-100 leading-tight">{{ $user->instansi }}</p>
                        </td>
                        <td class="px-3 py-2.5">
                            @foreach($user->roles as $role)
                                <span class="bg-primary/10 text-primary border border-primary/20 px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-widest">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <div class="flex justify-center space-x-1">
                                <button @click="openModal = true; editMode = true; currentUser = { id: '{{ $user->id }}', name: '{{ $user->name }}', nip_nik: '{{ $user->nip_nik }}', subbagian: '{{ $user->subbagian }}', jabatan: '{{ $user->jabatan }}', instansi: '{{ $user->instansi }}', hp: '{{ $user->hp }}', role: '{{ $user->roles->first()?->name }}', password: '' }" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"></path></svg>
                                </button>
                                <button onclick="confirmDeleteUser({{ $user->id }})" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"></path></svg>
                                </button>
                                <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal User --}}
    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-0">
            <div x-show="openModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openModal = false"></div>
            <div x-show="openModal" class="inline-block bg-white dark:bg-darkCard rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-md w-full border dark:border-gray-700">
                <form :action="editMode ? '{{ url('users') }}/' + currentUser.id : '{{ route('users.store') }}'" method="POST">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="px-4 py-3 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50">
                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest" x-text="editMode ? 'UPDATE AKUN USER' : 'REGISTER USER BARU'"></span>
                        <button type="button" @click="openModal = false" class="text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"></path></svg></button>
                    </div>

                    <div class="p-5 space-y-3.5">
                        <div>
                            <label class="block mb-1 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Nama Lengkap</label>
                            <input type="text" name="name" x-model="currentUser.name" required class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:ring-1 focus:ring-primary outline-none transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block mb-1 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">NIP / NIK</label>
                                <input type="text" name="nip_nik" x-model="currentUser.nip_nik" required class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs tracking-widest focus:ring-1 focus:ring-primary outline-none transition">
                            </div>
                            <div>
                                <label class="block mb-1 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">No. HP</label>
                                <input type="text" name="hp" x-model="currentUser.hp" class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:ring-1 focus:ring-primary outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Subbagian</label>
                            <input type="text" name="subbagian" x-model="currentUser.subbagian" class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:ring-1 focus:ring-primary outline-none transition">
                        </div>
                        <div>
                            <label class="block mb-1 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Jabatan</label>
                            <input type="text" name="jabatan" x-model="currentUser.jabatan" class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:ring-1 focus:ring-primary outline-none transition">
                        </div>
                        <div>
                            <label class="block mb-1 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Instansi</label>
                            <input type="text" name="instansi" x-model="currentUser.instansi" class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:ring-1 focus:ring-primary outline-none transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block mb-1 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Hak Akses (Role)</label>
                                <select name="role" x-model="currentUser.role" required class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:ring-1 focus:ring-primary outline-none transition">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Password</label>
                                <input type="password" name="password" class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs focus:ring-1 focus:ring-primary outline-none transition" :placeholder="editMode ? 'Kosongkan jika tidak ingin ganti' : 'Minimal 6 karakter'">
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700 flex justify-end space-x-2">
                        <button type="button" @click="openModal = false" class="px-3 py-1.5 rounded text-[10px] font-bold text-gray-500 hover:bg-gray-200 uppercase transition">BATAL</button>
                        <button type="submit" class="px-4 py-1.5 rounded bg-primary text-white text-[10px] font-bold uppercase tracking-wider transition">SIMPAN USER</button>
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
        $('#userTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: { search: "", searchPlaceholder: "Cari pegawai..." },
            dom: '<"flex justify-between items-center gap-4 mb-3"lf>rt<"flex justify-between items-center mt-3 text-xs"ip>'
        });

        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                width: 'auto', // Lebar otomatis mengikuti panjang teks
                padding: '0.4rem 0.8rem', // Padding atas-bawah tipis, kiri-kanan lebih longgar
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                customClass: {
                    // Membuat popup satu baris dan membatasi tinggi
                    popup: 'flex items-center rounded-md border dark:border-gray-700 shadow-md bg-white dark:bg-gray-800 my-2 mx-4', 
                    // Memberi jarak (margin) pada icon agar tidak mepet teks
                    icon: 'm-0 mr-2 scale-75', 
                    // Teks satu baris tanpa wrapping
                    title: 'text-[10px] font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap p-0 m-0' 
                }
            });
        @endif
    });

    function confirmDeleteUser(id) {
        Swal.fire({
            title: 'HAPUS USER?',
            text: "User tidak akan bisa login lagi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4680ff',
            cancelButtonColor: '#f44236',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            width: '300px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl',
                title: 'text-sm font-bold pt-4',
                htmlContainer: 'text-xs pt-1 pb-2',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-user-' + id).submit();
            }
        });
    }
</script>
@endpush