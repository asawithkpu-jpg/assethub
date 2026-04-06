@extends('layouts.app')

@section('content')
<div x-data="{ openModal: false, editMode: false, currentRole: { id: '', name: '', permissions: [] } }">
    {{-- Breadcrumb --}}
    <nav class="flex mb-3" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-[10px] font-medium uppercase tracking-wider">
            <li class="inline-flex items-center text-gray-600 font-bold">pengaturan</li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                    <span class="text-primary font-bold">role & akses</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">Role & Hak Akses</h2>
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest leading-tight">konfigurasi otoritas pengguna</p>
        </div>
        <button @click="openModal = true; editMode = false; currentRole = { id: '', name: '', permissions: [] }" class="bg-primary hover:bg-blue-600 text-white px-3 py-1.5 rounded text-[10px] font-bold shadow transition-all flex items-center uppercase tracking-widest shrink-0">
            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            tambah role
        </button>
    </div>

    <div class="bg-white dark:bg-darkCard rounded-lg shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="p-4 DatatableCompactWrapper">
            <div class="table-responsive">
                <table id="roleTable" class="w-full text-[11px] text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 uppercase text-[9px] font-bold tracking-widest border-b dark:border-gray-700">
                        <tr>
                            <th class="px-3 py-2.5 w-[20%]">role</th>
                            <th class="px-3 py-2.5">akses (permissions)</th>
                            <th class="px-3 py-2.5 text-center w-20">aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @foreach($roles as $role)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                            <td class="px-3 py-2.5 font-bold text-gray-700 dark:text-gray-200 lowercase tracking-tight">{{ $role->name }}</td>
                            <td class="px-3 py-2.5">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($role->permissions as $perm)
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 px-1.5 py-0.5 rounded border border-blue-100 dark:border-blue-800 lowercase tracking-tighter">{{ $perm->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-3 py-2.5">
                                <div class="flex justify-center space-x-1">
                                    <button @click="openModal = true; editMode = true; currentRole = { id: '{{ $role->id }}', name: '{{ $role->name }}', permissions: {{ $role->permissions->pluck('name') }} }" class="p-1 text-blue-500 hover:bg-blue-50 rounded transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2.5"></path></svg>
                                    </button>
                                    <button onclick="confirmDeleteRole({{ $role->id }})" class="p-1 text-red-500 hover:bg-red-50 rounded transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5"></path></svg>
                                    </button>
                                    <form id="delete-role-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Role --}}
    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openModal = false"></div>
            
            <div x-show="openModal" x-transition:enter="ease-out duration-200" class="relative bg-white dark:bg-darkCard rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-md w-full border dark:border-gray-700">
                <form :action="editMode ? '/roles/' + currentRole.id : '{{ route('roles.store') }}'" method="POST">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                    
                    <div class="px-4 py-3 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                        <span class="text-[10px] font-bold text-gray-600 dark:text-white uppercase tracking-widest" x-text="editMode ? 'edit role' : 'tambah role baru'"></span>
                        <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5"></path></svg></button>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block mb-1 text-[9px] font-bold text-primary uppercase tracking-tighter">nama role</label>
                            <input type="text" name="name" x-model="currentRole.name" required class="w-full px-2.5 py-1.5 rounded border dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-bold lowercase tracking-tight focus:ring-1 focus:ring-primary outline-none transition">
                        </div>

                        <div>
                            <label class="block mb-2 text-[9px] font-bold text-gray-500 uppercase tracking-tighter">daftar hak akses</label>
                            <div class="grid grid-cols-2 gap-1.5 max-h-60 overflow-y-auto p-2 border dark:border-gray-700 rounded bg-gray-50 dark:bg-gray-800/50">
                                @foreach($permissions as $perm)
                                <label class="flex items-center space-x-2 p-1 hover:bg-white dark:hover:bg-gray-700 rounded cursor-pointer transition">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                        :checked="currentRole.permissions.includes('{{ $perm->name }}')"
                                        class="rounded border-gray-300 text-primary focus:ring-primary h-3.5 w-3.5">
                                    <span class="text-[10px] text-gray-600 dark:text-gray-300 lowercase">{{ $perm->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700 flex justify-end space-x-2">
                        <button type="button" @click="openModal = false" class="px-3 py-1.5 rounded text-[10px] font-bold text-gray-500 hover:bg-gray-200 uppercase transition">batal</button>
                        <button type="submit" class="px-4 py-1.5 rounded bg-primary text-white text-[10px] font-bold shadow-sm uppercase tracking-wider transition">simpan role</button>
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
        $('#roleTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: { search: "", searchPlaceholder: "cari role..." },
            dom: '<"flex justify-between items-center gap-4 mb-3"lf>rt<"flex justify-between items-center mt-3 text-[10px]"ip>'
        });

        // Toastr Ramping Pojok Kanan Bawah
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
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                customClass: {
                    popup: 'flex items-center rounded-md border dark:border-gray-700 shadow-md bg-white dark:bg-gray-800 my-2 mx-4',
                    icon: 'm-0 mr-2 scale-75',
                    title: 'text-[10px] font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap p-0 m-0'
                }
            });
        @endif
    });

    function confirmDeleteRole(id) {
        Swal.fire({
            title: 'HAPUS ROLE?',
            text: "akses user terkait akan terpengaruh.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#f44236',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            width: '280px',
            customClass: {
                popup: 'rounded-lg border dark:border-gray-700 shadow-2xl',
                title: 'text-sm font-bold pt-4',
                htmlContainer: 'text-[10px] pt-1 pb-2',
                confirmButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold',
                cancelButton: 'text-[10px] px-3 py-1.5 uppercase tracking-wider font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-role-' + id).submit();
            }
        });
    }
</script>
@endpush