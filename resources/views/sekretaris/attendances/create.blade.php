@extends('layouts.sekretaris')

@section('title', 'Absensi Harian Sekretaris')

@section('page-title', 'Input Absensi Harian')
@section('page-description', 'Kelola kehadiran siswa kelas Anda untuk hari ini')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Absensi Harian Sekretaris</h2>
            <p class="text-gray-600 mt-1">Input kehadiran siswa untuk hari ini - {{ date('d F Y', strtotime($today)) }}</p>
        </div>

        <!-- Info Card -->
        <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl shadow-lg p-6 mb-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $classroom->name }}</h3>
                        <p class="text-cyan-100 text-sm">Total Siswa: {{ $students->count() }} orang</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">{{ date('d') }}</div>
                    <div class="text-sm text-cyan-100">{{ date('F Y') }}</div>
                    <div class="text-xs text-cyan-200 mt-1">{{ date('l') }}</div>
                </div>
            </div>
        </div>

        <!-- Alert Info: Default Hadir -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Info:</strong> Status default untuk siswa yang belum diabsen adalah <strong>"Hadir"</strong>. Anda hanya bisa input absensi untuk <strong>hari ini</strong>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Rekap Kehadiran Hari Ini -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Card Hadir (Biru) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Hadir</p>
                            <p class="text-4xl font-bold text-blue-600">{{ $recap['Hadir'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">Siswa hadir</span>
                    </div>
                </div>
            </div>

            <!-- Card Izin (Kuning) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-yellow-500 hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Izin</p>
                            <p class="text-4xl font-bold text-yellow-600">{{ $recap['Izin'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">Siswa izin</span>
                    </div>
                </div>
            </div>

            <!-- Card Sakit (Hijau) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Sakit</p>
                            <p class="text-4xl font-bold text-green-600">{{ $recap['Sakit'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">Siswa sakit</span>
                    </div>
                </div>
            </div>

            <!-- Card Alpa (Merah) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 border-red-500 hover:shadow-lg transition-shadow">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Alpa</p>
                            <p class="text-4xl font-bold text-red-600">{{ $recap['Alpa'] }}</p>
                        </div>
                        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">Tanpa keterangan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Absensi Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h4 class="font-bold text-gray-800 text-lg">Daftar Kehadiran Siswa</h4>
                <p class="text-sm text-gray-600">Pilih status kehadiran untuk setiap siswa (Default: Hadir)</p>
            </div>

            <!-- Form -->
            <form action="{{ route('sekretaris.absen.store') }}" method="POST" id="attendanceForm">
                @csrf

                <div class="p-6">
                    <!-- Last Updated Info Alert -->
                    @if($lastUpdate)
                        <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">
                                        <strong>ℹ️ Info:</strong> Absensi terakhir diperbarui oleh 
                                        <span class="font-semibold">{{ $lastUpdate->recorder->name ?? 'Sistem' }}</span> 
                                        pada <span class="font-semibold">{{ $lastUpdate->updated_at->format('H:i') }} WIB</span>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-800">
                                        <strong>ℹ️ Info:</strong> Belum ada data absensi yang diinput untuk hari ini. Status default adalah Hadir.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($students->isEmpty())
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada siswa di kelas ini</p>
                            <p class="text-gray-400 text-sm mt-1">Silakan tambahkan siswa terlebih dahulu</p>
                        </div>
                    @else
                        <!-- Table Absensi -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-16">
                                            No
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-32">
                                            NIS
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Nama Siswa
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider w-24">
                                            Hadir
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-yellow-700 uppercase tracking-wider w-24">
                                            Izin
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-green-700 uppercase tracking-wider w-24">
                                            Sakit
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider w-24">
                                            Alpa
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($students as $index => $student)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                            {{ $student->nis }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $student->name }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <input 
                                                type="radio" 
                                                name="attendances[{{ $student->nis }}][status]" 
                                                value="Hadir" 
                                                class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer"
                                                {{ isset($attendances[$student->nis]) && $attendances[$student->nis]->status === 'Hadir' ? 'checked' : (!isset($attendances[$student->nis]) ? 'checked' : '') }}
                                                required
                                            >
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <input 
                                                type="radio" 
                                                name="attendances[{{ $student->nis }}][status]" 
                                                value="Izin" 
                                                class="w-5 h-5 text-yellow-600 focus:ring-2 focus:ring-yellow-500 cursor-pointer"
                                                {{ isset($attendances[$student->nis]) && $attendances[$student->nis]->status === 'Izin' ? 'checked' : '' }}
                                            >
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <input 
                                                type="radio" 
                                                name="attendances[{{ $student->nis }}][status]" 
                                                value="Sakit" 
                                                class="w-5 h-5 text-green-600 focus:ring-2 focus:ring-green-500 cursor-pointer"
                                                {{ isset($attendances[$student->nis]) && $attendances[$student->nis]->status === 'Sakit' ? 'checked' : '' }}
                                            >
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <input 
                                                type="radio" 
                                                name="attendances[{{ $student->nis }}][status]" 
                                                value="Alpa" 
                                                class="w-5 h-5 text-red-600 focus:ring-2 focus:ring-red-500 cursor-pointer"
                                                {{ isset($attendances[$student->nis]) && $attendances[$student->nis]->status === 'Alpa' ? 'checked' : '' }}
                                            >
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a 
                                href="{{ route('sekretaris.dashboard') }}"
                                class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                                Kembali
                            </a>
                            <button 
                                type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Absensi
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Success Alert ketika ada session success
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#0891B2',
        timer: 3000,
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6 py-2'
        }
    });
    @endif

    // Error Alert ketika ada session error
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#EF4444',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6 py-2'
        }
    });
    @endif

    // Validasi form sebelum submit
    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        const radios = document.querySelectorAll('input[type="radio"]:checked');
        const totalStudents = {{ $students->count() }};
        
        if (radios.length !== totalStudents && totalStudents > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Mohon isi status kehadiran untuk semua siswa!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#F59E0B',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-6 py-2'
                }
            });
        }
    });
</script>
@endpush
@endsection
