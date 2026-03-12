@extends('layouts.admin')

@section('title', 'Kelola Kelas')

@section('page-title', 'Kelola Kelas')
@section('page-description', 'Manajemen data kelas dan wali kelas')

@section('content')
<div class="space-y-6" x-data="{ filter: 'semua', deleteId: null }">
    
    <!-- Header Section -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Kelola Kelas</h2>
                <p class="text-sm text-gray-600 mt-1">Total: <span class="font-semibold text-blue-600">{{ $classrooms->count() }}</span> kelas</p>
            </div>
        </div>
        
        <a href="{{ route('classrooms.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kelas Baru
        </a>
    </div>

     <!-- Filter & Grid Section -->
    <div x-data="{ filter: 'semua' }" class="space-y-6">
        
        <!-- Segmented Control Filter -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Filter by Tingkat</h3>
                    <p class="text-sm text-gray-600">Pilih tingkat kelas untuk ditampilkan</p>
                </div>
                
                <!-- Toggle Pill / Segmented Control -->
                <div class="inline-flex bg-gray-100 p-1.5 rounded-full gap-1">
                    <button 
                        @click="filter = 'semua'" 
                        :class="filter === 'semua' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-200'"
                        class="px-7 py-2.5 rounded-full font-semibold transition-all duration-200">
                        Semua
                    </button>
                    <button 
                        @click="filter = 'X'" 
                        :class="filter === 'X' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-200'"
                        class="px-7 py-2.5 rounded-full font-semibold transition-all duration-200">
                        X
                    </button>
                    <button 
                        @click="filter = 'XI'" 
                        :class="filter === 'XI' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-200'"
                        class="px-7 py-2.5 rounded-full font-semibold transition-all duration-200">
                        XI
                    </button>
                    <button 
                        @click="filter = 'XII'" 
                        :class="filter === 'XII' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-200'"
                        class="px-7 py-2.5 rounded-full font-semibold transition-all duration-200">
                        XII
                    </button>
                </div>
            </div>
        </div>

        <!-- All Classrooms Grid with Dynamic Filter -->
        <div>
            @if($classrooms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($classrooms as $classroom)
                @php
                    $gradientClass = match($classroom->tingkat) {
                        'X' => 'from-blue-500 to-blue-600',
                        'XI' => 'from-purple-500 to-purple-600',
                        'XII' => 'from-orange-500 to-orange-600',
                        default => 'from-gray-500 to-gray-600'
                    };
                    $textClass = match($classroom->tingkat) {
                        'X' => 'text-blue-100',
                        'XI' => 'text-purple-100',
                        'XII' => 'text-orange-100',
                        default => 'text-gray-100'
                    };
                @endphp
                <div 
                    x-show="filter === 'semua' || filter === '{{ $classroom->tingkat }}'"  
                    x-transition:enter="transition ease-out duration-300" 
                    x-transition:enter-start="opacity-0 transform scale-95" 
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200" 
                    x-transition:leave-start="opacity-100 transform scale-100" 
                    x-transition:leave-end="opacity-0 transform scale-95"
                    @click="window.location.href = '{{ route('classrooms.show', $classroom->id) }}'"
                    class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 cursor-pointer">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-br {{ $gradientClass }} px-6 py-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <span class="text-2xl">
                                        @if($classroom->tingkat === 'X') 🎓
                                        @elseif($classroom->tingkat === 'XI') 📚
                                        @elseif($classroom->tingkat === 'XII') 🎯
                                        @else 📖
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ $classroom->name }}</h3>
                                    <p class="{{ $textClass }} text-sm mt-0.5">Jumlah Siswa: <span class="font-semibold">{{ $classroom->students_count }}</span></p>
                                </div>
                            </div>
                            
                            <!-- Three Dots Menu (Alpine.js Dropdown) -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click.stop="open = !open" class="w-8 h-8 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg flex items-center justify-center transition-all">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl z-10 border border-gray-100 overflow-hidden">
                                    <a href="{{ route('classrooms.edit', $classroom->id) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition-colors">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit Kelas
                                    </a>
                                    <button @click="deleteId = {{ $classroom->id }}; $refs.deleteModal.showModal(); open = false" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus Kelas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Wali Kelas</p>
                                    <p class="text-sm font-bold text-gray-800 mt-1">{{ $classroom->user->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $classroom->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-xl shadow-md p-12 text-center border border-gray-100">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Data Kelas</h3>
                    <p class="text-gray-500 mb-4">Tidak ada data kelas yang terdaftar</p>
                    <a href="{{ route('classrooms.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Kelas Baru
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal (Alpine.js + HTML Dialog) -->
    <dialog x-ref="deleteModal" class="backdrop:bg-black backdrop:bg-opacity-50 rounded-2xl shadow-2xl p-0 w-full max-w-md">
        <div class="bg-white rounded-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-5">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Konfirmasi Penghapusan</h3>
                        <p class="text-red-100 text-sm mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-700 text-base leading-relaxed">
                    Apakah Anda yakin ingin menghapus data kelas ini? <br>
                    <span class="font-semibold text-red-600">Semua data terkait akan terhapus secara permanen.</span>
                </p>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
                <button @click="$refs.deleteModal.close()" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-all">
                    Batal
                </button>
                <form :action="`{{ route('classrooms.index') }}/${deleteId}`" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                        Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </dialog>

</div>

@push('scripts')
<script>
    // Success Notification dengan SweetAlert2
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000,
            customClass: {
                customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6 py-2'
        }
    });
    @endif
</script>
@endpush
@endsection
