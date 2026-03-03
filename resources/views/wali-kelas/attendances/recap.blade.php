@extends('layouts.wali-kelas')

@section('title', 'Rekap Absensi Bulanan')

@section('page-title', 'Rekapitulasi Absensi Bulanan')
@section('page-description', 'Laporan kehadiran siswa per bulan')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Filter Card -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <!-- Form Filter -->
            <form method="GET" action="{{ route('wali-kelas.recap') }}" class="flex items-end gap-4 flex-wrap flex-1">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                    <select name="month" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="01" {{ $month == '01' ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ $month == '02' ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ $month == '03' ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ $month == '04' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ $month == '05' ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ $month == '06' ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ $month == '07' ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ $month == '08' ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ $month == '09' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ $month == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ $month == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ $month == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                    <select name="year" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Tampilkan
                    </span>
                </button>
            </form>

            <!-- Tombol Export Excel -->
            <a href="{{ route('wali-kelas.recap.export', ['month' => $month, 'year' => $year]) }}" class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </span>
            </a>

            <!-- Tombol Cetak -->
            <button onclick="window.print()" class="px-6 py-2 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Laporan
                </span>
            </button>
        </div>
    </div>

    <!-- Area yang akan dicetak -->
    <div id="print-area">
        <!-- Header Laporan (hanya muncul saat print) -->
        <div class="print-header hidden print:block mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900">REKAPITULASI ABSENSI BULANAN</h1>
            <h2 class="text-xl font-semibold text-gray-700 mt-2">{{ $classroom->name }}</h2>
            <p class="text-gray-600 mt-1">
                Periode: 
                @php
                    $monthNames = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                @endphp
                {{ $monthNames[$month] }} {{ $year }}
            </p>
            <hr class="my-4 border-gray-300">
        </div>

        <!-- Info Card (tidak muncul saat print) -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white print:hidden">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $classroom->name }}</h3>
                        <p class="text-indigo-100 text-sm">Total Siswa: {{ $students->count() }} orang</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-indigo-100">Periode Rekap</div>
                    <div class="text-2xl font-bold">{{ $monthNames[$month] }}</div>
                    <div class="text-lg text-indigo-100">{{ $year }}</div>
                </div>
            </div>
        </div>

        <!-- Tabel Rekap -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 print:bg-white">
                <h4 class="font-bold text-gray-800 text-lg">Data Rekapitulasi Kehadiran</h4>
                <p class="text-sm text-gray-600">Rincian kehadiran siswa selama periode terpilih</p>
            </div>

            <div class="overflow-x-auto">
                @if($students->isEmpty())
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p class="text-gray-500 font-medium">Belum ada data siswa</p>
                        <p class="text-gray-400 text-sm mt-1">Silakan tambahkan siswa terlebih dahulu</p>
                    </div>
                @else
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50">
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-16">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIS</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider w-24">Hadir</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-yellow-700 uppercase tracking-wider w-24">Izin</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-green-700 uppercase tracking-wider w-24">Sakit</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider w-24">Alpa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($students as $index => $student)
                            @php
                                // Hitung total masing-masing status
                                $totalHadir = $student->attendances->where('status', 'Hadir')->count();
                                $totalIzin = $student->attendances->where('status', 'Izin')->count();
                                $totalSakit = $student->attendances->where('status', 'Sakit')->count();
                                $totalAlpa = $student->attendances->where('status', 'Alpa')->count();
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors print:hover:bg-white">
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900 font-medium">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $student->nis }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center print:hidden">
                                            <span class="text-white font-bold text-xs">{{ substr($student->name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-3 print:ml-0">
                                            <div class="text-sm font-semibold text-gray-900">{{ $student->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-700 font-bold rounded-lg text-lg print:bg-transparent print:w-auto print:h-auto">
                                        {{ $totalHadir }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 text-yellow-700 font-bold rounded-lg text-lg print:bg-transparent print:w-auto print:h-auto">
                                        {{ $totalIzin }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-12 h-12 bg-green-100 text-green-700 font-bold rounded-lg text-lg print:bg-transparent print:w-auto print:h-auto">
                                        {{ $totalSakit }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-12 h-12 bg-red-100 text-red-700 font-bold rounded-lg text-lg print:bg-transparent print:w-auto print:h-auto">
                                        {{ $totalAlpa }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-right font-bold text-gray-900 uppercase">Total:</td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center font-bold text-blue-700 text-lg">
                                        {{ $students->sum(function($s) { return $s->attendances->where('status', 'Hadir')->count(); }) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center font-bold text-yellow-700 text-lg">
                                        {{ $students->sum(function($s) { return $s->attendances->where('status', 'Izin')->count(); }) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center font-bold text-green-700 text-lg">
                                        {{ $students->sum(function($s) { return $s->attendances->where('status', 'Sakit')->count(); }) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center font-bold text-red-700 text-lg">
                                        {{ $students->sum(function($s) { return $s->attendances->where('status', 'Alpa')->count(); }) }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
        </div>

        <!-- Footer Print (hanya muncul saat print) -->
        <div class="hidden print:block mt-12">
            <div class="flex justify-between items-end">
                <div class="text-sm text-gray-600">
                    <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-16">Wali Kelas,</p>
                    <p class="font-bold text-gray-900 border-t border-gray-900 pt-1">{{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #print-area, #print-area * {
            visibility: visible;
        }
        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 20px;
        }
        /* Tambahan styling untuk print */
        .print\:block {
            display: block !important;
        }
        .print\:hidden {
            display: none !important;
        }
        table {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    }
</style>

@push('scripts')
<script>
    // Success Alert jika ada session success
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#4F46E5',
        timer: 3000,
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6 py-2'
        }
    });
    @endif

    // Error Alert jika ada session error
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
</script>
@endpush
@endsection
