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
                <span class="material-symbols-outlined text-[#00236f]" style="font-variation-settings:'FILL' 1;">add_circle</span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-[#00236f]">Formulir Data Kelas</h2>
                <p class="text-sm text-gray-500 mt-0.5">Lengkapi informasi tingkat, rombel, dan penugasan wali kelas.</p>
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
                        <label for="tingkat_kelas" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                            Tingkat Kelas <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="tingkat_kelas"
                                id="tingkat_kelas"
                                class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 pr-10 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('tingkat_kelas') ring-2 ring-red-400 @enderror"
                                required
                            >
                                <option value="" disabled {{ old('tingkat_kelas') ? '' : 'selected' }}>Pilih Tingkat</option>
                                <option value="X"   {{ old('tingkat_kelas') == 'X'   ? 'selected' : '' }}>Kelas X (Sepuluh)</option>
                                <option value="XI"  {{ old('tingkat_kelas') == 'XI'  ? 'selected' : '' }}>Kelas XI (Sebelas)</option>
                                <option value="XII" {{ old('tingkat_kelas') == 'XII' ? 'selected' : '' }}>Kelas XII (Dua Belas)</option>
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
                        <label for="rombel" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                            Rombongan Belajar (Rombel) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="rombel"
                                id="rombel"
                                class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 pr-10 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('rombel') ring-2 ring-red-400 @enderror"
                                required
                            >
                                <option value="" disabled {{ old('rombel') ? '' : 'selected' }}>Pilih Rombel</option>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('rombel') == $i ? 'selected' : '' }}>Rombel {{ $i }}</option>
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
                <div class="space-y-2">
                    <label for="wali_kelas_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        Wali Kelas <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            name="wali_kelas_id"
                            id="wali_kelas_id"
                            class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 pr-10 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('wali_kelas_id') ring-2 ring-red-400 @enderror"
                            required
                        >
                            <option value="" disabled {{ old('wali_kelas_id') ? '' : 'selected' }}>Pilih Guru Wali Kelas</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <span class="material-symbols-outlined text-lg">person_search</span>
                        </div>
                    </div>
                    @error('wali_kelas_id')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400 ml-1">Hanya guru yang belum memiliki jabatan wali kelas yang akan muncul dalam daftar.</p>
                </div>

            </div>{{-- end form content --}}

            {{-- Form Footer --}}
            <div class="px-8 py-5 bg-slate-50/60 border-t border-gray-100 flex items-center justify-end gap-4">
                <a
                    href="{{ route('classrooms.index') }}"
                    class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-[#00236f] hover:bg-gray-100 rounded-xl transition-colors"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="flex items-center gap-2 px-8 py-3 bg-[#00236f] hover:bg-[#001a55] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#00236f]/20 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all"
                >
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
                Penamaan kelas akan otomatis dibentuk oleh sistem dengan format <span class="font-bold">Tingkat - Rombel</span> (Contoh: X SMA 1).
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
@endsection
