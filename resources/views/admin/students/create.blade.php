@extends('layouts.admin')

@section('title', 'Tambah Siswa')

@section('page-title', 'Tambah Siswa Baru')
@section('page-description', 'Formulir untuk menambahkan siswa baru ke dalam sistem')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Page Header --}}
    <div>
        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-1">
            Kelola Siswa &rsaquo; Tambah
        </p>
        <h1 class="text-4xl font-extrabold text-[#00236f] tracking-tight leading-tight">Data Peserta Didik</h1>
        <p class="text-gray-500 mt-2 text-sm max-w-2xl">Daftarkan peserta didik baru SMA ke dalam sistem informasi akademik.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,35,111,0.06)] overflow-hidden">

        {{-- Card Header --}}
        <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-[#dce1ff] rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[#00236f]" style="font-variation-settings:'FILL' 1;">person_add</span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-[#00236f]">Formulir Data Siswa</h2>
                <p class="text-sm text-gray-500 mt-0.5">Lengkapi NIS, nama lengkap, dan penempatan kelas siswa.</p>
            </div>
        </div>

        {{-- Form Content --}}
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="px-8 py-8 space-y-6">

                {{-- NIS --}}
                <div class="space-y-2">
                    <label for="nis" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        NIS (Nomor Induk Siswa) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nis"
                        id="nis"
                        value="{{ old('nis') }}"
                        placeholder="Contoh: 2024001"
                        class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 text-sm font-mono font-medium text-gray-800 placeholder-gray-400 placeholder:font-sans focus:ring-2 focus:ring-[#00236f]/20 outline-none @error('nis') ring-2 ring-red-400 @enderror"
                        required
                    >
                    @error('nis')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Lengkap --}}
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Budi Pratama"
                        class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 text-sm font-medium text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#00236f]/20 outline-none @error('name') ring-2 ring-red-400 @enderror"
                        required
                    >
                    @error('name')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 h-14 px-5 bg-slate-50 rounded-xl cursor-pointer hover:bg-blue-50 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-[#00236f]/30 transition-all">
                            <input
                                type="radio"
                                name="jenis_kelamin"
                                value="L"
                                class="w-4 h-4 text-[#00236f] border-gray-300 focus:ring-[#00236f]/30"
                                {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}
                                required
                            >
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-6 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-semibold text-gray-700">Laki-laki</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 h-14 px-5 bg-slate-50 rounded-xl cursor-pointer hover:bg-rose-50 has-[:checked]:bg-rose-50 has-[:checked]:ring-2 has-[:checked]:ring-rose-300 transition-all">
                            <input
                                type="radio"
                                name="jenis_kelamin"
                                value="P"
                                class="w-4 h-4 text-rose-500 border-gray-300 focus:ring-rose-300"
                                {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}
                            >
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-6 bg-rose-500 rounded-full"></div>
                                <span class="text-sm font-semibold text-gray-700">Perempuan</span>
                            </div>
                        </label>
                    </div>
                    @error('jenis_kelamin')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kelas (Classroom) --}}
                <div class="space-y-2">
                    <label for="classroom_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        Penempatan Kelas
                    </label>
                    <div class="relative">
                        <select
                            name="classroom_id"
                            id="classroom_id"
                            class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 pr-10 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('classroom_id') ring-2 ring-red-400 @enderror"
                        >
                            <option value="">-- Biarkan Kosong (Siswa Tanpa Kelas) --</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <span class="material-symbols-outlined text-lg">expand_more</span>
                        </div>
                    </div>
                    @error('classroom_id')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400 ml-1">Kelas dapat ditentukan kemudian melalui halaman detail kelas.</p>
                </div>

            </div>{{-- end form content --}}

            {{-- Form Footer --}}
            <div class="px-8 py-5 bg-slate-50/60 border-t border-gray-100 flex items-center justify-end gap-4">
                <a
                    href="{{ route('students.index') }}"
                    class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-[#00236f] hover:bg-gray-100 rounded-xl transition-colors"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="flex items-center gap-2 px-8 py-3 bg-[#00236f] hover:bg-[#001a55] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#00236f]/20 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all"
                >
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    Simpan Data Siswa
                </button>
            </div>
        </form>
    </div>

    {{-- Guidance Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="p-5 bg-blue-50/60 rounded-xl border-l-4 border-[#00236f]">
            <div class="flex items-center gap-2 mb-2 text-[#00236f]">
                <span class="material-symbols-outlined text-[20px]">badge</span>
                <span class="text-[10px] font-black uppercase tracking-wider">NIS</span>
            </div>
            <p class="text-sm text-[#264191] leading-relaxed">
                Nomor Induk Siswa harus unik. Pastikan NIS belum terdaftar sebelumnya di sistem.
            </p>
        </div>
        <div class="p-5 bg-green-50/60 rounded-xl border-l-4 border-[#006c49]">
            <div class="flex items-center gap-2 mb-2 text-[#006c49]">
                <span class="material-symbols-outlined text-[20px]">school</span>
                <span class="text-[10px] font-black uppercase tracking-wider">Penempatan</span>
            </div>
            <p class="text-sm text-[#005236] leading-relaxed">
                Siswa yang belum memiliki kelas dapat ditambahkan ke kelas melalui menu <span class="font-bold">Detail Kelas</span>.
            </p>
        </div>
        <div class="p-5 bg-orange-50/60 rounded-xl border-l-4 border-orange-600">
            <div class="flex items-center gap-2 mb-2 text-orange-700">
                <span class="material-symbols-outlined text-[20px]">info</span>
                <span class="text-[10px] font-black uppercase tracking-wider">Konteks SMA</span>
            </div>
            <p class="text-sm text-orange-700 leading-relaxed">
                Data ini hanya berlaku untuk jenjang <span class="font-bold">SMA</span>. Pastikan siswa sudah terdaftar di jenjang yang benar.
            </p>
        </div>
    </div>

</div>
@endsection
