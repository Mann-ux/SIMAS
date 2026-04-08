@extends('layouts.admin')

@section('title', 'Detail Kelas')

@section('page-title', 'Detail Kelas')
@section('page-description', 'Informasi kelas, anggota, dan rekap absensi')

@section('content')
    <div class="max-w-7xl mx-auto">

        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('classrooms.index') }}"
                class="inline-flex items-center gap-2 bg-[#00236f] text-white py-4 px-6 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#00236f]/90 hover:shadow-lg transition-all active:scale-[0.98] group">

                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>

                Kembali ke Daftar Kelas
            </a>
        </div>

        {{-- Page Title --}}
        <div class="mb-10">
            <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-1">
                Student Records &rsaquo; Detail Kelas
            </p>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-[#00236f] tracking-tight leading-tight">
                {{ $classroom->name }}
            </h1>
            <p class="text-gray-500 mt-2 font-medium text-sm max-w-2xl">
                Pengaturan profil kelas dan daftar inventaris siswa.
            </p>
        </div>

        @php
            $currentTingkat = old('tingkat_kelas', $classroom->tingkat ?? explode('-', $classroom->name)[0] ?? null);
            $nameParts = explode('-', $classroom->name, 2);
            $currentRombel = old('rombel', isset($nameParts[1]) ? (int) $nameParts[1] : null);
        @endphp

        {{-- Split-Screen Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- ═══════════════════════════════════════════
            LEFT COLUMN — Edit Identitas Kelas (Sticky)
            ═══════════════════════════════════════════ --}}
            <div class="lg:col-span-4 lg:sticky lg:top-6">
                <section class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,35,111,0.08)] p-8">

                    {{-- Section Header --}}
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-1.5 h-6 bg-[#00236f] rounded-full flex-shrink-0"></div>
                        <h2 class="text-[10px] font-black tracking-[0.2em] uppercase text-[#00236f]">Edit Identitas Kelas
                        </h2>
                    </div>

                    <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST" class="space-y-5" x-data="{
                                            ketuaManual: {{ old('ketua_set_manual_password') ? 'true' : 'false' }},
                                            sekretarisManual: {{ old('sekretaris_set_manual_password') ? 'true' : 'false' }}
                                        }">
                        @csrf
                        @method('PUT')

                        {{-- Tingkat & Rombel --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="tingkat_kelas"
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                                    Tingkat <span class="text-red-500">*</span>
                                </label>
                                <select name="tingkat_kelas" id="tingkat_kelas"
                                    class="w-full bg-slate-50 border-0 rounded-xl py-3 px-4 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('tingkat_kelas') ring-2 ring-red-400 @enderror"
                                    required>
                                    <option value="" disabled {{ $currentTingkat ? '' : 'selected' }}>-- Pilih --</option>
                                    <option value="X" {{ $currentTingkat === 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                                    <option value="XI" {{ $currentTingkat === 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                                    <option value="XII" {{ $currentTingkat === 'XII' ? 'selected' : '' }}>XII (Duabelas)
                                    </option>
                                </select>
                                @error('tingkat_kelas')
                                    <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="rombel"
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                                    Rombel <span class="text-red-500">*</span>
                                </label>
                                <select name="rombel" id="rombel"
                                    class="w-full bg-slate-50 border-0 rounded-xl py-3 px-4 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none appearance-none cursor-pointer @error('rombel') ring-2 ring-red-400 @enderror"
                                    required>
                                    <option value="" disabled {{ $currentRombel ? '' : 'selected' }}>--</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ (int) $currentRombel === $i ? 'selected' : '' }}>{{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                @error('rombel')
                                    <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Wali Kelas --}}
                        <div class="space-y-1.5">
                            <label for="wali_kelas_id"
                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                                Wali Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="wali_kelas_id" id="wali_kelas_id"
                                class="w-full bg-slate-50 border-0 rounded-xl py-3 px-4 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none cursor-pointer @error('wali_kelas_id') ring-2 ring-red-400 @enderror"
                                required>
                                <option value="" disabled {{ old('wali_kelas_id', $classroom->wali_kelas_id) ? '' : 'selected' }}>-- Pilih Wali Kelas --</option>
                                @foreach(($users ?? collect()) as $user)
                                    <option value="{{ $user->id }}" {{ old('wali_kelas_id', $classroom->wali_kelas_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('wali_kelas_id')
                                <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-3 border-t border-gray-100 space-y-5">

                            {{-- Ketua Kelas --}}
                            <div class="space-y-1.5">
                                <label for="ketua_id"
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                                    Ketua Kelas (Opsional)
                                </label>
                                <select name="ketua_id" id="ketua_id"
                                    class="w-full bg-slate-50 border-0 rounded-xl py-3 px-4 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none cursor-pointer @error('ketua_id') ring-2 ring-red-400 @enderror">
                                    <option value="" {{ old('ketua_id', $selectedKetuaId ?? null) ? '' : 'selected' }}>--
                                        Tidak Ada --</option>
                                    @foreach(($classroomStudents ?? collect()) as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('ketua_id', $selectedKetuaId ?? null) == $candidate->id ? 'selected' : '' }}>
                                            {{ $candidate->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ketua_id')
                                    <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                                @enderror

                                <div class="flex items-center gap-2 mt-2 ml-1">
                                    <input type="checkbox" name="ketua_set_manual_password" id="ketua_set_manual_password"
                                        value="1" x-model="ketuaManual"
                                        class="rounded border-gray-300 text-[#00236f] focus:ring-[#00236f]/30">
                                    <label for="ketua_set_manual_password"
                                        class="text-[11px] font-medium text-gray-500 cursor-pointer">
                                        Atur Password Manual
                                    </label>
                                </div>
                            </div>

                            {{-- Password Ketua --}}
                            <div class="space-y-1.5" x-show="ketuaManual" style="display: none;">
                                <label for="ketua_password"
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                                    Password Baru Ketua Kelas
                                </label>
                                <input type="text" name="ketua_password" id="ketua_password"
                                    value="{{ old('ketua_password') }}" placeholder="Minimal 6 karakter"
                                    class="w-full bg-slate-50 border-0 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-[#00236f]/20 outline-none @error('ketua_password') ring-2 ring-red-400 @enderror">
                                @error('ketua_password')
                                    <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Sekretaris --}}
                            <div class="space-y-1.5">
                                <label for="sekretaris_id"
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                                    Sekretaris (Opsional)
                                </label>
                                <select name="sekretaris_id" id="sekretaris_id"
                                    class="w-full bg-slate-50 border-0 rounded-xl py-3 px-4 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#00236f]/20 outline-none cursor-pointer @error('sekretaris_id') ring-2 ring-red-400 @enderror">
                                    <option value="" {{ old('sekretaris_id', $selectedSekretarisId ?? null) ? '' : 'selected' }}>-- Tidak Ada --</option>
                                    @foreach(($classroomStudents ?? collect()) as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('sekretaris_id', $selectedSekretarisId ?? null) == $candidate->id ? 'selected' : '' }}>
                                            {{ $candidate->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sekretaris_id')
                                    <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                                @enderror

                                <div class="flex items-center gap-2 mt-2 ml-1">
                                    <input type="checkbox" name="sekretaris_set_manual_password"
                                        id="sekretaris_set_manual_password" value="1" x-model="sekretarisManual"
                                        class="rounded border-gray-300 text-[#00236f] focus:ring-[#00236f]/30">
                                    <label for="sekretaris_set_manual_password"
                                        class="text-[11px] font-medium text-gray-500 cursor-pointer">
                                        Atur Password Manual
                                    </label>
                                </div>
                            </div>

                            {{-- Password Sekretaris --}}
                            <div class="space-y-1.5" x-show="sekretarisManual" style="display: none;">
                                <label for="sekretaris_password"
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                                    Password Baru Sekretaris
                                </label>
                                <input type="text" name="sekretaris_password" id="sekretaris_password"
                                    value="{{ old('sekretaris_password') }}" placeholder="Minimal 6 karakter"
                                    class="w-full bg-slate-50 border-0 rounded-xl py-3 px-4 text-sm font-mono focus:ring-2 focus:ring-[#00236f]/20 outline-none @error('sekretaris_password') ring-2 ring-red-400 @enderror">
                                @error('sekretaris_password')
                                    <p class="text-xs text-red-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>{{-- end .border-t section --}}

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full bg-[#00236f] text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-[#00236f]/90 hover:shadow-lg transition-all active:scale-[0.98] mt-2">
                            Simpan Perubahan
                        </button>

                    </form>
                </section>
            </div>{{-- end left col --}}

            {{-- ═══════════════════════════════════════════
            RIGHT COLUMN — Student List Panel
            ═══════════════════════════════════════════ --}}
            <div class="lg:col-span-8" x-data="{ showModal: false }">
                <div class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,35,111,0.08)] overflow-hidden">

                    {{-- Panel Header --}}
                    <div
                        class="px-8 py-7 flex flex-wrap items-center justify-between gap-4 bg-white border-b border-gray-100">
                        <div>
                            <h2 class="text-xl font-extrabold text-[#00236f] tracking-tight">DAFTAR SISWA</h2>
                            <p class="text-xs font-medium text-gray-400 mt-1">
                                Total: {{ $classroom->students->count() }} Siswa Terdaftar
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            {{-- Search Bar --}}
                            <div class="relative">
                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                                </svg>
                                <input type="text" id="search-students" placeholder="Cari NIS atau Nama..."
                                    class="pl-9 pr-4 py-2.5 bg-slate-50 border-0 rounded-full text-xs font-medium w-56 focus:ring-2 focus:ring-[#00236f]/20 outline-none"
                                    oninput="filterStudents(this.value)">
                            </div>
                            {{-- Add Button --}}
                            <button @click="showModal = true" type="button"
                                class="flex items-center gap-2 bg-[#006c49] text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-[#005236] transition-all shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Murid
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    @if($classroom->students->count() > 0)
                        <div class="px-4 pb-6">
                            <table class="w-full text-left" id="students-table">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No
                                        </th>
                                        <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">NIS
                                        </th>
                                        <th class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Nama Siswa</th>
                                        <th
                                            class="py-4 px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($classroom->students as $student)
                                        @php
                                            $genderColor = match ($student->jenis_kelamin) {
                                                'L' => 'bg-blue-500',
                                                'P' => 'bg-rose-500',
                                                default => 'bg-gray-300',
                                            };
                                        @endphp
                                        <tr class="group hover:bg-slate-50 transition-colors student-row"
                                            data-name="{{ strtolower($student->name) }}" data-nis="{{ $student->nis }}">
                                            {{-- NO with gender bar --}}
                                            <td class="py-5 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-1 h-8 {{ $genderColor }} rounded-full flex-shrink-0"></div>
                                                    <span
                                                        class="text-xs font-bold text-gray-400">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                                </div>
                                            </td>
                                            {{-- NIS --}}
                                            <td class="py-5 px-4 text-xs font-medium font-mono text-gray-400">
                                                {{ $student->nis }}
                                            </td>
                                            {{-- Name only --}}
                                            <td class="py-5 px-4">
                                                <span class="text-sm font-bold text-[#00236f]">{{ $student->name }}</span>
                                            </td>
                                            {{-- Action — Trash icon only --}}
                                            <td class="py-5 px-4 text-right">
                                                <form
                                                    action="{{ route('classrooms.remove_student', [$classroom->id, $student->id]) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Yakin mau mengeluarkan siswa ini dari kelas?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 rounded-full text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-all flex items-center justify-center"
                                                        title="Keluarkan dari kelas">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-16 px-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-600 mb-1">Belum Ada Siswa</h3>
                            <p class="text-sm text-gray-400">Klik tombol "Tambah Murid" untuk menambahkan siswa ke kelas ini.
                            </p>
                        </div>
                    @endif

                </div>{{-- end panel card --}}

                {{-- ══════════════════════════
                Modal — Tambah Siswa
                ══════════════════════════ --}}
                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    style="display: none;">
                    {{-- Backdrop --}}
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showModal = false"></div>

                    {{-- Dialog --}}
                    <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col overflow-hidden"
                        style="max-height: 85vh;" @click.stop>
                        {{-- ── Modal Header ── --}}
                        <div
                            class="flex-shrink-0 bg-gradient-to-r from-[#00236f] to-[#1a4aad] px-6 py-5 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white tracking-tight">Tambah Siswa ke Kelas</h3>
                                    <p class="text-blue-200/80 text-[11px] mt-0.5">Pilih siswa yang ingin ditambahkan</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form id="add-students-form" action="{{ route('classrooms.add_students', $classroom->id) }}"
                            method="POST" class="flex flex-col flex-1 min-h-0">
                            @csrf

                            {{-- ── Search & Select-All Bar ── --}}
                            <div class="flex-shrink-0 px-5 pt-5 pb-3 space-y-3 border-b border-gray-100">

                                {{-- Search input --}}
                                <div class="relative">
                                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                                    </svg>
                                    <input type="text" id="modal-search-input" placeholder="Cari nama atau NIS siswa..."
                                        autocomplete="off" oninput="filterModalStudents(this.value)"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-0 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-[#00236f]/20 outline-none transition">
                                </div>

                                {{-- Select All checkbox --}}
                                @if($availableStudents->count() > 0)
                                    <label class="flex items-center gap-2.5 cursor-pointer group select-none">
                                        <input type="checkbox" id="modal-select-all" onchange="toggleSelectAll(this)"
                                            class="w-4 h-4 rounded border-gray-300 text-[#00236f] focus:ring-[#00236f]/30 cursor-pointer">
                                        <span
                                            class="text-[11px] font-bold text-gray-500 uppercase tracking-widest group-hover:text-[#00236f] transition-colors">
                                            Pilih Semua
                                        </span>
                                        <span id="modal-selected-count"
                                            class="ml-auto text-[11px] font-semibold text-[#00236f] bg-blue-50 px-2 py-0.5 rounded-full hidden">
                                            0 dipilih
                                        </span>
                                    </label>
                                @endif
                            </div>

                            {{-- ── Student List ── --}}
                            <div class="flex-1 overflow-y-auto px-2 py-2" id="modal-student-list">
                                @if($availableStudents->count() > 0)
                                    @foreach($availableStudents as $student)
                                        @php
                                            $mGender = $student->jenis_kelamin ?? '';
                                            $mBorderColor = match ($mGender) {
                                                'L' => 'border-blue-500',
                                                'P' => 'border-rose-500',
                                                default => 'border-gray-200',
                                            };
                                            $mBarColor = match ($mGender) {
                                                'L' => 'bg-blue-500',
                                                'P' => 'bg-rose-500',
                                                default => 'bg-gray-300',
                                            };
                                        @endphp
                                        <label
                                            class="modal-student-item flex items-center gap-3 px-3 py-3 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors group border-l-4 {{ $mBorderColor }} mb-1"
                                            data-name="{{ strtolower($student->name) }}" data-nis="{{ $student->nis }}">
                                            {{-- Checkbox --}}
                                            <input type="checkbox" name="student_id[]" value="{{ $student->id }}"
                                                class="modal-student-checkbox w-4 h-4 rounded border-gray-300 text-[#00236f] focus:ring-[#00236f]/30 flex-shrink-0 cursor-pointer"
                                                onchange="updateSelectedCount()">
                                            {{-- Gender bar + Name --}}
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-semibold text-gray-800 group-hover:text-[#00236f] transition-colors truncate">
                                                    {{ $student->name }}
                                                </p>
                                            </div>
                                            {{-- NIS badge --}}
                                            <span
                                                class="flex-shrink-0 text-[11px] font-mono font-medium text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">
                                                {{ $student->nis }}
                                            </span>
                                        </label>
                                    @endforeach

                                    {{-- Empty search state --}}
                                    <div id="modal-no-results" class="hidden text-center py-10">
                                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                                        </svg>
                                        <p class="text-xs font-semibold text-gray-400">Tidak ada siswa yang cocok.</p>
                                    </div>
                                @else
                                    {{-- No available students at all --}}
                                    <div class="text-center py-12 px-6">
                                        <div
                                            class="inline-flex items-center justify-center w-14 h-14 bg-gray-100 rounded-full mb-3">
                                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-gray-600">Tidak Ada Siswa Tersedia</h4>
                                        <p class="text-xs text-gray-400 mt-1">Semua siswa sudah memiliki kelas.</p>
                                    </div>
                                @endif
                            </div>

                            {{-- ── Footer ── --}}
                            <div
                                class="flex-shrink-0 bg-gray-50/80 border-t border-gray-100 px-5 py-4 flex items-center justify-between gap-3">
                                <p class="text-[11px] text-gray-400 font-medium">
                                    {{ $availableStudents->count() }} siswa tersedia
                                </p>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="showModal = false"
                                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-sm transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-[#00236f] hover:bg-[#001a55] text-white font-bold rounded-xl text-sm shadow-md hover:shadow-lg transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        @if($availableStudents->count() === 0) disabled @endif>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Masukkan ke Kelas
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>{{-- end modal --}}

            </div>{{-- end right col --}}
        </div>{{-- end grid --}}
    </div>

    @push('scripts')
        <script>
            /* ─── Main table search ─── */
            function filterStudents(query) {
                const q = query.toLowerCase().trim();
                document.querySelectorAll('#students-table .student-row').forEach(row => {
                    const name = row.dataset.name || '';
                    const nis = (row.dataset.nis || '').toLowerCase();
                    row.style.display = (!q || name.includes(q) || nis.includes(q)) ? '' : 'none';
                });
            }

            /* ─── Modal: search filter ─── */
            function filterModalStudents(query) {
                const q = query.toLowerCase().trim();
                let visibleCount = 0;
                document.querySelectorAll('.modal-student-item').forEach(item => {
                    const name = (item.dataset.name || '');
                    const nis = (item.dataset.nis || '').toLowerCase();
                    const matches = !q || name.includes(q) || nis.includes(q);
                    item.style.display = matches ? '' : 'none';
                    if (matches) visibleCount++;
                });
                const noResults = document.getElementById('modal-no-results');
                if (noResults) noResults.classList.toggle('hidden', visibleCount > 0);
                // Keep select-all consistent
                syncSelectAll();
            }

            /* ─── Modal: toggle select all ─── */
            function toggleSelectAll(masterCb) {
                document.querySelectorAll('.modal-student-checkbox').forEach(cb => {
                    // Only affect visible rows
                    if (cb.closest('.modal-student-item').style.display !== 'none') {
                        cb.checked = masterCb.checked;
                    }
                });
                updateSelectedCount();
            }

            /* ─── Modal: update counter badge ─── */
            function updateSelectedCount() {
                const checked = document.querySelectorAll('.modal-student-checkbox:checked').length;
                const badge = document.getElementById('modal-selected-count');
                const master = document.getElementById('modal-select-all');
                if (badge) {
                    if (checked > 0) {
                        badge.textContent = checked + ' dipilih';
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                syncSelectAll();
            }

            /* ─── Modal: sync master checkbox state ─── */
            function syncSelectAll() {
                const master = document.getElementById('modal-select-all');
                if (!master) return;
                const visible = Array.from(document.querySelectorAll('.modal-student-item')).filter(i => i.style.display !== 'none');
                const checkedV = visible.filter(i => i.querySelector('.modal-student-checkbox')?.checked);
                master.indeterminate = checkedV.length > 0 && checkedV.length < visible.length;
                master.checked = visible.length > 0 && checkedV.length === visible.length;
            }

            /* ─── Reset modal state when it re-opens ─── */
            document.addEventListener('DOMContentLoaded', () => {
                // Watch for modal open via Alpine's x-show attribute mutation
                const observer = new MutationObserver(() => {
                    const input = document.getElementById('modal-search-input');
                    if (input && input.closest('[style*="display: none"]') === null) {
                        // Modal just became visible — reset
                        input.value = '';
                        filterModalStudents('');
                        const master = document.getElementById('modal-select-all');
                        if (master) { master.checked = false; master.indeterminate = false; }
                        document.querySelectorAll('.modal-student-checkbox').forEach(cb => cb.checked = false);
                        updateSelectedCount();
                    }
                });
                const modalRoot = document.querySelector('[id="modal-search-input"]')?.closest('[style]')?.parentElement;
                if (modalRoot) observer.observe(modalRoot, { attributes: true, attributeFilter: ['style'] });
            });
        </script>
    @endpush

@endsection