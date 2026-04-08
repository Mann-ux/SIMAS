@extends('layouts.wali-kelas')

@section('title', 'Dashboard Wali Kelas')

@section('page-title', 'Dashboard Wali Kelas')
@section('page-description', 'Pantau kehadiran siswa dan kelola absensi kelas Anda')

@section('content')
{{-- ============================================================
     DASHBOARD WALI KELAS — Scholastic Editorial Design System
     Responsive: Mobile-first, bergabung PC & Mobile layout
     ============================================================ --}}

{{-- ── WELCOME HEADER ────────────────────────────────────────── --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-900">Selamat Datang di Kelas {{ $classroom?->name ?? 'XII' }} SMA 1</h2>
    <p class="text-sm text-gray-500">Sistem Informasi Manajemen Akademik & Siswa</p>
</div>

{{-- ── BANNER STATUS ABSENSI ─────────────────────────────────── --}}
@if($recap_hari_ini)
<div class="mb-12">
    <div class="bg-emerald-400 rounded-xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between border-l-8 border-emerald-700 shadow-sm gap-4">
        <div class="flex items-center gap-4">
            <div class="bg-emerald-500/50 p-3 rounded-full flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-emerald-900">
                  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-emerald-950 text-xl tracking-tight">Status Absensi Hari Ini</h2>
                <p class="text-emerald-900 font-medium">SUDAH DIISI PADA {{ $lastUpdate ? $lastUpdate->updated_at->format('H:i') : \Carbon\Carbon::now()->format('H:i') }} WIB</p> 
            </div>
        </div>
        <a href="{{ route('wali-kelas.absen.create') }}" class="bg-blue-900 text-white px-6 py-3 rounded-xl font-bold text-sm tracking-wide shadow-md hover:bg-blue-800 transition-all active:scale-95 inline-block text-center flex-shrink-0 whitespace-nowrap">
            LIHAT DETAIL
        </a>
    </div>
</div>
@else
<div class="mb-12">
    <div class="bg-amber-400 rounded-xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between border-l-8 border-amber-600 shadow-sm gap-4">
        <div class="flex items-center gap-4">
            <div class="bg-amber-500/50 p-3 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-amber-950 text-xl tracking-tight">Status Absensi Hari Ini</h2>
                <p class="text-amber-900 font-medium">BELUM DIISI hari ini</p>
            </div>
        </div>
        <a href="{{ route('wali-kelas.absen.create') }}" class="bg-blue-900 text-white px-6 py-3 rounded-xl font-bold text-sm tracking-wide shadow-md hover:bg-blue-800 transition-all active:scale-95 inline-block text-center flex-shrink-0 whitespace-nowrap">
            INPUT SEKARANG
        </a>
    </div>
</div>
@endif

@if($classroom)

{{-- ── STAT CARDS GRID ──────────────────────────────────────── --}}
<section class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-6">

    {{-- Total Siswa --}}
    <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300 flex flex-col">
        <span class="absolute top-4 md:top-5 right-4 md:right-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">KAPASITAS</span>
        <div class="bg-[#00236f]/5 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#00236f]/10 transition-colors">
            <svg class="w-6 h-6 text-[#00236f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <p class="text-[11px] md:text-sm font-medium text-slate-500 uppercase tracking-wide">Total Siswa</p>
        <h3 class="text-3xl md:text-4xl font-extrabold text-[#00236f] mt-1">{{ $total_siswa }}</h3>
        <p class="text-[10px] md:text-xs font-bold text-slate-400 mt-1 uppercase">SISWA AKTIF</p>
    </div>

    {{-- Hadir Hari Ini --}}
    <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border-b-4 border-[#006c49] md:border-b-0 md:border-l-4 relative overflow-hidden group hover:shadow-md transition-all duration-300 flex flex-col">
        <span class="absolute top-4 md:top-5 right-4 md:right-5 text-[10px] font-bold text-[#006c49]/60 uppercase tracking-widest">REAL-TIME</span>
        <div class="bg-[#006c49]/5 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#006c49]/10 transition-colors">
            <svg class="w-6 h-6 text-[#006c49]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-[11px] md:text-sm font-medium text-slate-500 uppercase tracking-wide">Hadir Hari Ini</p>
        <h3 class="text-3xl md:text-4xl font-extrabold text-[#006c49] mt-1">{{ $recap_hari_ini ? $recap_hari_ini['Hadir'] : 0 }}</h3>
        @php
            $rate = $total_siswa > 0 && $recap_hari_ini ? round(($recap_hari_ini['Hadir'] / $total_siswa) * 100, 1) : 0;
        @endphp
        <p class="text-[10px] md:text-xs font-bold text-[#006c49] mt-1 uppercase">{{ $rate }}% RATE</p>
    </div>

    {{-- Sakit/Izin --}}
    <div class="bg-white p-5 md:p-6 rounded-xl shadow-sm border-b-4 border-amber-500 md:border-b-0 md:border-l-4 relative overflow-hidden group hover:shadow-md transition-all duration-300 flex flex-col">
        <span class="absolute top-4 md:top-5 right-4 md:right-5 text-[10px] font-bold text-amber-500/60 uppercase tracking-widest">LAPORAN</span>
        <div class="bg-amber-500/5 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-500/10 transition-colors">
            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-[11px] md:text-sm font-medium text-slate-500 uppercase tracking-wide">Sakit/Izin</p>
        <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mt-1">{{ $recap_hari_ini ? ($recap_hari_ini['Sakit'] + $recap_hari_ini['Izin']) : 0 }}</h3>
        <p class="text-[10px] md:text-xs font-bold text-orange-500 mt-1 uppercase">BUTUH VERIFIKASI</p>
    </div>

</section>

{{-- ── BOTTOM GRID: Siswa Perlu Perhatian + Tindakan Cepat + Analisis ─── --}}
<section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

    {{-- ── Tindakan Cepat (Mobile: tampil duluan via order, Desktop: kanan) ── --}}
    <div class="order-first lg:order-last lg:col-span-5 flex flex-col gap-6">

        {{-- Quick Action Card --}}
        <div class="bg-blue-900 rounded-2xl shadow-lg p-6 md:p-8 text-white relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="flex items-center gap-3 mb-5 md:mb-6">
                <svg class="w-6 h-6 text-[#90a8ff]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                <h2 class="text-lg md:text-xl font-bold">Tindakan Cepat</h2>
            </div>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('wali-kelas.absen.create') }}"
                   class="flex items-center gap-3 md:gap-4 bg-[#264191] hover:bg-[#2e4da7] p-3 md:p-4 rounded-xl transition-all active:scale-95 text-left">
                    <svg class="w-5 h-5 text-[#90a8ff] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="font-semibold text-sm md:text-base">Input Nilai Harian</span>
                </a>
                <button class="flex items-center gap-3 md:gap-4 bg-[#264191] hover:bg-[#2e4da7] p-3 md:p-4 rounded-xl transition-all active:scale-95 text-left">
                    <svg class="w-5 h-5 text-[#90a8ff] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span class="font-semibold text-sm md:text-base">Kirim Broadcast</span>
                </button>
                <a href="{{ route('wali-kelas.recap') }}"
                   class="flex items-center gap-3 md:gap-4 bg-[#264191] hover:bg-[#2e4da7] p-3 md:p-4 rounded-xl transition-all active:scale-95 text-left">
                    <svg class="w-5 h-5 text-[#90a8ff] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span class="font-semibold text-sm md:text-base">Cetak Rekap</span>
                </a>
            </div>
        </div>

        {{-- Analisis Kelas widget --}}
        <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
            <h3 class="text-xs font-medium text-slate-500 uppercase tracking-widest mb-1">Analisis Kelas</h3>
            <div class="flex justify-between items-end mb-4">
                <p class="text-xl md:text-2xl font-extrabold text-[#00236f]">Performa Akademik</p>
                <span class="text-xl md:text-2xl font-extrabold text-[#006c49]">
                    @php
                        $pct = $total_siswa > 0 && $recap_hari_ini
                            ? round(($recap_hari_ini['Hadir'] / $total_siswa) * 100)
                            : 0;
                    @endphp
                    {{ $pct }}%
                </span>
            </div>
            <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden">
                <div class="bg-[#006c49] h-full rounded-full transition-all duration-1000" style="width: {{ $pct }}%"></div>
            </div>
            <div class="mt-4 flex gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-[#006c49]"></div>
                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-tighter">Hadir</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-tighter">Tidak Hadir</span>
                </div>
            </div>
            <p class="mt-4 text-xs text-slate-500 leading-relaxed">
                Tingkat kehadiran siswa kelas {{ $classroom->name }} hari ini sebesar <span class="text-[#006c49] font-bold">{{ $pct }}%</span>.
            </p>
        </div>

    </div>

    {{-- ── Siswa Perlu Perhatian (Desktop: kiri) ─────────────── --}}
    <div class="order-last lg:order-first lg:col-span-7 bg-white rounded-xl shadow-sm p-6 md:p-8">
        <div class="flex justify-between items-start mb-6 md:mb-8">
            <div>
                <h2 class="text-xl font-bold text-[#00236f]">Siswa Perlu Perhatian</h2>
                <p class="text-slate-500 text-sm mt-1">Segera tindak lanjuti ketidakhadiran berikut</p>
            </div>
            <a href="{{ route('wali-kelas.absen.create') }}"
               class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-[#00236f] transition-colors">
                LIHAT SEMUA
            </a>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden">
            @forelse($siswaPerluPerhatian as $absen)
            @php
                $status = $absen->status;
                $nama   = $absen->student->name ?? '-';
                $nis    = $absen->student->nis ?? '-';
                $keterangan = $absen->keterangan;
                $waktu = $absen->updated_at ? $absen->updated_at->format('H:i') : ($absen->created_at ? $absen->created_at->format('H:i') : null);
                
                $badgeClass = match(strtolower($status)) {
                    'sakit' => 'bg-blue-100 text-blue-700 px-3 py-1 rounded-md text-xs font-bold uppercase',
                    'izin'  => 'bg-yellow-100 text-yellow-800 px-3 py-1 rounded-md text-xs font-bold uppercase',
                    default => 'bg-red-100 text-red-700 px-3 py-1 rounded-md text-xs font-bold uppercase',
                };
            @endphp
            <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0">
                <div class="flex flex-col">
                    <span class="font-bold text-gray-800">{{ $nama }}</span>
                    <span class="text-xs text-gray-500">{{ $nis }}</span>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="{{ $badgeClass }}">
                        {{ $status }}
                    </span>
                    @if($keterangan)
                        <div class="text-xs text-gray-500 max-w-[180px] truncate flex items-center justify-end" title="{{ $keterangan }} {{ $waktu ? '• '.$waktu.' WIB' : '' }}">
                            <span class="italic truncate">{{ $keterangan }}</span>
                            @if($waktu)
                                <span class="text-[11px] text-gray-400 ml-1 whitespace-nowrap">&bull; {{ $waktu }} WIB</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="py-10 text-center">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 font-medium">Semua siswa hadir hari ini</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6 pt-4">
            <a href="{{ route('wali-kelas.absen.create') }}"
               class="w-full block text-center text-[#00236f] font-bold text-sm hover:underline decoration-2 underline-offset-4">
                Lihat Semua Riwayat
            </a>
        </div>
    </div>

</section>

{{-- ── DECORATIVE FOOTER BANNER ─────────────────────────────── --}}
<div class="mt-6 w-full h-40 md:h-48 rounded-xl overflow-hidden relative group">
    <div class="absolute inset-0 bg-gradient-to-r from-[#00236f] to-[#1e3a8a] opacity-90"></div>
    <div class="absolute inset-0 p-6 md:p-10 flex flex-col justify-end">
        <h4 class="text-white font-bold text-xl md:text-2xl">Visi Akademik 2024</h4>
        <p class="text-white/70 text-xs md:text-sm max-w-md mt-2 italic">
            "Membangun generasi unggul melalui disiplin digital dan kurikulum yang inklusif."
        </p>
    </div>
</div>

@else

{{-- ── BELUM PUNYA KELAS ────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow-lg p-8 text-center">
    <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-16 h-16 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Kelas yang Ditugaskan</h3>
    <p class="text-gray-600 mb-6 max-w-md mx-auto">
        Anda belum ditugaskan sebagai wali kelas. Silakan hubungi administrator untuk mendapatkan penugasan kelas.
    </p>
    <a href="{{ route('profile.edit') }}"
       class="inline-block px-6 py-3 bg-gradient-to-r from-[#00236f] to-[#1e3a8a] text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
        Lihat Profil
    </a>
</div>

@endif
@endsection
