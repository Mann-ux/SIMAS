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
                <div class="flex items-center gap-2 w-full md:flex-1 md:min-w-0">
                    <div class="relative w-full md:max-w-sm">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Cari NIS atau nama siswa..."
                            class="w-full pl-12 pr-12 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-900/10 transition-all placeholder:text-slate-400 outline-none" />
                        <span id="searchLoadingSpinner" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" class="opacity-25" stroke="currentColor" stroke-width="3"></circle>
                                <path class="opacity-90" fill="currentColor"
                                    d="M12 2a10 10 0 0 1 10 10h-3a7 7 0 0 0-7-7V2z"></path>
                            </svg>
                        </span>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('students.index') }}"
                            class="shrink-0 px-4 py-3 border border-slate-200 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </div>

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

            <div id="tableContainer" class="relative">
                <div id="tableLoadingOverlay"
                    class="hidden absolute inset-0 z-20 bg-white/60 backdrop-blur-[1px] items-center justify-center rounded-xl">
                    <div class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                        <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" class="opacity-25" stroke="currentColor" stroke-width="3"></circle>
                            <path class="opacity-90" fill="currentColor"
                                d="M12 2a10 10 0 0 1 10 10h-3a7 7 0 0 0-7-7V2z"></path>
                        </svg>
                        Memuat data...
                    </div>
                </div>

                <div id="tableContent">
                    @include('admin.students.partials.table', ['students' => $students])
                </div>
            </div>

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

            (function() {
                const searchInput = document.getElementById('searchInput');
                const tableContainer = document.getElementById('tableContainer');
                const tableContent = document.getElementById('tableContent');
                const tableLoadingOverlay = document.getElementById('tableLoadingOverlay');
                const searchLoadingSpinner = document.getElementById('searchLoadingSpinner');

                if (!searchInput || !tableContainer || !tableContent || !tableLoadingOverlay || !searchLoadingSpinner) {
                    return;
                }

                let loadingCounter = 0;
                let loadingDelayTimer = null;

                const setLoading = (isLoading) => {
                    if (isLoading) {
                        loadingCounter++;
                        if (loadingCounter > 1) {
                            return;
                        }

                        searchLoadingSpinner.classList.remove('hidden');
                        loadingDelayTimer = setTimeout(() => {
                            tableLoadingOverlay.classList.remove('hidden');
                            tableLoadingOverlay.classList.add('flex');
                        }, 150);

                        return;
                    }

                    loadingCounter = Math.max(0, loadingCounter - 1);
                    if (loadingCounter > 0) {
                        return;
                    }

                    clearTimeout(loadingDelayTimer);
                    loadingDelayTimer = null;

                    searchLoadingSpinner.classList.add('hidden');
                    tableLoadingOverlay.classList.add('hidden');
                    tableLoadingOverlay.classList.remove('flex');
                };

                const debounce = (callback, delay = 500) => {
                    let timeoutId;

                    return (...args) => {
                        clearTimeout(timeoutId);
                        timeoutId = setTimeout(() => callback(...args), delay);
                    };
                };

                const buildUrl = (page = 1) => {
                    const url = new URL(window.location.href);
                    const keyword = searchInput.value.trim();

                    if (keyword) {
                        url.searchParams.set('search', keyword);
                    } else {
                        url.searchParams.delete('search');
                    }

                    if (Number(page) > 1) {
                        url.searchParams.set('page', page);
                    } else {
                        url.searchParams.delete('page');
                    }

                    return url;
                };

                const fetchTable = async (url) => {
                    setLoading(true);

                    try {
                        const response = await fetch(url.toString(), {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html',
                            },
                        });

                        if (!response.ok) {
                            throw new Error(`Request failed with status ${response.status}`);
                        }

                        const html = await response.text();
                        tableContent.innerHTML = html;
                        window.history.replaceState({}, '', url.toString());
                    } catch (error) {
                        console.error('Live search students gagal:', error);
                    } finally {
                        setLoading(false);
                    }
                };

                const handleSearch = debounce(() => {
                    fetchTable(buildUrl(1));
                }, 500);

                searchInput.addEventListener('input', handleSearch);
                searchInput.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });

                tableContainer.addEventListener('click', function(event) {
                    const link = event.target.closest('a');

                    if (!link) {
                        return;
                    }

                    const url = new URL(link.href, window.location.origin);
                    const page = url.searchParams.get('page');

                    if (!page) {
                        return;
                    }

                    event.preventDefault();
                    fetchTable(buildUrl(page));
                });
            })();

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