@extends('layouts.admin')

@section('title', 'Mutasi / Kenaikan Kelas')
@section('page-title', 'Mutasi / Kenaikan Kelas')
@section('page-description', 'Pindahkan siswa antar kelas secara massal')

@section('content')
<div class="space-y-8">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-5">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-1">
                Akademik &rsaquo; Mutasi Kelas
            </p>
            <h1 class="text-4xl font-extrabold text-[#00236f] tracking-tight leading-tight">Mutasi / Kenaikan Kelas</h1>
            <p class="text-gray-500 mt-2 text-sm max-w-2xl">
                Pilih kelas asal, centang siswa yang akan dipindah, lalu tentukan kelas tujuan.
            </p>
        </div>

        {{-- Action Buttons: Export & Import --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-shrink-0 w-full md:w-auto mt-4 md:mt-0">
            {{-- Download Format Excel --}}
            <a
                href="{{ route('admin.mutasi.export') }}"
                class="flex justify-center items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-sm hover:shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all whitespace-nowrap"
            >
                <span class="material-symbols-outlined text-[18px]">download</span>
                Download Format Excel
            </a>

            {{-- Import Rolling Kelas --}}
            <button
                type="button"
                id="btn-open-import"
                onclick="document.getElementById('modal-import').classList.remove('hidden')"
                class="flex justify-center items-center gap-2 px-5 py-2.5 bg-white border-2 border-[#00236f] text-[#00236f] hover:bg-[#00236f] hover:text-white rounded-xl text-sm font-bold shadow-sm hover:shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all whitespace-nowrap"
            >
                <span class="material-symbols-outlined text-[18px]">upload_file</span>
                Import Rolling Kelas
            </button>
        </div>
    </div>

    {{-- ── Step 1: Pilih Kelas Asal (GET filter) ───────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,35,111,0.06)] overflow-hidden">
        <div class="px-5 md:px-8 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[#00236f]" style="font-variation-settings:'FILL' 1">filter_list</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#00236f]">Langkah 1 — Pilih Kelas Asal</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Pilih kelas yang siswanya ingin dipindahkan.</p>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.mutasi.index') }}" class="px-5 md:px-8 py-6">
            <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-end">
                <div class="flex-1 w-full space-y-1.5">
                    <label for="kelas_asal" class="block text-xs font-bold text-gray-500 uppercase tracking-widest">
                        Kelas Asal <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            id="kelas_asal"
                            name="kelas_asal"
                            class="w-full h-13 bg-slate-50 border-0 rounded-xl px-4 pr-10 py-3.5 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer"
                        >
                            <option value="" disabled {{ !request('kelas_asal') ? 'selected' : '' }}>-- Pilih Kelas --</option>
                            @foreach($classrooms as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_asal') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <span class="material-symbols-outlined text-lg">expand_more</span>
                        </div>
                    </div>
                </div>
                <button
                    type="submit"
                    class="w-full md:w-auto flex justify-center items-center gap-2 px-7 py-3.5 bg-[#00236f] hover:bg-[#001a55] text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all whitespace-nowrap"
                >
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>

    {{-- ── Step 2: Tabel Siswa + Form Pindah (hanya muncul jika kelas_asal dipilih) --}}
    @if(request('kelas_asal'))
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,35,111,0.06)] overflow-hidden">

        {{-- Card Header --}}
        <div class="px-5 md:px-8 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1">swap_horiz</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-[#00236f]">Langkah 2 — Pilih Siswa &amp; Kelas Tujuan</h2>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Kelas asal: <span class="font-semibold text-gray-600">{{ $kelasAsal?->name ?? '-' }}</span>
                        &bull; <span class="font-semibold text-gray-600">{{ $siswaList->count() }}</span> siswa ditemukan
                    </p>
                </div>
            </div>
        </div>

        @if($siswaList->isEmpty())
            {{-- Empty State --}}
            <div class="py-16 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-gray-400">person_off</span>
                </div>
                <h3 class="text-base font-bold text-gray-600 mb-1">Kelas Ini Belum Memiliki Siswa</h3>
                <p class="text-sm text-gray-400">Tambahkan siswa ke kelas ini terlebih dahulu.</p>
            </div>
        @else
        {{-- ── POST Form: Kelas Tujuan + Tabel Siswa ── --}}
        <form method="POST" action="{{ route('admin.mutasi.store') }}" id="form-mutasi">
            @csrf

            {{-- Kelas Tujuan --}}
            <div class="px-5 md:px-8 pt-6 pb-4">
                @error('kelas_tujuan')
                    <p class="text-xs text-red-500 mb-2">{{ $message }}</p>
                @enderror
                @error('nis_array')
                    <p class="text-xs text-red-500 mb-2">{{ $message }}</p>
                @enderror

                <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-end p-4 md:p-5 bg-emerald-50/60 rounded-xl border border-emerald-100">
                    <div class="flex-1 w-full space-y-1.5">
                        <label for="kelas_tujuan" class="block text-xs font-bold text-emerald-700 uppercase tracking-widest">
                            Kelas Tujuan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="kelas_tujuan"
                                name="kelas_tujuan"
                                class="w-full bg-white border border-emerald-200 rounded-xl px-4 pr-10 py-3 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-emerald-400/30 outline-none appearance-none cursor-pointer"
                                required
                            >
                                <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                                @foreach($classrooms as $kelas)
                                    @if($kelas->id != request('kelas_asal'))
                                        <option value="{{ $kelas->id }}">{{ $kelas->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <span class="material-symbols-outlined text-lg">expand_more</span>
                            </div>
                        </div>
                    </div>
                    <button
                        type="submit"
                        id="btn-pindahkan"
                        class="w-full md:w-auto flex justify-center items-center gap-2 px-7 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all whitespace-nowrap"
                    >
                        <span class="material-symbols-outlined text-[18px]">move_down</span>
                        Pindahkan Siswa
                    </button>
                </div>
            </div>

            {{-- Search bar + counter row --}}
            <div class="px-5 md:px-8 pb-4 flex flex-col md:flex-row md:items-center gap-3 justify-between">
                <div class="relative w-full md:flex-1 md:max-w-md">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none">search</span>
                    <input
                        type="text"
                        id="searchMutasi"
                        placeholder="Cari nama atau NIS siswa..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-gray-200 focus:border-[#00236f] focus:ring-2 focus:ring-[#00236f]/10 rounded-xl text-sm outline-none transition-all placeholder:text-gray-400"
                        autocomplete="off"
                    />
                </div>
                <p class="text-xs text-gray-400 text-right whitespace-nowrap">
                    <span id="selected-count" class="font-bold text-[#00236f]">0</span> siswa dipilih
                </p>
            </div>

            {{-- Tabel Siswa --}}
            <div class="px-5 md:px-8 pb-5 md:pb-8">
                <div id="tableMutasiContainer" class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-5 py-4 text-center w-12 rounded-tl-xl">
                                    <input
                                        type="checkbox"
                                        id="select-all"
                                        title="Pilih semua siswa"
                                        class="w-4 h-4 rounded accent-[#00236f] cursor-pointer"
                                    />
                                </th>
                                <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">No</th>
                                <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">NIS</th>
                                <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">Nama Siswa</th>
                                <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 rounded-tr-xl">Jenis Kelamin</th>
                            </tr>
                        </thead>
                        <tbody id="mutasiTbody" class="divide-y divide-gray-100">
                            @include('admin.mutasi.partials.siswa-table', ['siswaList' => $siswaList])
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
        @endif
    </div>
    @endif

    {{-- ── Modal Import Rolling Kelas ────────────────────────────────── --}}
    <div
        id="modal-import"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-import-title"
    >
        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-black/40 backdrop-blur-sm"
            onclick="document.getElementById('modal-import').classList.add('hidden')"
        ></div>

        {{-- Modal Panel --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

            {{-- Modal Header --}}
            <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#00236f]" style="font-variation-settings:'FILL' 1">upload_file</span>
                    </div>
                    <div>
                        <h2 id="modal-import-title" class="text-base font-bold text-[#00236f]">Import Rolling Kelas</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Upload file Excel untuk memperbarui kelas siswa secara massal.</p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="document.getElementById('modal-import').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-700 transition-colors"
                    aria-label="Tutup modal"
                >
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <form
                method="POST"
                action="{{ route('admin.mutasi.import') }}"
                enctype="multipart/form-data"
                class="px-7 py-6 space-y-5"
                id="form-import-rolling"
            >
                @csrf

                {{-- Validation Error --}}
                @error('file_excel')
                    <div class="flex items-start gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-xl">
                        <span class="material-symbols-outlined text-red-500 text-[18px] flex-shrink-0 mt-0.5">error</span>
                        <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                    </div>
                @enderror

                {{-- File Drop Zone --}}
                <div class="space-y-1.5">
                    <label for="fileInput" class="block text-xs font-bold text-gray-500 uppercase tracking-widest">
                        File Excel <span class="text-red-500">*</span>
                    </label>
                    <label
                        for="fileInput"
                        id="dropzoneContainer"
                        class="flex flex-col items-center justify-center gap-3 w-full min-h-[140px] border-2 border-dashed border-gray-200 hover:border-[#00236f] bg-slate-50 hover:bg-indigo-50/40 rounded-xl cursor-pointer transition-all group"
                    >
                        {{-- State 1: Kondisi Kosong --}}
                        <div id="defaultState" class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-gray-300 group-hover:text-[#00236f] transition-colors">upload_file</span>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-gray-600 group-hover:text-[#00236f] transition-colors">Klik untuk pilih file</p>
                                <p class="text-xs text-gray-400 mt-0.5">Format: .xlsx atau .xls &bull; Maks. 5 MB</p>
                            </div>
                        </div>

                        {{-- State 2: Kondisi File Terpilih --}}
                        <div id="selectedState" class="hidden flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-[#00236f] mb-1">description</span>
                            <p id="fileNameDisplay" class="text-sm font-semibold text-[#00236f] text-center max-w-[250px] truncate"></p>
                            <button type="button" id="resetFileBtn" class="mt-2 px-3 py-1.5 text-[11px] font-bold text-red-500 hover:text-white bg-red-50 hover:bg-red-500 rounded-lg transition-colors border border-red-100 flex items-center justify-center">
                                Batal / Ganti File
                            </button>
                        </div>

                        <input
                            type="file"
                            id="fileInput"
                            name="file_excel"
                            accept=".xlsx,.xls"
                            class="hidden"
                        />
                    </label>
                </div>

                {{-- Panduan Kolom --}}
                <div class="px-4 py-3 bg-amber-50/60 rounded-xl border-l-4 border-amber-400">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700 mb-1">Panduan Kolom Excel</p>
                    <ul class="text-xs text-amber-700 space-y-0.5">
                        <li><span class="font-mono font-bold">nis</span> — NIS siswa (primary key, wajib tepat)</li>
                        <li><span class="font-mono font-bold">nama</span> — Nama lengkap siswa</li>
                        <li><span class="font-mono font-bold">nama_kelas</span> — Nama kelas tujuan sesuai database</li>
                    </ul>
                    <p class="text-[10px] text-amber-600 mt-2 font-medium">💡 Download format Excel terlebih dahulu agar kolom sudah sesuai.</p>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-3 pt-1">
                    <button
                        type="button"
                        onclick="document.getElementById('modal-import').classList.add('hidden')"
                        class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex items-center gap-2 px-6 py-2.5 bg-[#00236f] hover:bg-[#001a55] text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all"
                    >
                        <span class="material-symbols-outlined text-[17px]">dataset</span>
                        Proses Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Info Cards ────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-3xl">
        <div class="p-5 bg-blue-50/60 rounded-xl border-l-4 border-[#00236f]">
            <div class="flex items-center gap-2 mb-2 text-[#00236f]">
                <span class="material-symbols-outlined text-[20px]">info</span>
                <span class="text-[10px] font-black uppercase tracking-wider">Cara Penggunaan</span>
            </div>
            <ol class="list-decimal list-inside space-y-1 text-sm text-[#264191] leading-relaxed">
                <li>Pilih kelas asal lalu klik <strong>Tampilkan Siswa</strong>.</li>
                <li>Centang siswa yang akan dipindahkan.</li>
                <li>Pilih kelas tujuan, klik <strong>Pindahkan</strong>.</li>
            </ol>
        </div>
        <div class="p-5 bg-amber-50/60 rounded-xl border-l-4 border-amber-500">
            <div class="flex items-center gap-2 mb-2 text-amber-700">
                <span class="material-symbols-outlined text-[20px]">warning</span>
                <span class="text-[10px] font-black uppercase tracking-wider">Perhatian</span>
            </div>
            <p class="text-sm text-amber-700 leading-relaxed">
                Pemindahan bersifat <strong>permanen</strong> dan langsung diproses. Pastikan kelas tujuan sudah benar sebelum menekan tombol Pindahkan.
            </p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    // ─────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────
    function debounce(fn, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    // ── Bind / rebind checkbox listeners (dipanggil ulang setelah AJAX) ──
    function bindCheckboxes() {
        const selectAll  = document.getElementById('select-all');
        const countEl    = document.getElementById('selected-count');

        function updateCount() {
            if (!countEl) return;
            countEl.textContent = document.querySelectorAll('.siswa-checkbox:checked').length;
        }

        function syncSelectAll() {
            const all = [...document.querySelectorAll('.siswa-checkbox')];
            if (!selectAll || all.length === 0) return;
            selectAll.checked       = all.every(c => c.checked);
            selectAll.indeterminate = !selectAll.checked && all.some(c => c.checked);
        }

        // Select-All header checkbox
        if (selectAll) {
            // Clone to remove stale listeners
            const fresh = selectAll.cloneNode(true);
            selectAll.parentNode.replaceChild(fresh, selectAll);

            fresh.addEventListener('change', function () {
                document.querySelectorAll('.siswa-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateCount();
            });
        }

        // Individual checkboxes
        document.querySelectorAll('.siswa-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                syncSelectAll();
                updateCount();
            });
        });

        updateCount();
    }

    // ─────────────────────────────────────────────────────────────
    // AJAX Live Search
    // ─────────────────────────────────────────────────────────────
    const searchInput = document.getElementById('searchMutasi');
    const tbody       = document.getElementById('mutasiTbody');

    if (searchInput && tbody) {
        // Ambil kelas_asal dari URL saat ini
        const urlParams  = new URLSearchParams(window.location.search);
        const kelasAsal  = urlParams.get('kelas_asal') ?? '';
        const baseUrl    = '{{ route('admin.mutasi.index') }}';

        const doSearch = debounce(function (term) {
            // Loading state
            tbody.style.opacity = '0.4';

            const fetchUrl = `${baseUrl}?kelas_asal=${encodeURIComponent(kelasAsal)}&search=${encodeURIComponent(term)}`;

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Network error ' + res.status);
                return res.text();
            })
            .then(html => {
                tbody.innerHTML = html;
                tbody.style.opacity = '1';
                bindCheckboxes();   // rebind setelah DOM diperbarui
            })
            .catch(() => {
                tbody.style.opacity = '1';
            });
        }, 500);

        searchInput.addEventListener('input', function () {
            doSearch(this.value.trim());
        });
    }

    // ─────────────────────────────────────────────────────────────
    // Inisialisasi awal
    // ─────────────────────────────────────────────────────────────
    bindCheckboxes();

    // ─────────────────────────────────────────────────────────────
    // Konfirmasi submit form mutasi
    // ─────────────────────────────────────────────────────────────
    const formMutasi = document.getElementById('form-mutasi');
    if (formMutasi) {
        formMutasi.addEventListener('submit', function (e) {
            e.preventDefault();
            const checked    = document.querySelectorAll('.siswa-checkbox:checked').length;
            const tujuan     = document.getElementById('kelas_tujuan');
            const namaTujuan = tujuan?.options[tujuan.selectedIndex]?.text ?? '-';

            const swalBase = {
                buttonsStyling: false,
                customClass: {
                    popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
                    title: 'text-xl font-bold text-gray-800',
                    htmlContainer: 'text-sm text-gray-500 mt-2',
                    confirmButton: 'mt-4 bg-[#00236f] text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
                }
            };

            if (checked === 0) {
                Swal.fire({ ...swalBase, icon: 'warning', title: 'Belum Ada Siswa Dipilih',
                    text: 'Centang minimal satu siswa sebelum memindahkan.', confirmButtonText: 'Oke' });
                return;
            }

            if (!tujuan || !tujuan.value) {
                Swal.fire({ ...swalBase, icon: 'warning', title: 'Kelas Tujuan Belum Dipilih',
                    text: 'Pilih kelas tujuan terlebih dahulu.', confirmButtonText: 'Oke' });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Mutasi',
                html: `Pindahkan <strong>${checked} siswa</strong> ke kelas <strong>${namaTujuan}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pindahkan',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
                    title: 'text-xl font-bold text-gray-800',
                    htmlContainer: 'text-sm text-gray-500 mt-2',
                    confirmButton: 'mt-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
                    cancelButton: 'mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95 ml-2',
                }
            }).then(result => {
                if (result.isConfirmed) formMutasi.submit();
            });
        });
    }

    // ── Success Toast ──────────────────────────────────────────
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2500,
        buttonsStyling: false,
        customClass: {
            popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
            title: 'text-xl font-bold text-gray-800',
            htmlContainer: 'text-sm text-gray-500 mt-2',
        }
    });
    @endif

    // ── Buka modal otomatis jika ada error validasi file_excel ──
    @error('file_excel')
    document.getElementById('modal-import')?.classList.remove('hidden');
    @enderror

    // ── Tutup modal dengan tombol Escape ──────────────────────
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('modal-import')?.classList.add('hidden');
        }
    });

    // ── Dropzone File Input State Management ──────────────────────
    const fileInput = document.getElementById('fileInput');
    const dropzoneContainer = document.getElementById('dropzoneContainer');
    const defaultState = document.getElementById('defaultState');
    const selectedState = document.getElementById('selectedState');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const resetFileBtn = document.getElementById('resetFileBtn');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                // Sembunyikan state default, tampilkan state terpilih
                defaultState.classList.add('hidden');
                selectedState.classList.remove('hidden');

                // Update nama file
                fileNameDisplay.textContent = this.files[0].name;

                // Ubah styling container menjadi aktif
                dropzoneContainer.classList.remove('border-dashed', 'border-gray-200', 'bg-slate-50');
                dropzoneContainer.classList.add('border-solid', 'border-[#00236f]', 'bg-indigo-50/50');
            } else {
                resetDropzone();
            }
        });
    }

    if (resetFileBtn) {
        resetFileBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); // Mencegah klik menyebar kembali ke input file
            resetDropzone();
        });
    }

    function resetDropzone() {
        if (!fileInput) return;
        
        fileInput.value = '';
        
        // Tampilkan state default, sembunyikan state terpilih
        defaultState.classList.remove('hidden');
        selectedState.classList.add('hidden');

        // Kembalikan styling container ke awal
        dropzoneContainer.classList.remove('border-solid', 'border-[#00236f]', 'bg-indigo-50/50');
        dropzoneContainer.classList.add('border-dashed', 'border-gray-200', 'bg-slate-50');
    }

})();
</script>
@endpush
