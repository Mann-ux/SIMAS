@extends('layouts.sekretaris')

@section('title', 'Dashboard Pengurus')

@section('page-title', 'Dashboard Pengurus')
@section('page-description', 'Ringkasan kelas dan akses cepat input absensi harian')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Card -->
    <div class="mb-6 bg-gradient-to-r from-cyan-600 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold">Selamat Datang, {{ auth()->user()->name }}</h2>
                <p class="text-cyan-100 mt-2">Semoga harimu produktif! Yuk pastikan absensi kelas hari ini tercatat dengan rapi.</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Card Kelas -->
        <div class="bg-white rounded-xl shadow-md border-l-4 border-cyan-500 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Nama Kelas</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $classroom?->name ?? 'Belum ada kelas' }}
                    </p>
                    @if($classroom)
                    <p class="text-xs text-gray-500 mt-1">Tahun Ajaran {{ $classroom->academicYear?->year ?? '-' }}</p>
                    @endif
                </div>
                <div class="w-14 h-14 bg-cyan-100 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Total Siswa -->
        <div class="bg-white rounded-xl shadow-md border-l-4 border-blue-500 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Siswa</p>
                    <p class="text-4xl font-bold text-blue-600">{{ $total_siswa }}</p>
                    <p class="text-xs text-gray-500 mt-1">Siswa terdaftar di kelas ini</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a3 3 0 00-5.356-1.857M17 20H7m10 0v-1c0-.656-.126-1.283-.356-1.857M7 20H2v-1a3 3 0 015.356-1.857M7 20v-1c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekap Absensi Hari Ini -->
    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Rekap Absensi Hari Ini</h3>
        <p class="text-sm text-gray-600 mb-4">{{ \Carbon\Carbon::parse($today ?? now())->isoFormat('dddd, D MMMM YYYY') }}</p>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Card Hadir -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-blue-600">{{ $recap_hari_ini['Hadir'] ?? 0 }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Hadir</p>
                    <p class="text-xs text-gray-500">Siswa hadir</p>
                </div>
            </div>

            <!-- Card Izin -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-yellow-500 hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-yellow-600">{{ $recap_hari_ini['Izin'] ?? 0 }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Izin</p>
                    <p class="text-xs text-gray-500">Siswa izin</p>
                </div>
            </div>

            <!-- Card Sakit -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-green-600">{{ $recap_hari_ini['Sakit'] ?? 0 }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Sakit</p>
                    <p class="text-xs text-gray-500">Siswa sakit</p>
                </div>
            </div>

            <!-- Card Alpa -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-red-600">{{ $recap_hari_ini['Alpa'] ?? 0 }}</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Alpa</p>
                    <p class="text-xs text-gray-500">Tanpa keterangan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 text-cyan-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Aksi Cepat
                </h3>
                <p class="text-sm text-gray-600 mt-1">Lanjutkan ke form input absensi harian untuk kelas Anda.</p>
            </div>
            <a href="{{ route('pengurus.absen.create') }}"
               class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold rounded-lg shadow-lg hover:from-cyan-600 hover:to-blue-700 hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Input Absensi Harian
            </a>
        </div>
    </div>
</div>
@endsection
