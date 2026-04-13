@extends('layouts.sekretaris')

@section('title', 'Rekap Absensi Bulanan')

@section('page-title', 'Rekapitulasi Absensi Bulanan')
@section('page-description', 'Laporan kehadiran siswa per bulan')

@section('content')
<div class="w-full pb-28 md:pb-8">

    @php
        $monthNames = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',    '04' => 'April',
            '05' => 'Mei',     '06' => 'Juni',      '07' => 'Juli',     '08' => 'Agustus',
            '09' => 'September','10' => 'Oktober',  '11' => 'November', '12' => 'Desember'
        ];

        // Stats Calculation
        $totalSakitIzin = $students->sum(fn($s) => $s->attendances->whereIn('status', ['Sakit', 'Izin'])->count());
        $allAttendancesCount = $students->sum(fn($s) => $s->attendances->count());
        $totalHadirOverall = $students->sum(fn($s) => $s->attendances->where('status', 'Hadir')->count());
        $attendancePercentage = $allAttendancesCount > 0 ? round(($totalHadirOverall / $allAttendancesCount) * 100, 1) : 0;
        
        $statusKelas = 'Baik';
        $statusColor = 'bg-[#4edea3]';
        if ($attendancePercentage >= 90) { $statusKelas = 'Sangat Baik'; }
        elseif ($attendancePercentage >= 75) { $statusKelas = 'Cukup'; $statusColor = 'bg-amber-400'; }
        else { $statusKelas = 'Perlu Perhatian'; $statusColor = 'bg-rose-500'; }
    @endphp

    <header class="mb-12">
        <div class="max-w-2xl">
            <div class="flex items-center gap-2 mb-4 print:hidden">
                <span class="w-10 h-[3px] bg-blue-900 rounded-full"></span>
                <span class="text-xs font-bold text-gray-400 tracking-[0.2em] uppercase">LAPORAN AKADEMIK</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-blue-900 tracking-tight mb-4 print:hidden">Rekapitulasi Absensi</h1>
            <p class="text-sm md:text-base text-gray-500 max-w-2xl leading-relaxed print:hidden">
                Ringkasan kehadiran siswa bulanan untuk memantau kedisiplinan dan partisipasi akademik di Kelas {{ $classroom->name }} SMAN 1 Kembang.
            </p>
        </div>

        <div class="mt-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 print:hidden">
            <form method="GET" action="{{ route('pengurus.recap') }}" class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <select name="month" onchange="this.form.submit()" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium text-gray-700 flex items-center gap-2 w-full focus:ring-2 focus:ring-[#00236f]/20 transition-all cursor-pointer">
                            @foreach($monthNames as $m => $name)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                    <div class="relative">
                        <select name="year" onchange="this.form.submit()" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium text-gray-700 flex items-center gap-2 w-full focus:ring-2 focus:ring-[#00236f]/20 transition-all cursor-pointer">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </form>

            <div class="flex flex-wrap items-center gap-3 lg:gap-4 md:ml-auto">
                <a href="{{ route('pengurus.recap.export', ['month' => $month, 'year' => $year]) }}" class="bg-blue-900 hover:bg-blue-800 text-white rounded-lg px-4 py-2.5 text-sm font-medium flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Download CSV
                </a>
                <button onclick="window.print()" class="hidden md:inline-flex print:hidden bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 rounded-lg px-4 py-2.5 text-sm font-medium items-center gap-2 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak
                </button>
            </div>
        </div>
    </header>

    <div id="print-area">
        <div class="print-header hidden print:block mb-6 text-center">
            <h1 class="text-xl font-bold text-gray-900">REKAPITULASI ABSENSI BULANAN</h1>
            <h2 class="text-base font-semibold text-gray-700 mt-1">{{ $classroom->name }} · SMAN 1 Kembang</h2>
            <p class="text-sm text-gray-500 mt-1">Periode: {{ $monthNames[$month] }} {{ $year }}</p>
            <hr class="my-4 border-gray-300">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 print:hidden">
            <div class="bg-white p-8 rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between border border-gray-100">
                <span class="text-[#444651] font-label text-sm uppercase tracking-wider">Rata-rata Kehadiran</span>
                <div class="mt-4">
                    <span class="text-4xl font-bold font-headline text-[#00236f]">{{ $attendancePercentage }}%</span>
                    <p class="text-[#006c49] text-sm font-medium mt-1 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ $totalHadirOverall }} Kehadiran Total
                    </p>
                </div>
            </div>
            
            <div class="bg-white p-8 rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between border border-gray-100">
                <span class="text-[#444651] font-label text-sm uppercase tracking-wider">Total Ketidakhadiran</span>
                <div class="mt-4">
                    <span class="text-4xl font-bold font-headline text-[#4b1c00]">{{ $totalSakitIzin }} Hari</span>
                    <p class="text-[#444651] text-sm mt-1">Sakit & Izin terakumulasi</p>
                </div>
            </div>
            
            <div class="bg-white p-8 rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between relative overflow-hidden group border border-gray-100">
                <div class="relative z-10">
                    <span class="text-[#444651] font-label text-sm uppercase tracking-wider">Status Kelas</span>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full {{ $statusColor }}"></div>
                        <span class="text-2xl font-bold font-headline text-[#191c1e]">{{ $statusKelas }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden md:block print:block bg-white rounded-xl overflow-hidden shadow-[0_12px_40px_rgba(0,35,111,0.04)] border border-gray-100">
            <div class="overflow-x-auto">
                @if($students->isEmpty())
                <div class="text-center py-14 text-gray-400">
                    <p class="font-medium text-gray-500 font-headline">Belum ada data siswa</p>
                </div>
                @else
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-[#f3f4f6]/50 print:bg-gray-100 border-b border-[#edeef0]">
                            <th class="px-8 py-6 text-xs font-bold uppercase tracking-widest text-[#444651] font-label">Nama Siswa</th>
                            <th class="px-6 py-6 text-center text-xs font-bold uppercase tracking-widest text-[#444651] font-label">H</th>
                            <th class="px-6 py-6 text-center text-xs font-bold uppercase tracking-widest text-[#444651] font-label">I</th>
                            <th class="px-6 py-6 text-center text-xs font-bold uppercase tracking-widest text-[#444651] font-label">S</th>
                            <th class="px-6 py-6 text-center text-xs font-bold uppercase tracking-widest text-[#444651] font-label">A</th>
                            <th class="px-8 py-6 text-right text-xs font-bold uppercase tracking-widest text-[#444651] font-label print:hidden">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#edeef0]">
                        @foreach($students as $index => $student)
                        @php
                            $totalHadir = $student->attendances->where('status', 'Hadir')->count();
                            $totalIzin  = $student->attendances->where('status', 'Izin')->count();
                            $totalSakit = $student->attendances->where('status', 'Sakit')->count();
                            $totalAlpa  = $student->attendances->where('status', 'Alpa')->count();
                            $studentTotal = $totalHadir + $totalSakit + $totalIzin + $totalAlpa;
                            $studentPercentage = $studentTotal > 0 ? round(($totalHadir / $studentTotal) * 100) : 0;
                            $percentageColor = $studentPercentage < 75 ? 'bg-[#ffdbcb]' : ($studentPercentage < 90 ? 'bg-[#00236f]' : 'bg-[#4edea3]');
                            $textColor = $studentPercentage < 75 ? 'text-[#4b1c00]' : ($studentPercentage < 90 ? 'text-[#00236f]' : 'text-[#006c49]');
                        @endphp
                        <tr class="hover:bg-[#f3f4f6]/50 transition-colors group print:hover:bg-transparent">
                            <td class="px-8 py-6">
                                <p class="font-bold text-[#191c1e] text-sm md:text-base print:text-black">{{ $student->name }}</p>
                                <p class="text-xs text-[#444651]">NIS: {{ $student->nis }}</p>
                            </td>
                            <td class="px-6 py-6 text-center"><span class="font-bold text-[#00714d]">{{ $totalHadir ?: 0 }}</span></td>
                            <td class="px-6 py-6 text-center"><span class="font-bold text-[#00236f]">{{ $totalIzin ?: 0 }}</span></td>
                            <td class="px-6 py-6 text-center"><span class="font-bold text-amber-800">{{ $totalSakit ?: 0 }}</span></td>
                            <td class="px-6 py-6 text-center"><span class="font-bold text-rose-800">{{ $totalAlpa ?: 0 }}</span></td>
                            <td class="px-8 py-6 text-right print:hidden">
                                <span class="text-xl font-extrabold font-headline {{ $textColor }}">{{ $studentPercentage }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            <div class="bg-[#f8f9fb] px-8 py-4 flex items-center justify-between border-t border-[#edeef0] print:hidden">
                <span class="text-sm text-[#444651] font-label italic">Data: {{ date('d F Y, H:i') }} WIB</span>
            </div>
        </div>

        <div class="md:hidden flex flex-col gap-2 print:hidden">
            @if($students->isEmpty())
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 text-center text-gray-500">
                <p class="font-medium text-gray-500 font-headline">Belum ada data siswa</p>
            </div>
            @else
            @foreach($students as $student)
            @php
                $totalHadir = $student->attendances->where('status', 'Hadir')->count();
                $totalIzin  = $student->attendances->where('status', 'Izin')->count();
                $totalSakit = $student->attendances->where('status', 'Sakit')->count();
                $totalAlpa  = $student->attendances->where('status', 'Alpa')->count();
                $studentTotal = $totalHadir + $totalSakit + $totalIzin + $totalAlpa;
                $studentPercentage = $studentTotal > 0 ? round(($totalHadir / $studentTotal) * 100) : 0;
            @endphp
            <div x-data="{ open: false }" class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div @click="open = !open" class="p-4 flex justify-between items-center cursor-pointer hover:bg-slate-50 transition-colors">
                    <p class="text-sm font-bold text-gray-800">{{ $student->name }}</p>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-blue-900">{{ $studentPercentage }}%</span>
                        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div x-show="open" x-collapse x-cloak class="p-4 bg-slate-50 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-3">NIS: {{ $student->nis }}</p>
                    <div class="grid grid-cols-4 gap-2 text-center text-xs">
                        <div class="rounded-lg bg-emerald-100 px-2 py-2">
                            <p class="font-medium text-emerald-700">H</p>
                            <p class="mt-1 text-sm font-bold text-emerald-800">{{ $totalHadir ?: 0 }}</p>
                        </div>
                        <div class="rounded-lg bg-amber-100 px-2 py-2">
                            <p class="font-medium text-amber-700">S</p>
                            <p class="mt-1 text-sm font-bold text-amber-800">{{ $totalSakit ?: 0 }}</p>
                        </div>
                        <div class="rounded-lg bg-blue-100 px-2 py-2">
                            <p class="font-medium text-blue-700">I</p>
                            <p class="mt-1 text-sm font-bold text-blue-800">{{ $totalIzin ?: 0 }}</p>
                        </div>
                        <div class="rounded-lg bg-rose-100 px-2 py-2">
                            <p class="font-medium text-rose-700">A</p>
                            <p class="mt-1 text-sm font-bold text-rose-800">{{ $totalAlpa ?: 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        <div class="hidden print:block mt-10">
            <div class="flex justify-between items-end">
                <div class="text-sm text-gray-600"><p>Dicetak pada: {{ date('d F Y, H:i') }}</p></div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-16">Pengurus Kelas,</p>
                    <p class="font-bold text-gray-900 border-t border-gray-900 pt-1">{{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }

    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area { position: absolute; left: 0; top: 0; width: 100%; padding: 20px;}
        .print\:block { display: block !important; }
        .print\:hidden { display: none !important; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th:first-child, td:first-child { text-align: left; }
    }
</style>
@endsection
