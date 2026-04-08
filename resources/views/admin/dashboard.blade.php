@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-description', 'Overview presensi & monitoring kelas secara real-time')

@section('content')
<div id="admin-dashboard">

    {{-- ═══ SECTION TITLE ═══════════════════════════════════════════════════════ --}}
    <div class="mb-10">
        <h2 class="font-display text-4xl font-extrabold text-primary tracking-tight mb-2">
            Overview Presensi Hari Ini
        </h2>
        <p class="text-on_surface_variant font-sans">
            Laporan kehadiran siswa secara real-time pada seluruh jenjang kelas.
        </p>
    </div>

    {{-- ═══ STATS BENTO GRID ══════════════════════════════════════════════════════
         3 kolom di md+, empat card total: Hadir, Izin/Sakit, Alpa + total siswa
         Desktop: grid-cols-3, Mobile: single column stack
    ══════════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12" id="stats-bento">

        {{-- Hadir Card --}}
        <div class="bg-emerald-400 p-8 rounded-3xl shadow-sm flex flex-col justify-between h-48 cursor-default">
            <div class="flex justify-between items-start">
                <svg class="w-8 h-8 text-emerald-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @php
                    $pctHadir = $total_siswa > 0 ? round($hadir / $total_siswa * 100, 1) : 0;
                @endphp
                <span class="text-xs font-bold font-display bg-emerald-950/15 text-emerald-900 px-3 py-1 rounded-full">
                    {{ $pctHadir }}% Hadir
                </span>
            </div>
            <div>
                <p class="text-4xl font-extrabold font-display text-emerald-900">
                    {{ $hadir }}
                </p>
                <p class="text-sm font-medium text-emerald-900 uppercase tracking-widest mt-1">
                    Hadir
                </p>
            </div>
        </div>

        {{-- Izin / Sakit Card --}}
        <div class="bg-blue-800 p-8 rounded-3xl shadow-sm flex flex-col justify-between h-48 cursor-default">
            <div class="flex justify-between items-start">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @php
                    $pctIzin = $total_siswa > 0 ? round($izin_sakit / $total_siswa * 100, 1) : 0;
                @endphp
                <span class="text-xs font-bold font-display bg-white/20 text-white px-3 py-1 rounded-full">
                    {{ $pctIzin }}% dari Total
                </span>
            </div>
            <div>
                <p class="text-4xl font-extrabold font-display text-white">
                    {{ $izin_sakit }}
                </p>
                <p class="text-sm font-medium text-white uppercase tracking-widest mt-1">
                    Izin / Sakit
                </p>
            </div>
        </div>

        {{-- Alpa Card --}}
        <div class="bg-orange-900 p-8 rounded-3xl shadow-sm flex flex-col justify-between h-48 cursor-default sm:col-span-2 lg:col-span-1">
            <div class="flex justify-between items-start">
                <svg class="w-8 h-8 text-orange-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                @php
                    $pctAlpa = $total_siswa > 0 ? round($alpa / $total_siswa * 100, 1) : 0;
                @endphp
                <span class="text-xs font-bold font-display bg-white/10 text-orange-100 px-3 py-1 rounded-full">
                    {{ $pctAlpa > 2 ? 'Perlu Atensi' : 'Stabil' }}
                </span>
            </div>
            <div>
                <p class="text-4xl font-extrabold font-display text-orange-100">
                    {{ $alpa }}
                </p>
                <p class="text-sm font-medium text-orange-100 uppercase tracking-widest mt-1">
                    Alpa
                </p>
            </div>
        </div>
    </div>

    {{-- ═══ WARNING LIST — KELAS BELUM ABSEN ══════════════════════════════════════ --}}
    <div class="mb-12" id="warning-section">
        @if($kelas_belum_absen->isNotEmpty())
            <div class="bg-surface-container-low rounded-3xl p-8 lg:p-10">

                {{-- Warning Header --}}
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-xl btn-primary-gradient flex items-center justify-center text-white shadow-lg flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-display text-xl lg:text-2xl font-extrabold text-primary">
                            Peringatan: Kelas Belum Absen Hari Ini
                        </h3>
                        <p class="text-sm text-on_surface_variant font-sans mt-0.5">
                            Segera hubungi wali kelas untuk sinkronisasi data presensi pagi.
                        </p>
                    </div>
                    <span class="flex-shrink-0 px-3 py-1 rounded-full bg-error-container text-on-error-container text-xs font-bold font-display hidden sm:inline-flex">
                        {{ $kelas_belum_absen->count() }} kelas
                    </span>
                </div>

                {{-- Warning Items --}}
                <div class="space-y-4">
                    @foreach($kelas_belum_absen as $kelas)
                        <div class="bg-surface-container-lowest rounded-2xl px-5 py-4 lg:px-6 lg:py-5 flex items-center justify-between gap-4 group hover:translate-x-2 transition-transform duration-200 cursor-pointer">

                            {{-- Left: Icon + Info --}}
                            <div class="flex items-center gap-4 lg:gap-6 min-w-0">
                                <div class="w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-error-container flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-on-error-container" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-display text-base lg:text-lg font-bold text-primary truncate">
                                        {{ $kelas->name }}
                                    </h4>
                                    <p class="text-sm text-on_surface_variant font-sans truncate">
                                        Wali Kelas:
                                        <span class="font-semibold text-primary">
                                            {{ $kelas->user?->name ?? 'Belum ditentukan' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            {{-- Right: Status + Action --}}
                            <div class="flex items-center gap-4 lg:gap-8 flex-shrink-0">
                                <div class="text-right hidden md:block">
                                    <p class="text-[10px] text-on_surface_variant uppercase tracking-widest mb-1 font-display">
                                        Status Laporan
                                    </p>
                                    <p class="text-xs font-bold text-error">
                                        Belum Input
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    class="btn-primary-gradient text-white font-display text-sm font-bold px-5 py-2.5 rounded-xl hover:opacity-90 active:scale-95 transition-all flex items-center gap-2 shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Hubungi</span>
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        @else
            {{-- All Clear State --}}
            <div class="bg-surface-container-low rounded-3xl p-8 lg:p-10 flex items-center justify-between gap-6">
                <div>
                    <h3 class="font-display text-xl lg:text-2xl font-extrabold text-secondary mb-1">
                        Semua kelas sudah absen hari ini!
                    </h3>
                    <p class="text-sm text-on_surface_variant font-sans">
                        Mantap, monitoring harian aman terkendali.
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0">
                    <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        @endif
    </div>

    {{-- ═══ PANEL UNDUH LAPORAN BULANAN ═════════════════════════════════════════ --}}
    <form action="#" method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-12">
        <h3 class="text-xl font-bold text-gray-800">Unduh Laporan Rekapitulasi</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mt-4">
            <div>
                <label for="periode" class="text-sm font-bold text-gray-500 uppercase block mb-2">PERIODE</label>
                <select id="periode" name="periode" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="2026-04">April 2026</option>
                </select>
            </div>

            <div>
                <label for="tingkat" class="text-sm font-bold text-gray-500 uppercase block mb-2">TINGKAT</label>
                <select id="tingkat" name="tingkat" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih Tingkat</option>
                    <option value="X">X</option>
                    <option value="XI">XI</option>
                    <option value="XII SMA">XII SMA</option>
                </select>
            </div>

            <div>
                <label for="rombel" class="text-sm font-bold text-gray-500 uppercase block mb-2">ROMBEL / KELAS</label>
                <select id="rombel" name="rombel" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih Rombel</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-4 rounded-lg transition-colors">
                    Download Excel
                </button>
            </div>
        </div>
    </form>

    {{-- ═══ FOOTER META ══════════════════════════════════════════════════════════ --}}
    <div class="pt-8 border-t border-on_surface_variant/10 flex flex-wrap justify-between items-center gap-4 text-[10px] uppercase tracking-widest text-on_surface_variant">
        <span>Last Updated: {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y, H:i') }} WIB</span>
        <span class="font-bold text-primary">SIMAS Scholastic Edition v2.0</span>
    </div>

</div>
@endsection
