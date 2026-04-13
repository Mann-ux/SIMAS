@extends('layouts.admin')

@section('title', 'Kelola Guru')

@section('page-title', 'Kelola Guru')
@section('page-description', 'Management portal for academic faculty. Assign roles, update credentials, and oversee teacher records within the scholastic directory.')

@section('content')
    <div class="space-y-6">

        {{-- ================================================================
        CENTRAL CARD — Scholastic Editorial Design
        ================================================================ --}}
        <div class="bg-white rounded-xl shadow-[0_12px_40px_rgba(0,35,111,0.08)] overflow-hidden">

            {{-- Inner Header: Search Bar (kiri) + Tombol Tambah (kanan) — Responsif --}}
            <div class="p-4 md:p-6 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">

                {{-- Search Bar --}}
                <div class="relative w-full md:w-80">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                    </span>
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama guru..."
                        class="w-full pl-12 pr-12 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-900/10 transition-all placeholder:text-slate-400 outline-none" />
                    <span id="searchLoadingSpinner" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-slate-500">
                        <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" class="opacity-25" stroke="currentColor" stroke-width="3"></circle>
                            <path class="opacity-90" fill="currentColor"
                                d="M12 2a10 10 0 0 1 10 10h-3a7 7 0 0 0-7-7V2z"></path>
                        </svg>
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    {{-- Import Excel Button --}}
                    <button type="button" id="btnImportUser"
                        class="inline-flex items-center justify-center gap-2 border border-slate-300 text-slate-700 hover:bg-slate-50 active:scale-95 px-5 py-3 rounded-xl font-semibold text-sm transition-all whitespace-nowrap flex-1 md:flex-none"
                        onclick="openImportModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V4m-4 5l4-4 4 4" />
                        </svg>
                        Import Excel
                    </button>

                    {{-- Tambah User Button --}}
                    <a href="{{ route('users.create') }}" id="btnTambahUser"
                        class="inline-flex items-center justify-center gap-2 bg-[#006c49] hover:opacity-90 active:scale-95 text-white w-full md:w-auto px-6 py-3 rounded-xl font-semibold text-sm transition-all shadow-sm whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah User
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
                    @include('admin.users.partials.table', ['users' => $users])
                </div>
            </div>

        </div>{{-- /.Central Card --}}

        {{-- Modal Import Excel --}}
        <div id="importExcelModal"
            class="fixed inset-0 bg-slate-900/50 z-50 hidden items-center justify-center px-4 py-6"
            onclick="closeImportModal(event)">
            <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()"
                role="dialog" aria-modal="true" aria-labelledby="importExcelTitle">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-3">
                    <h3 id="importExcelTitle" class="text-lg md:text-xl font-bold text-[#00236f]">Import Data User dari Excel</h3>
                    <button type="button" onclick="closeImportModal()"
                        class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all"
                        aria-label="Tutup modal import excel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ \Illuminate\Support\Facades\Route::has('users.import') ? route('users.import') : '#' }}"
                    method="POST" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
                    @csrf

                    <div>
                        <label for="importUserFile" class="block text-sm font-semibold text-slate-700 mb-2">File Excel/CSV</label>
                        <input type="file" id="importUserFile" name="file" accept=".xlsx,.xls,.csv" required
                            class="block w-full text-sm text-slate-600 border border-slate-200 rounded-xl file:mr-3 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200"
                            autofocus>
                        <p class="text-xs text-slate-400 mt-2">Format file yang didukung: .xlsx, .xls, .csv</p>
                        @error('file')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ \Illuminate\Support\Facades\Route::has('users.template.download') ? route('users.template.download') : '#' }}"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-700" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V4m0 8l-4-4m4 4l4-4" />
                            </svg>
                            Download Template Excel
                        </a>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-2">
                        <p class="text-sm text-slate-700">
                            Pastikan format baris pertama (header) di Excel adalah:
                            <span class="font-semibold">nip, nama_lengkap, email, password.</span>
                        </p>
                        <p class="text-xs md:text-sm text-blue-700">
                            *Kosongkan kolom password jika ingin menggunakan password otomatis (Nama Depan + Ekor NIP/123).
                        </p>
                    </div>

                    <div class="pt-2 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                        <button type="button" onclick="closeImportModal()"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-blue-900 text-white font-semibold text-sm hover:bg-blue-800 active:scale-95 transition-all">
                            Upload Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function openImportModal() {
                const modal = document.getElementById('importExcelModal');
                if (!modal) return;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeImportModal(event = null) {
                const modal = document.getElementById('importExcelModal');
                if (!modal) return;

                if (event && event.target !== modal) {
                    return;
                }

                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeImportModal();
                }
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
                        console.error('Live search users gagal:', error);
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
                    text: 'Apakah Anda yakin ingin menghapus data user ini?',
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