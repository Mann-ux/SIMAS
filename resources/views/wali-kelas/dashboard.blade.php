@extends('layouts.wali-kelas')

@section('title', 'Dashboard Wali Kelas')

@section('page-title', 'Dashboard Wali Kelas')
@section('page-description', 'Pantau kehadiran siswa dan kelola absensi kelas Anda')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-xl shadow-2xl p-8 mb-8 text-white overflow-hidden relative">
        <!-- Background Pattern -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 🎓</h1>
                    <p class="text-indigo-100 text-lg">Kelola absensi dan pantau kehadiran siswa dengan mudah</p>
                    <div class="mt-4 flex items-center space-x-2">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-semibold">Wali Kelas</span>
                        <span class="text-indigo-100">•</span>
                        <span class="text-indigo-100 text-sm">{{ date('l, d F Y') }}</span>
                    </div>
                </div>
                <div class="w-24 h-24 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($classroom)
        <!-- Info Kelas dan Total Siswa -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <!-- Card Info Kelas -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">KELAS ANDA</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-gray-600 text-sm font-medium">Kelas yang Anda Kelola</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $classroom->name }}</p>
                        <p class="text-xs text-gray-500">Wali kelas aktif</p>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-3 border-t border-indigo-200">
                    <div class="text-indigo-700 text-sm font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status: Aktif
                    </div>
                </div>
            </div>

            <!-- Card Total Siswa -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">SISWA</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-gray-600 text-sm font-medium">Total Siswa di Kelas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $total_siswa }}</p>
                        <p class="text-xs text-gray-500">Siswa terdaftar</p>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-3 border-t border-purple-200">
                    <div class="text-purple-700 text-sm font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Kelola Siswa Anda
                    </div>
                </div>
            </div>

        </div>

        <!-- Rekap Absensi Hari Ini -->
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Rekap Absensi Hari Ini
            </h3>

            @if($recap_hari_ini)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Card Hadir -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Hadir</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $recap_hari_ini['Hadir'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Siswa hadir</p>
                        </div>
                    </div>

                    <!-- Card Izin -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-yellow-500 hover:shadow-lg transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Izin</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $recap_hari_ini['Izin'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Siswa izin</p>
                        </div>
                    </div>

                    <!-- Card Sakit -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Sakit</p>
                            <p class="text-3xl font-bold text-green-600">{{ $recap_hari_ini['Sakit'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Siswa sakit</p>
                        </div>
                    </div>

                    <!-- Card Alpa -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Alpa</p>
                            <p class="text-3xl font-bold text-red-600">{{ $recap_hari_ini['Alpa'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Tanpa keterangan</p>
                        </div>
                    </div>

                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-5 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-yellow-800 mb-1">Belum Ada Data Absensi Hari Ini</p>
                            <p class="text-sm text-yellow-700">Silakan input absensi siswa untuk hari ini terlebih dahulu.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Aksi Cepat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('wali-kelas.absen.create') }}" class="flex items-center p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg hover:from-indigo-100 hover:to-indigo-200 transition-all group">
                    <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Input Absensi Harian</p>
                        <p class="text-xs text-gray-600">Catat kehadiran siswa hari ini</p>
                    </div>
                </a>

                <a href="{{ route('wali-kelas.recap') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all group">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Rekap Bulanan</p>
                        <p class="text-xs text-gray-600">Lihat laporan kehadiran per bulan</p>
                    </div>
                </a>
            </div>
        </div>

    @else
        <!-- Pesan Jika Belum Punya Kelas -->
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-16 h-16 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Kelas yang Ditugaskan</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                Anda belum ditugaskan sebagai wali kelas. Silakan hubungi administrator untuk mendapatkan penugasan kelas.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('profile.edit') }}" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    Lihat Profil
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
