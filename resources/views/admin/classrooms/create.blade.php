@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('page-title', 'Tambah Kelas Baru')
@section('page-description', 'Formulir untuk menambahkan kelas baru ke dalam sistem')
@section('content')
    <div class="max-w-4xl mx-auto space-y-8">

        {{-- Page Header --}}
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-1">
                Kelola Kelas &rsaquo; Tambah
            </p>
            <h1 class="text-4xl font-extrabold text-[#00236f] tracking-tight leading-tight">Manajemen Akademik</h1>
            <p class="text-gray-500 mt-2 text-sm max-w-2xl">Pendaftaran entitas kelas baru untuk tahun ajaran berjalan.</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,35,111,0.06)] overflow-hidden">

            {{-- Card Header --}}
            <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-[#dce1ff] rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[#00236f]"
                        style="font-variation-settings:'FILL' 1;">add_circle</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-[#00236f]">Formulir Data Kelas</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Lengkapi informasi tingkat, rombel, dan penugasan wali kelas.
                    </p>
                </div>
            </div>

            {{-- Form Content --}}
            <form action="{{ route('classrooms.store') }}" method="POST">
                @csrf

                <div class="px-8 py-8 space-y-8">

                    {{-- Row 1: Tingkat & Rombel --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Tingkat Kelas --}}
                        <div class="space-y-2">
                            <label for="tingkatSelect"
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                                Tingkat Kelas <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="tingkat_kelas" id="tingkatSelect"
                                    class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 pr-10 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('tingkat_kelas') ring-2 ring-red-400 @enderror"
                                    required>
                                    <option value="" disabled {{ old('tingkat_kelas') ? '' : 'selected' }}>Pilih Tingkat
                                    </option>
                                    <option value="X" {{ old('tingkat_kelas') == 'X' ? 'selected' : '' }}>Kelas X (Sepuluh)
                                    </option>
                                    <option value="XI" {{ old('tingkat_kelas') == 'XI' ? 'selected' : '' }}>Kelas XI (Sebelas)
                                    </option>
                                    <option value="XII" {{ old('tingkat_kelas') == 'XII' ? 'selected' : '' }}>Kelas XII (Dua
                                        Belas)</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <span class="material-symbols-outlined text-lg">expand_more</span>
                                </div>
                            </div>
                            @error('tingkat_kelas')
                                <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rombel --}}
                        <div class="space-y-2">
                            <label for="rombelSelect"
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                                Rombongan Belajar (Rombel) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="rombel" id="rombelSelect"
                                    class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 pr-10 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('rombel') ring-2 ring-red-400 @enderror"
                                    required>
                                    <option value="" disabled {{ old('rombel') ? '' : 'selected' }}>Pilih Rombel</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('rombel') == $i ? 'selected' : '' }}>Rombel {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <span class="material-symbols-outlined text-lg">expand_more</span>
                                </div>
                            </div>
                            @error('rombel')
                                <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Wali Kelas (full width) --}}
                    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.css"
                        rel="stylesheet">
                    <style>
                        .ts-wrapper {
                            width: 100% !important;
                        }

                        .ts-wrapper .ts-control {
                            border: 1px solid #d1d5db !important;
                            border-radius: 0.375rem !important;
                            padding: 0.5rem 0.75rem !important;
                            min-height: 42px !important;
                            display: flex !important;
                            align-items: center !important;
                            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                            font-size: 0.875rem !important;
                            background-color: #ffffff !important;
                        }

                        .ts-wrapper .ts-control input {
                            font-size: 0.875rem !important;
                            line-height: 1.25rem !important;
                            margin: 0 !important;
                            padding: 0 !important;
                        }

                        .ts-wrapper.focus .ts-control {
                            border-color: #3b82f6 !important;
                            box-shadow: 0 0 0 1px #3b82f6 !important;
                        }

                        .ts-dropdown {
                            border-radius: 0.375rem !important;
                            border: 1px solid #d1d5db !important;
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
                            font-size: 0.875rem !important;
                            margin-top: 4px !important;
                        }

                        .ts-dropdown .option {
                            padding: 0.5rem 0.75rem !important;
                        }

                        .ts-control {
                            border-width: 0px !important;
                        }
                    </style>

                    <div class="space-y-2">
                        <label for="waliKelasSelect"
                            class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                            Wali Kelas <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="wali_kelas_id" id="waliKelasSelect"
                                class="w-full @error('wali_kelas_id') ring-2 ring-red-400 @enderror" required>
                                <option value="" disabled {{ old('wali_kelas_id') ? '' : 'selected' }}>Pilih Guru Wali Kelas
                                </option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('wali_kelas_id')
                            <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-gray-400 ml-1">Hanya guru yang belum memiliki jabatan wali kelas yang
                            akan muncul dalam daftar.</p>
                    </div>

                </div>{{-- end form content --}}

                {{-- Form Footer --}}
                <div
                    class="px-8 py-5 bg-slate-50/60 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('classrooms.index') }}"
                        class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-[#00236f] hover:bg-gray-100 rounded-xl transition-colors">
                        Batal
                    </a>
                    {{-- Simpan & Tambah Lagi --}}
                    <button type="submit" name="action" value="save_and_add"
                        class="flex items-center gap-2 px-7 py-3 bg-white border-2 border-emerald-500 text-emerald-700 hover:bg-emerald-500 hover:text-white rounded-xl text-sm font-bold shadow-sm hover:shadow-lg hover:shadow-emerald-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        Simpan &amp; Tambah Lagi
                    </button>
                    {{-- Simpan --}}
                    <button type="submit" name="action" value="save"
                        class="flex items-center gap-2 px-8 py-3 bg-[#00236f] hover:bg-[#001a55] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#00236f]/20 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        Simpan Data Kelas
                    </button>
                </div>
            </form>
        </div>

        {{-- Guidance Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="p-5 bg-blue-50/60 rounded-xl border-l-4 border-[#00236f]">
                <div class="flex items-center gap-2 mb-2 text-[#00236f]">
                    <span class="material-symbols-outlined text-[20px]">info</span>
                    <span class="text-[10px] font-black uppercase tracking-wider">Informasi</span>
                </div>
                <p class="text-sm text-[#264191] leading-relaxed">
                    Penamaan kelas akan otomatis dibentuk oleh sistem dengan format <span class="font-bold">Tingkat -
                        Rombel</span> (Contoh: X SMA 1).
                </p>
            </div>
            <div class="p-5 bg-green-50/60 rounded-xl border-l-4 border-[#006c49]">
                <div class="flex items-center gap-2 mb-2 text-[#006c49]">
                    <span class="material-symbols-outlined text-[20px]">rule</span>
                    <span class="text-[10px] font-black uppercase tracking-wider">Validasi</span>
                </div>
                <p class="text-sm text-[#005236] leading-relaxed">
                    Pastikan kombinasi Tingkat dan Rombel belum terdaftar sebelumnya di database sekolah.
                </p>
            </div>
            <div class="p-5 bg-orange-50/60 rounded-xl border-l-4 border-orange-600">
                <div class="flex items-center gap-2 mb-2 text-orange-700">
                    <span class="material-symbols-outlined text-[20px]">edit_calendar</span>
                    <span class="text-[10px] font-black uppercase tracking-wider">Tahun Ajaran</span>
                </div>
                <p class="text-sm text-orange-700 leading-relaxed">
                    Data yang disimpan akan otomatis masuk ke filter Tahun Ajaran <span class="font-bold">aktif</span>.
                </p>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Tom Select for searchable Wali Kelas
                new TomSelect("#waliKelasSelect", {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: "Ketik nama wali kelas..."
                });

                const tingkatSelect = document.getElementById('tingkatSelect');
                const rombelSelect = document.getElementById('rombelSelect');

                // Parse existing classes JSON that was passed from the controller
                const existingClasses = @json($existingClasses);

                function updateRombelOptions() {
                    const selectedTingkat = tingkatSelect.value;

                    // Get array of used rombels for the selected tingkat
                    const usedRombels = existingClasses
                        .filter(cls => cls.tingkat === selectedTingkat)
                        .map(cls => cls.rombel);

                    // Loop over rombel options to act accordingly
                    Array.from(rombelSelect.options).forEach(option => {
                        if (option.value === "") return; // Skip placeholder option

                        if (usedRombels.includes(option.value)) {
                            option.disabled = true;
                            option.classList.add('bg-gray-200', 'text-gray-400');
                            option.innerText = `Rombel ${option.value} (Sudah Terpakai)`;
                        } else {
                            option.disabled = false;
                            option.classList.remove('bg-gray-200', 'text-gray-400');
                            option.innerText = `Rombel ${option.value}`;
                        }
                    });

                    // Reset rombel selection whenever tingkat changes
                    rombelSelect.value = "";
                }

                // Attach event listener to tingkat select
                tingkatSelect.addEventListener('change', updateRombelOptions);

                // Run once on page load to configure initial state if user goes back
                if (tingkatSelect.value) {
                    updateRombelOptions();

                    // Re-apply old val if available
                    const oldRombel = "{{ old('rombel') }}";
                    if (oldRombel) {
                        // Set only if the old mapped value is not disabled
                        const optionToSelect = Array.from(rombelSelect.options).find(o => o.value === oldRombel);
                        if (optionToSelect && !optionToSelect.disabled) {
                            rombelSelect.value = oldRombel;
                        } else {
                            rombelSelect.value = "";
                        }
                    }
                }
            });
        </script>
    @endpush

@endsection