@extends('layouts.admin')

@section('title', 'Tambah User')

@section('page-title', 'Tambah User Baru')
@section('page-description', 'Formulir untuk menambahkan guru atau admin baru ke dalam sistem')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Page Header --}}
    <div>
        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-1">
            Kelola Guru &rsaquo; Tambah
        </p>
        <h1 class="text-4xl font-extrabold text-[#00236f] tracking-tight leading-tight">Manajemen Pengguna</h1>
        <p class="text-gray-500 mt-2 text-sm max-w-2xl">Registrasi akun guru atau staf pengajar baru untuk tahun ajaran berjalan.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,35,111,0.06)] overflow-hidden">

        {{-- Card Header --}}
        <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-[#dce1ff] rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[#00236f]" style="font-variation-settings:'FILL' 1;">person_add</span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-[#00236f]">Formulir Data Pengguna</h2>
                <p class="text-sm text-gray-500 mt-0.5">Lengkapi informasi identitas, akses login, dan peran pengguna.</p>
            </div>
        </div>

        {{-- Form Content --}}
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="px-8 py-8 space-y-6">

                {{-- NIP --}}
                <div class="space-y-2">
                    <label for="nip" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        NIP <span class="text-gray-400 font-normal normal-case tracking-normal">(Opsional)</span>
                    </label>
                    <input
                        type="text"
                        name="nip"
                        id="nip"
                        value="{{ old('nip') }}"
                        placeholder="Contoh: 197512152006041001"
                        class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 text-sm font-medium text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#00236f]/20 outline-none @error('nip') ring-2 ring-red-400 @enderror"
                    >
                    @error('nip')
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
                        placeholder="Contoh: Budi Santoso, S.Pd"
                        class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 text-sm font-medium text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#00236f]/20 outline-none @error('name') ring-2 ring-red-400 @enderror"
                        required
                    >
                    @error('name')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="Contoh: budi.santoso@smasatu.sch.id"
                        class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 text-sm font-medium text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#00236f]/20 outline-none @error('email') ring-2 ring-red-400 @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Minimal 8 karakter"
                        class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 text-sm font-medium text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-[#00236f]/20 outline-none @error('password') ring-2 ring-red-400 @enderror"
                        required
                    >
                    @error('password')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="space-y-2">
                    <label for="role" class="block text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">
                        Peran (Role) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            name="role"
                            id="role"
                            class="w-full h-14 bg-slate-50 border-0 rounded-xl px-4 pr-10 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('role') ring-2 ring-red-400 @enderror"
                            required
                        >
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Peran --</option>
                            <option value="admin"      {{ old('role') == 'admin'      ? 'selected' : '' }}>Administrator</option>
                            <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <span class="material-symbols-outlined text-lg">expand_more</span>
                        </div>
                    </div>
                    @error('role')
                        <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>{{-- end form content --}}

            {{-- Form Footer --}}
            <div class="px-8 py-5 bg-slate-50/60 border-t border-gray-100 flex items-center justify-end gap-4">
                <a
                    href="{{ route('users.index') }}"
                    class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-[#00236f] hover:bg-gray-100 rounded-xl transition-colors"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="flex items-center gap-2 px-8 py-3 bg-[#00236f] hover:bg-[#001a55] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#00236f]/20 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all"
                >
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    Simpan Data Guru
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
                NIP bersifat opsional. Guru honorer atau staf non-PNS dapat melewati kolom ini.
            </p>
        </div>
        <div class="p-5 bg-green-50/60 rounded-xl border-l-4 border-[#006c49]">
            <div class="flex items-center gap-2 mb-2 text-[#006c49]">
                <span class="material-symbols-outlined text-[20px]">security</span>
                <span class="text-[10px] font-black uppercase tracking-wider">Keamanan</span>
            </div>
            <p class="text-sm text-[#005236] leading-relaxed">
                Gunakan password minimal 8 karakter. Guru dapat mengubah password setelah login pertama.
            </p>
        </div>
        <div class="p-5 bg-orange-50/60 rounded-xl border-l-4 border-orange-600">
            <div class="flex items-center gap-2 mb-2 text-orange-700">
                <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                <span class="text-[10px] font-black uppercase tracking-wider">Peran</span>
            </div>
            <p class="text-sm text-orange-700 leading-relaxed">
                Peran <span class="font-bold">Wali Kelas</span> dapat ditugaskan ke kelas tertentu setelah akun dibuat.
            </p>
        </div>
    </div>

</div>
@endsection
