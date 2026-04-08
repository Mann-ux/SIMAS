@extends('layouts.sekretaris')

@section('title', 'Input Absensi Harian')

@section('page-title', 'Input Absensi Harian')
@section('page-description', 'Kelola kehadiran siswa kelas Anda untuk hari ini')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ── Header & Date Picker ── --}}
    <div class="mb-8 flex items-end justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-extrabold font-headline tracking-tight text-blue-900">Input Absensi Harian</h1>
            <div class="flex items-center flex-wrap gap-3 mt-2">
                <p class="text-slate-500 font-medium">{{ \Carbon\Carbon::parse($today ?? now())->locale('id')->isoFormat('dddd, D MMMM YYYY') }} - {{ $classroom->name }}</p>
                
                {{-- Form Date Picker --}}
                <form method="GET" action="{{ route('pengurus.absen.create') }}" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ request('date', $today ?? now()->toDateString()) }}" max="{{ now()->toDateString() }}" class="px-3 py-1.5 text-sm border border-slate-200 rounded-lg focus:ring-blue-900 focus:border-blue-900 text-slate-700 font-medium outline-none">
                    <button type="submit" class="px-4 py-1.5 text-sm font-bold bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition-colors">Ganti Tanggal</button>
                </form>
            </div>
        </div>
    </div>

    @if($students->isNotEmpty())
    {{-- Live Search Bar --}}
    <div class="mb-6 bg-white rounded-xl border border-slate-200 shadow-sm p-2 flex items-center gap-2">
        <svg class="w-5 h-5 text-slate-400 ml-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <input type="text" id="searchInput" placeholder="Cari Nama Siswa..." class="w-full px-2 py-1.5 text-sm outline-none text-slate-700 bg-transparent">
    </div>
    @endif

    {{-- ── Main Card ── --}}
    <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
        
        {{-- Info Alert --}}
        @if($lastUpdate)
            <div class="m-6 mb-2 flex items-start gap-3 bg-blue-50 border border-blue-100 text-blue-800 text-sm rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>
                    Absensi terakhir diperbarui oleh&nbsp;
                    @if($isGuruWali)
                        <strong>Guru Wali</strong>
                    @elseif($isPetugasKelas && $petugasName)
                        <strong>{{ $petugasName }}</strong>
                    @else
                        <strong>Petugas Kelas</strong>
                    @endif
                    &nbsp;pada <strong>{{ $lastUpdate->updated_at->format('H:i') }} WIB</strong>.
                </p>
            </div>
        @else
            <div class="m-6 mb-2 flex items-start gap-3 bg-amber-50 border border-amber-100 text-amber-800 text-sm rounded-xl px-5 py-4">
                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Belum ada data absensi yang diinput. Status default adalah <strong>Hadir</strong>.</p>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('pengurus.absen.store') }}" method="POST" id="attendanceForm">
            @csrf

            <div class="p-6 pt-2">
            @if($students->isEmpty())
                {{-- Empty State --}}
                <div class="text-center py-14 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="font-medium text-gray-500">Belum ada siswa di kelas ini</p>
                    <p class="text-sm mt-1">Silakan tambahkan siswa terlebih dahulu</p>
                </div>
            @else
                {{-- Table --}}
                <div class="overflow-x-auto no-scrollbar rounded-xl border border-slate-200">
                    {{-- Ringkasan Absensi --}}
                    <div class="bg-slate-50 py-3 px-4 border-b border-gray-200 flex justify-start items-center">
                        <div class="text-sm font-medium text-slate-600 flex items-center gap-3">
                            <span>Total Hadir: <span class="font-bold text-emerald-500">{{ $recap['Hadir'] ?? 0 }}</span></span>
                            <span class="text-gray-300">&bull;</span>
                            <span>Izin: <span class="font-bold text-blue-500">{{ $recap['Izin'] ?? 0 }}</span></span>
                            <span class="text-gray-300">&bull;</span>
                            <span>Sakit: <span class="font-bold text-amber-500">{{ $recap['Sakit'] ?? 0 }}</span></span>
                            <span class="text-gray-300">&bull;</span>
                            <span>Alpa: <span class="font-bold text-red-500">{{ $recap['Alpa'] ?? 0 }}</span></span>
                        </div>
                    </div>
                    <table class="w-full border-collapse">
                        <thead class="sticky top-0 z-10 shadow-sm border-b border-gray-300">
                            <tr>
                                <th class="bg-white text-left py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-12">No</th>
                                <th class="bg-white text-left py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Siswa (NIS)</th>
                                <th class="bg-white text-center py-4 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300">
                            @foreach($students as $index => $student)
                            <tr class="student-row hover:bg-gray-50 transition-colors" data-name="{{ strtolower($student->name) }}">
                                <td class="py-4 px-4 text-sm font-medium text-slate-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->nis }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-top">
                                    @php
                                        $attendanceRecord = $attendances[$student->id] ?? null;
                                        $status = $attendanceRecord ? $attendanceRecord->status : 'Hadir';
                                        $ket = $attendanceRecord ? $attendanceRecord->keterangan : '';
                                        $time = $attendanceRecord && $attendanceRecord->updated_at 
                                            ? $attendanceRecord->updated_at->format('H:i') 
                                            : ($attendanceRecord && $attendanceRecord->created_at ? $attendanceRecord->created_at->format('H:i') : '');
                                    @endphp
                                    <div class="flex flex-col w-full"
                                         x-data="{ 
                                            status: '{{ $status }}', 
                                            ket: '{{ addslashes($ket) }}', 
                                            time: '{{ $time }}',
                                            editing: {{ $ket ? 'false' : 'true' }}, 
                                            showKet() { return this.status === 'Sakit' || this.status === 'Izin'; }
                                         }">
                                        <div class="flex justify-center gap-2">
                                            <label class="cursor-pointer">
                                                <input type="radio" x-model="status" @change="if(!showKet()) ket = ''" name="attendances[{{ $student->id }}][status]" value="Hadir" class="hidden peer" required>
                                                <div class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold text-slate-400 transition-all peer-checked:text-white peer-checked:border-emerald-500 peer-checked:bg-emerald-500">H</div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" x-model="status" @change="if(!showKet()) ket = ''" name="attendances[{{ $student->id }}][status]" value="Izin" class="hidden peer">
                                                <div class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold text-slate-400 transition-all peer-checked:text-white peer-checked:border-blue-500 peer-checked:bg-blue-500">I</div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" x-model="status" @change="if(!showKet()) ket = ''" name="attendances[{{ $student->id }}][status]" value="Sakit" class="hidden peer">
                                                <div class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold text-slate-400 transition-all peer-checked:text-white peer-checked:border-amber-500 peer-checked:bg-amber-500">S</div>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" x-model="status" @change="if(!showKet()) ket = ''" name="attendances[{{ $student->id }}][status]" value="Alpa" class="hidden peer">
                                                <div class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-xs font-bold text-slate-400 transition-all peer-checked:text-white peer-checked:border-red-500 peer-checked:bg-red-500">A</div>
                                            </label>
                                        </div>
                                        
                                        <!-- Keterangan Inline Edit -->
                                        <div x-show="showKet()" style="display: none;" class="mt-2 text-center">
                                            
                                            <!-- View Mode (Label) -->
                                            <div x-show="!editing && ket.trim() !== ''" 
                                                 @click="editing = true; $nextTick(() => $refs.ketInput.focus())"
                                                 class="cursor-pointer group flex items-center justify-center py-1 px-3 rounded-md hover:bg-slate-100 transition-colors max-w-[240px] mx-auto">
                                                <span class="text-xs text-slate-500 truncate max-w-full">
                                                    <span class="font-medium text-slate-700 italic" x-text="ket"></span>
                                                    <template x-if="time">
                                                        <span class="text-[11px] text-gray-400 ml-1 whitespace-nowrap" x-text="' &bull; ' + time + ' WIB'"></span>
                                                    </template>
                                                </span>
                                                <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 ml-1.5 transition-opacity shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </div>

                                            <!-- Edit Mode (Input) -->
                                            <div x-show="editing || ket.trim() === ''" class="max-w-[200px] mx-auto relative px-2">
                                                <input type="text" 
                                                       x-ref="ketInput"
                                                       x-model="ket" 
                                                       @blur="if(ket.trim() !== '') { editing = false; }" 
                                                       @keydown.enter.prevent="if(ket.trim() !== '') { editing = false; $el.blur() }"
                                                       name="attendances[{{ $student->id }}][keterangan]" 
                                                       class="w-full text-[11px] border border-gray-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-slate-50 py-1 px-2 placeholder-slate-400 text-center" 
                                                       placeholder="Ketik lalu Enter...">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
            </div>

            @if($students->isNotEmpty())
            {{-- Card Footer --}}
            <div class="bg-slate-50 px-6 py-4 flex items-center justify-end border-t border-slate-200">
                <div class="flex items-center gap-3">
                    <a href="{{ route('pengurus.dashboard') }}" class="px-5 py-2 text-sm font-bold text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                        Kembali
                    </a>
                    <button type="submit" class="text-white px-6 py-2 rounded-lg font-bold text-sm shadow-sm transition-all active:scale-95 bg-blue-900 hover:bg-blue-800">
                        Simpan Absen
                    </button>
                </div>
            </div>
            @endif
        </form>
    </div>

    {{-- Bottom Guidelines Info --}}
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-5 rounded-xl border border-slate-200 shadow-sm flex gap-4 bg-blue-900">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 bg-blue-800">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Butuh Bantuan?</h3>
                <p class="text-xs mt-1 leading-relaxed text-blue-100">Hubungi Operator Sekolah jika terdapat kesalahan data siswa atau NIS.</p>
            </div>
        </div>
        <div class="p-5 rounded-xl border border-slate-200 shadow-sm flex gap-4 bg-blue-900">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 bg-blue-800">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Verifikasi Data</h3>
                <p class="text-xs mt-1 leading-relaxed text-blue-100">Pastikan status kehadiran telah diisi dengan benar sebelum menyimpan absensi harian.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#10B981',
        timer: 3000,
        buttonsStyling: false,
        customClass: {
            popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
            title: 'text-2xl font-bold text-gray-800',
            htmlContainer: 'text-base text-gray-500 mt-2',
            confirmButton: 'mt-4 bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
            cancelButton: 'mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95 ml-2'
        }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#F43F5E',
        buttonsStyling: false,
        customClass: {
            popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
            title: 'text-2xl font-bold text-gray-800',
            htmlContainer: 'text-base text-gray-500 mt-2',
            confirmButton: 'mt-4 bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
            cancelButton: 'mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95 ml-2'
        }
    });
    @endif

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
                buttonsStyling: false,
                customClass: {
                    popup: 'bg-white rounded-2xl shadow-2xl border border-gray-100',
                    title: 'text-2xl font-bold text-gray-800',
                    htmlContainer: 'text-base text-gray-500 mt-2',
                    confirmButton: 'mt-4 bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95',
                    cancelButton: 'mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-8 rounded-xl transition-colors active:scale-95 ml-2'
                }
            });
        }
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.student-row').forEach(row => {
                const name = row.getAttribute('data-name');
                if (name && name.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

</script>
@endpush
@endsection
