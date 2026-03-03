@extends('layouts.admin')

@section('title', 'Command Center Admin')

@section('page-title', 'Command Center Absensi Harian')
@section('page-description', 'Pantau progres absensi semua kelas secara real-time')

@section('content')
<div class="max-w-7xl mx-auto" id="admin-command-center">

    <!-- Hero -->
    <div class="bg-gradient-to-r from-slate-800 via-indigo-800 to-blue-700 rounded-xl shadow-2xl p-8 mb-8 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">Command Center Absensi 📡</h1>
                <p class="text-indigo-100">Tanggal monitoring: {{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="px-4 py-2 rounded-lg bg-white/15 text-sm font-semibold backdrop-blur-sm">
                Admin: {{ auth()->user()->name }}
            </div>
        </div>
    </div>

    <!-- Section 1: Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-indigo-500 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase">Total Siswa</p>
            <p class="text-4xl font-bold text-indigo-600 mt-2">{{ $total_siswa }}</p>
            <p class="text-xs text-gray-500 mt-1">Seluruh siswa terdaftar</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-emerald-500 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase">Hadir</p>
            <p class="text-4xl font-bold text-emerald-600 mt-2">{{ $hadir }}</p>
            <p class="text-xs text-gray-500 mt-1">Siswa hadir hari ini</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-amber-500 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase">Izin / Sakit</p>
            <p class="text-4xl font-bold text-amber-600 mt-2">{{ $izin_sakit }}</p>
            <p class="text-xs text-gray-500 mt-1">Akumulasi izin & sakit</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-rose-500 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase">Alpa</p>
            <p class="text-4xl font-bold text-rose-600 mt-2">{{ $alpa }}</p>
            <p class="text-xs text-gray-500 mt-1">Perlu atensi lebih lanjut</p>
        </div>
    </div>

    <!-- Section 2: Urgent Alert -->
    <div class="mb-8">
        @if($kelas_belum_absen->isNotEmpty())
            <div class="bg-red-50 border-2 border-red-300 rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-red-700">🚨 Kelas Belum Absen Hari Ini</h2>
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full">
                        {{ $kelas_belum_absen->count() }} kelas
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-red-100 text-red-800">
                                <th class="px-4 py-3 text-left font-semibold">Nama Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold">Wali Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelas_belum_absen as $kelas)
                                <tr class="border-b border-red-200">
                                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $kelas->name }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $kelas->user?->name ?? 'Belum ditentukan' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-emerald-50 border-2 border-emerald-300 rounded-xl shadow p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-emerald-700">✅ Semua kelas sudah absen hari ini!</h2>
                    <p class="text-emerald-600 text-sm mt-1">Mantap, monitoring harian aman terkendali.</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        @endif
    </div>

    <!-- Section 3: Daftar Kelas by Tab -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <h2 class="text-xl font-bold text-gray-900">Daftar Kelas per Tingkatan</h2>
            <div class="inline-flex rounded-lg border border-gray-200 p-1 bg-gray-50" id="grade-tabs">
                <button type="button" data-tab="X" class="tab-btn px-4 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white">X</button>
                <button type="button" data-tab="XI" class="tab-btn px-4 py-2 text-sm font-semibold rounded-md text-gray-700">XI</button>
                <button type="button" data-tab="XII" class="tab-btn px-4 py-2 text-sm font-semibold rounded-md text-gray-700">XII</button>
            </div>
        </div>

        @foreach(['X', 'XI', 'XII'] as $grade)
            <div class="tab-panel {{ $grade === 'X' ? '' : 'hidden' }}" data-panel="{{ $grade }}">
                @if(($classrooms_by_grade[$grade] ?? collect())->isEmpty())
                    <div class="text-center py-8 bg-gray-50 rounded-lg text-gray-500">
                        Belum ada data kelas tingkat {{ $grade }}.
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($classrooms_by_grade[$grade] as $classroom)
                            @php
                                $attendanceToday = $classroom->students->flatMap(fn($student) => $student->attendances);
                                $hadirKelas = $attendanceToday->where('status', 'Hadir')->count();
                                $izinKelas = $attendanceToday->where('status', 'Izin')->count();
                                $sakitKelas = $attendanceToday->where('status', 'Sakit')->count();
                                $alpaKelas = $attendanceToday->where('status', 'Alpa')->count();
                            @endphp

                            <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $classroom->name }}</h3>
                                        <p class="text-sm text-gray-600">Wali Kelas: {{ $classroom->user?->name ?? 'Belum ditentukan' }}</p>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded bg-indigo-100 text-indigo-700">
                                        {{ $classroom->students->count() }} siswa
                                    </span>
                                </div>

                                <div class="grid grid-cols-4 gap-2 text-center text-sm">
                                    <div class="bg-emerald-50 rounded-lg py-2">
                                        <p class="text-emerald-700 font-bold">{{ $hadirKelas }}</p>
                                        <p class="text-gray-500 text-xs">Hadir</p>
                                    </div>
                                    <div class="bg-amber-50 rounded-lg py-2">
                                        <p class="text-amber-700 font-bold">{{ $izinKelas }}</p>
                                        <p class="text-gray-500 text-xs">Izin</p>
                                    </div>
                                    <div class="bg-yellow-50 rounded-lg py-2">
                                        <p class="text-yellow-700 font-bold">{{ $sakitKelas }}</p>
                                        <p class="text-gray-500 text-xs">Sakit</p>
                                    </div>
                                    <div class="bg-rose-50 rounded-lg py-2">
                                        <p class="text-rose-700 font-bold">{{ $alpaKelas }}</p>
                                        <p class="text-gray-500 text-xs">Alpa</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const buttons = document.querySelectorAll('#grade-tabs .tab-btn');
        const panels = document.querySelectorAll('#admin-command-center .tab-panel');

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-tab');

                buttons.forEach((btn) => {
                    btn.classList.remove('bg-indigo-600', 'text-white');
                    btn.classList.add('text-gray-700');
                });

                button.classList.add('bg-indigo-600', 'text-white');
                button.classList.remove('text-gray-700');

                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.getAttribute('data-panel') !== target);
                });
            });
        });
    })();
</script>
@endpush
