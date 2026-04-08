@extends('layouts.admin')

@section('title', 'Kelola Siswa')

@section('page-title', 'Kelola Siswa')
@section('page-description', 'Daftar semua siswa yang terdaftar dalam sistem. Kelola data, kelas, dan informasi akademik siswa.')

@section('content')
    <div class="space-y-6">

        {{-- ================================================================
        CENTRAL CARD — Scholastic Editorial Design
        ================================================================ --}}
        <div class="bg-white rounded-xl shadow-[0_12px_40px_rgba(0,35,111,0.08)] overflow-hidden">

            {{-- Inner Header: Search (kiri) | Import Excel + Tambah Siswa (kanan) — Responsif --}}
            <div class="p-4 md:p-6 flex flex-col md:flex-row md:flex-wrap justify-between items-stretch md:items-center gap-3">

                {{-- Search Bar --}}
                <form action="{{ route('students.index') }}" method="GET" class="flex items-center gap-2 w-full md:flex-1 md:min-w-0">
                    <div class="relative w-full md:max-w-sm">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" id="searchSiswaInput" value="{{ request('search') }}"
                            placeholder="Cari NIS atau nama siswa..."
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-900/10 transition-all placeholder:text-slate-400 outline-none" />
                    </div>
                    {{-- Hidden submit agar Enter bisa submit --}}
                    <button type="submit" class="hidden"></button>
                    @if(request('search'))
                        <a href="{{ route('students.index') }}"
                            class="shrink-0 px-4 py-3 border border-slate-200 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </form>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 w-full md:w-auto md:shrink-0">

                    {{-- Import Excel (outline / sekunder) --}}
                    <button type="button" id="btnImportExcel" onclick="openImportModal()"
                        class="inline-flex items-center justify-center gap-2 border border-slate-300 text-slate-700 hover:bg-slate-50 active:scale-95 px-5 py-3 rounded-xl font-semibold text-sm transition-all whitespace-nowrap flex-1 md:flex-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V4m-4 5l4-4 4 4" />
                        </svg>
                        Import Excel
                    </button>

                    {{-- Tambah Siswa (primary) --}}
                    <a href="{{ route('students.create') }}" id="btnTambahSiswa"
                        class="inline-flex items-center justify-center gap-2 bg-[#00236f] hover:opacity-90 active:scale-95 text-white px-5 py-3 rounded-xl font-semibold text-sm transition-all shadow-sm whitespace-nowrap flex-1 md:flex-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Siswa
                    </a>

                </div>
            </div>

            {{-- ============================================================
                 TABLE VIEW — Desktop Only (hidden on mobile)
            ============================================================ --}}
            <div class="hidden md:block w-full overflow-x-auto px-6 pb-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[11px] uppercase tracking-[0.15em] font-bold">
                            <th class="pb-5 w-4 pr-3"></th>{{-- gender indicator spacer --}}
                            <th class="pb-5 w-12">No</th>
                            <th class="pb-5">NIS</th>
                            <th class="pb-5">Nama Siswa</th>
                            <th class="pb-5">Kelas</th>
                            <th class="pb-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $index => $student)
                            @php
                                $borderColor = match ($student->jenis_kelamin) {
                                    'L' => 'bg-blue-500',
                                    'P' => 'bg-pink-400',
                                    default => 'bg-slate-200',
                                };
                                $classroomName = $student->classroom->name ?? null;
                            @endphp
                            <tr class="group hover:bg-slate-50 transition-colors">

                                {{-- Gender Indicator Bar (kiri) --}}
                                <td class="py-5 pr-3 w-1">
                                    <div class="w-1 h-8 rounded-full {{ $borderColor }}"></div>
                                </td>

                                {{-- NO --}}
                                <td class="py-5 text-sm font-medium text-slate-400">
                                    {{ $students->firstItem() + $index }}
                                </td>

                                {{-- NIS --}}
                                <td class="py-5 text-sm font-semibold text-slate-600 whitespace-nowrap">
                                    {{ $student->nis }}
                                </td>

                                {{-- NAMA — teks saja, tanpa avatar/inisial --}}
                                <td class="py-5 text-[15px] font-semibold text-[#00236f] whitespace-nowrap">
                                    {{ $student->name }}
                                </td>

                                {{-- KELAS — badge minimalis, atau strip "-" --}}
                                <td class="py-5">
                                    @if($classroomName)
                                        <span
                                            class="px-3 py-1 bg-[#E0E7FF] text-[#4338CA] text-[11px] font-bold rounded-full uppercase tracking-tight whitespace-nowrap">
                                            {{ $classroomName }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-medium">—</span>
                                    @endif
                                </td>

                                {{-- AKSI — ikon SVG murni: Pensil (Edit) + Sampah (Hapus) --}}
                                <td class="py-5">
                                    <div class="flex justify-end gap-2">

                                        {{-- Edit: Ikon Pensil --}}
                                        <a href="{{ route('students.edit', $student->id) }}" title="Edit"
                                            class="p-2 text-slate-400 hover:text-[#00236f] hover:bg-slate-100 rounded-lg transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Hapus: form Laravel (CSRF + DELETE) + Ikon Sampah --}}
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <p class="text-slate-500 font-medium">Belum ada data siswa</p>
                                        <p class="text-slate-400 text-sm mt-1">Klik tombol "+ Tambah Siswa" untuk menambah data
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ============================================================
                 CARD VIEW — Mobile Only (hidden on desktop)
            ============================================================ --}}
            <div class="grid grid-cols-1 gap-4 px-4 pb-6 md:hidden">
                @forelse($students as $index => $student)
                    @php
                        $cardBorderColor = match ($student->jenis_kelamin) {
                            'L' => 'border-l-blue-500',
                            'P' => 'border-l-pink-400',
                            default => 'border-l-slate-200',
                        };
                        $classroomName = $student->classroom->name ?? null;
                    @endphp
                    <div class="bg-white border border-gray-200 border-l-4 {{ $cardBorderColor }} rounded-xl shadow-sm p-4 flex flex-col gap-2 relative overflow-hidden">

                        {{-- Body: NIS + Nama --}}
                        <div>
                            <p class="text-xs text-slate-400 font-medium">{{ $student->nis }}</p>
                            <p class="text-[15px] font-bold text-[#00236f] leading-snug mt-0.5">{{ $student->name }}</p>
                        </div>

                        {{-- Badge Kelas --}}
                        <div>
                            @if($classroomName)
                                <span class="px-3 py-1 bg-[#E0E7FF] text-[#4338CA] text-[11px] font-bold rounded-full uppercase tracking-tight">
                                    {{ $classroomName }}
                                </span>
                            @else
                                <span class="text-slate-400 font-medium text-sm">—</span>
                            @endif
                        </div>

                        {{-- Footer: Aksi (Edit + Hapus) --}}
                        <div class="border-t border-slate-100 pt-3 flex justify-end gap-3">

                            {{-- Edit --}}
                            <a href="{{ route('students.edit', $student->id) }}" title="Edit"
                                class="p-2 text-slate-400 hover:text-[#00236f] hover:bg-slate-100 rounded-lg transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                class="inline-block" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus"
                                    class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center col-span-full">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-14 h-14 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-slate-500 font-medium">Belum ada data siswa</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($students->hasPages())
                <div
                    class="px-6 pb-6 pt-3 border-t border-slate-100 flex flex-wrap justify-between items-center gap-3 text-sm text-slate-500">
                    <div>Menampilkan {{ $students->firstItem() }}–{{ $students->lastItem() }} dari {{ $students->total() }}
                        siswa</div>
                    <div>
                        {{ $students->links() }}
                    </div>
                </div>
            @endif

        </div>{{-- /.Central Card --}}

    </div>

    {{-- ================================================================
    MODAL IMPORT EXCEL
    ================================================================ --}}
    <div id="importModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm"
        onclick="closeImportModal(event)">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl" onclick="event.stopPropagation()">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-[#00236f]">Import Data Siswa</h3>
                <button type="button" onclick="closeImportModal()"
                    class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5 px-6 py-5">
                @csrf

                <div>
                    <label for="import_file" class="mb-2 block text-sm font-semibold text-slate-700">Pilih File</label>
                    <input id="import_file" type="file" name="file" accept=".xlsx,.xls,.csv" class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700
                               file:mr-3 file:rounded-lg file:border-0 file:bg-[#E0E7FF] file:px-3 file:py-2
                               file:text-sm file:font-semibold file:text-[#4338CA] hover:file:bg-[#c7d2fe]
                               cursor-pointer" required>
                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2 pt-1">
                    <a href="{{ route('students.template') }}"
                        class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Download Template
                    </a>
                    <button type="button" onclick="closeImportModal()"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-[#00236f] px-4 py-2 text-sm font-semibold text-white hover:opacity-90 transition-all active:scale-95">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openImportModal() {
                const modal = document.getElementById('importModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeImportModal(event = null) {
                if (event && event.target !== event.currentTarget) return;
                const modal = document.getElementById('importModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeImportModal();
            });

            @if($errors->has('file'))
                openImportModal();
            @endif

            // Konfirmasi Delete dengan SweetAlert2
            function confirmDelete(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data siswa ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
                        title: 'text-2xl font-bold text-gray-800',
                        htmlContainer: 'text-base text-gray-500 mt-2',
                        confirmButton: 'mt-4 bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
                        cancelButton: 'mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95 ml-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                });

                return false;
            }

            // Success Alert ketika ada session success
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6',
                    timer: 3000,
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