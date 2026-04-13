@extends('layouts.admin')

@section('title', 'Kelola Kelas')

@section('page-title', 'Kelola Kelas')
@section('page-description', 'Manajemen data kelas dan wali kelas')

@section('content')
<div class="space-y-10">

    <!-- Page Header Section -->
    <section class="max-w-6xl">
        <h2 class="text-4xl font-manrope font-extrabold text-on-surface tracking-tight">Kelola Kelas</h2>
        <p class="mt-2 text-on-surface-variant font-body text-lg max-w-2xl">
            Kelola struktur akademik sekolah Anda dengan presisi. Tambah, edit, atau hapus data kelas.
        </p>
    </section>

    <!-- Metrics Section -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-12 h-12 bg-[#00236f]/5 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#00236f]">school</span>
            </div>
            <div>
                <p class="text-[10px] font-manrope font-bold text-gray-500 uppercase tracking-widest">Total Kelas</p>
                <p class="text-2xl font-manrope font-extrabold text-[#00236f]">{{ $classrooms->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-12 h-12 bg-[#00236f]/5 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#00236f]">group</span>
            </div>
            <div>
                <p class="text-[10px] font-manrope font-bold text-gray-500 uppercase tracking-widest">Total Siswa</p>
                <p class="text-2xl font-manrope font-extrabold text-[#00236f]">{{ $classrooms->sum('students_count') }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="w-12 h-12 bg-[#00236f]/5 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[#00236f]">monitoring</span>
            </div>
            <div>
                <p class="text-[10px] font-manrope font-bold text-gray-500 uppercase tracking-widest">Rata-Rata Siswa/Kelas</p>
                <p class="text-2xl font-manrope font-extrabold text-[#00236f]">
                    {{ $classrooms->count() > 0 ? round($classrooms->sum('students_count') / $classrooms->count()) : 0 }}
                </p>
            </div>
        </div>
    </section>

    <!-- Filter & Action Bar -->
    <div x-data="{ filter: 'semua', search: '' }" class="space-y-6">

        <!-- Filter by Tingkat -->
        <section class="max-w-6xl bg-white p-6 rounded-2xl shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 border border-gray-100">
            <div class="flex flex-col">
                <h3 class="text-lg font-manrope font-bold text-gray-800">Filter by Tingkat</h3>
                <p class="text-sm text-gray-500">Pilih tingkat kelas untuk ditampilkan</p>
            </div>
            <div class="flex items-center p-1 bg-gray-100 rounded-xl">
                <button @click="filter = 'semua'" :class="filter === 'semua' ? 'bg-white text-[#00236f] shadow-sm' : 'text-gray-500 hover:text-gray-800'" class="px-6 py-2 text-sm font-manrope font-bold rounded-lg transition-all">Semua</button>
                <button @click="filter = 'X'"     :class="filter === 'X'     ? 'bg-white text-[#00236f] shadow-sm' : 'text-gray-500 hover:text-gray-800'" class="px-6 py-2 text-sm font-manrope font-bold rounded-lg transition-all">X</button>
                <button @click="filter = 'XI'"    :class="filter === 'XI'    ? 'bg-white text-[#00236f] shadow-sm' : 'text-gray-500 hover:text-gray-800'" class="px-6 py-2 text-sm font-manrope font-bold rounded-lg transition-all">XI</button>
                <button @click="filter = 'XII'"   :class="filter === 'XII'   ? 'bg-white text-[#00236f] shadow-sm' : 'text-gray-500 hover:text-gray-800'" class="px-6 py-2 text-sm font-manrope font-bold rounded-lg transition-all">XII</button>
            </div>
        </section>

        <!-- Search & Add Button -->
        <section class="flex flex-col md:flex-row gap-4 items-center justify-between max-w-6xl">
            <div class="relative w-full md:w-96">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                <input
                    x-model="search"
                    class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 focus:border-[#00236f] focus:ring-0 rounded-xl text-sm transition-all placeholder:text-gray-400 shadow-sm outline-none"
                    placeholder="Cari nama kelas..."
                    type="text"
                />
            </div>
            <a href="{{ route('classrooms.create') }}" class="w-full md:w-auto px-6 py-3 bg-[#00236f] text-white font-manrope font-bold rounded-xl flex items-center justify-center gap-2 hover:opacity-90 transition-all shadow-sm">
                <span class="material-symbols-outlined text-xl">add</span>
                Tambah Kelas
            </a>
        </section>

        <!-- Editorial Table -->
        <section class="max-w-6xl bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
            @if($classrooms->count() > 0)
            <div class="w-full overflow-x-auto hidden md:block">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50 whitespace-nowrap">
                        <th class="px-8 py-5 text-[11px] font-manrope font-bold uppercase tracking-widest text-gray-500">Nama Kelas</th>
                        <th class="px-8 py-5 text-[11px] font-manrope font-bold uppercase tracking-widest text-gray-500">Wali Kelas</th>
                        <th class="px-8 py-5 text-[11px] font-manrope font-bold uppercase tracking-widest text-gray-500">Jumlah Siswa</th>
                        <th class="px-8 py-5 text-[11px] font-manrope font-bold uppercase tracking-widest text-gray-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($classrooms as $classroom)
                    @php
                        $initial  = strtoupper(substr($classroom->waliKelas->name ?? '?', 0, 1));
                        $isFemale = in_array($initial, ['A','E','I','O','U','R','S','N','D','F','L','M','P','T','W','Y']);
                        $avatarBg = $isFemale ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700';
                    @endphp
                    <tr
                        x-show="(filter === 'semua' || filter === '{{ $classroom->tingkat }}') && (search === '' || '{{ strtolower($classroom->name) }}'.includes(search.toLowerCase()))"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="group hover:bg-blue-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-base font-manrope font-bold text-gray-900">{{ $classroom->name }}</span>
                                <span class="text-xs text-gray-400">Level {{ $classroom->tingkat }} Reg</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full {{ $avatarBg }} flex items-center justify-center font-manrope font-bold text-xs">{{ $initial }}</span>
                                <span class="text-sm font-medium text-gray-800">{{ $classroom->waliKelas->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-semibold text-gray-600">{{ $classroom->students_count }} Siswa</span>
                        </td>
                        <td class="px-8 py-6 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('classrooms.show', $classroom->id) }}"
                                   class="px-4 py-2 text-xs font-manrope font-bold text-[#00236f] hover:bg-[#00236f]/5 rounded-lg transition-colors">
                                    Detail
                                </a>
                                <button type="button"
                                        onclick="confirmArchive({{ $classroom->id }}, '{{ addslashes($classroom->name) }}')"
                                        class="px-4 py-2 text-xs font-manrope font-bold text-amber-600 hover:bg-amber-50 rounded-lg transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px]">inventory_2</span>
                                    Arsip
                                </button>
                                <form id="delete-form-{{ $classroom->id }}" action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>{{-- end overflow-x-auto --}}

            {{-- ══════════════════════════════════════
                 MOBILE CARD VIEW (hidden on md+)
                 ══════════════════════════════════════ --}}
            <div class="grid grid-cols-1 gap-3 md:hidden px-4 py-4">
                @foreach($classrooms as $classroom)
                @php
                    $initial  = strtoupper(substr($classroom->waliKelas->name ?? '?', 0, 1));
                    $isFemale = in_array($initial, ['A','E','I','O','U','R','S','N','D','F','L','M','P','T','W','Y']);
                    $avatarBg = $isFemale ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700';
                @endphp
                <div
                    x-show="(filter === 'semua' || filter === '{{ $classroom->tingkat }}') && (search === '' || '{{ strtolower($classroom->name) }}'.includes(search.toLowerCase()))"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col gap-3"
                >
                    {{-- Card Header: Nama Kelas --}}
                    <div class="border-b border-gray-100 pb-2">
                        <p class="text-base font-manrope font-bold text-gray-900">{{ $classroom->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Level {{ $classroom->tingkat }} Reg</p>
                    </div>

                    {{-- Card Body: Info Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        {{-- Wali Kelas --}}
                        <div class="flex flex-col gap-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Wali Kelas</p>
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full {{ $avatarBg }} flex items-center justify-center font-manrope font-bold text-xs flex-shrink-0">{{ $initial }}</span>
                                <span class="text-xs font-medium text-gray-700 leading-tight">{{ $classroom->waliKelas->name ?? '-' }}</span>
                            </div>
                        </div>
                        {{-- Jumlah Siswa --}}
                        <div class="flex flex-col gap-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jumlah Siswa</p>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-semibold text-gray-600 w-fit">{{ $classroom->students_count }} Siswa</span>
                        </div>
                    </div>

                    {{-- Card Footer: Action Buttons --}}
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-1">
                        <a
                            href="{{ route('classrooms.show', $classroom->id) }}"
                            class="flex-1 text-center px-4 py-2 text-xs font-manrope font-bold text-[#00236f] bg-[#00236f]/5 hover:bg-[#00236f]/10 rounded-lg transition-colors mr-2"
                        >
                            Detail
                        </a>
                        <button
                            type="button"
                            onclick="confirmArchive({{ $classroom->id }}, '{{ addslashes($classroom->name) }}')"
                            class="flex-1 text-center px-4 py-2 text-xs font-manrope font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors flex items-center justify-center gap-1"
                        >
                            <span class="material-symbols-outlined text-[15px]">inventory_2</span>
                            Arsip
                        </button>
                        <form id="delete-form-{{ $classroom->id }}" action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                @endforeach
            </div>{{-- end mobile card view --}}

            @else
            <div class="p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-4xl text-gray-400">school</span>
                    </div>
                    <h3 class="text-lg font-manrope font-bold text-gray-700 mb-2">Belum Ada Data Kelas</h3>
                    <p class="text-gray-400 mb-6 font-body">Tidak ada data kelas yang terdaftar. Mulai dengan menambahkan kelas baru.</p>
                    <a href="{{ route('classrooms.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#00236f] text-white font-manrope font-bold rounded-xl hover:opacity-90 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-xl">add</span>
                        Tambah Kelas Pertama
                    </a>
                </div>
            </div>
            @endif
        </section>

    </div>{{-- end x-data --}}

</div>

@push('scripts')
<script>
    function confirmArchive(classroomId, classroomName) {
        Swal.fire({
            title: 'Arsipkan Kelas?',
            text: 'Kelas "' + classroomName + '" akan dipindahkan ke arsip dan tidak tampil di daftar aktif.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Arsipkan',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
                title: 'text-2xl font-bold text-gray-800',
                htmlContainer: 'text-base text-gray-500 mt-2',
                confirmButton: 'mt-4 bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
                cancelButton: 'mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95 ml-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(`delete-form-${classroomId}`);
                if (form) {
                    form.submit();
                }
            }
        });
    }

    // Success Notification dengan SweetAlert2
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000,
        buttonsStyling: false,
        customClass: {
            popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
            title: 'text-2xl font-bold text-gray-800',
            htmlContainer: 'text-base text-gray-500 mt-2',
            confirmButton: 'mt-4 bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
            cancelButton: 'mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95 ml-2'
        }
    });
    @endif
</script>
@endpush
@endsection
