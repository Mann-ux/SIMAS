{{-- ============================================================
     TABLE VIEW — Desktop Only (hidden on mobile)
============================================================ --}}
<div class="hidden md:block w-full overflow-x-auto px-6 pb-6">
    <table class="w-full text-left">
        <thead>
            <tr class="text-slate-400 text-[11px] uppercase tracking-[0.15em] font-bold">
                <th class="pb-5 w-4 pr-3"></th>{{-- gender indicator spacer --}}
                <th class="pb-5 w-12">No</th>
                <th class="pb-5">NIS</th>
                <th class="pb-5">Nama Siswa</th>
                <th class="pb-5">Kelas</th>
                <th class="pb-5 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($students as $index => $student)
                @php
                    $borderColor = match ($student->jenis_kelamin) {
                        'L' => 'bg-blue-500',
                        'P' => 'bg-pink-400',
                        default => 'bg-slate-200',
                    };
                    $classroomName = $student->classroom->name ?? null;
                @endphp
                <tr class="group hover:bg-slate-50 transition-colors">

                    {{-- Gender Indicator Bar (kiri) --}}
                    <td class="py-5 pr-3 w-1">
                        <div class="w-1 h-8 rounded-full {{ $borderColor }}"></div>
                    </td>

                    {{-- NO --}}
                    <td class="py-5 text-sm font-medium text-slate-400">
                        {{ $students->firstItem() + $index }}
                    </td>

                    {{-- NIS --}}
                    <td class="py-5 text-sm font-semibold text-slate-600 whitespace-nowrap">
                        {{ $student->nis }}
                    </td>

                    {{-- NAMA — teks saja, tanpa avatar/inisial --}}
                    <td class="py-5 text-[15px] font-semibold text-[#00236f] whitespace-nowrap">
                        {{ $student->name }}
                    </td>

                    {{-- KELAS — badge minimalis, atau strip "-" --}}
                    <td class="py-5">
                        @if($classroomName)
                            <span
                                class="px-3 py-1 bg-[#E0E7FF] text-[#4338CA] text-[11px] font-bold rounded-full uppercase tracking-tight whitespace-nowrap">
                                {{ $classroomName }}
                            </span>
                        @else
                            <span class="text-slate-400 font-medium">—</span>
                        @endif
                    </td>

                    {{-- AKSI — ikon SVG murni: Pensil (Edit) + Sampah (Hapus) --}}
                    <td class="py-5">
                        <div class="flex justify-end gap-2">

                            {{-- Edit: Ikon Pensil --}}
                            <a href="{{ route('students.edit', $student->id) }}" title="Edit"
                                class="p-2 text-slate-400 hover:text-[#00236f] hover:bg-slate-100 rounded-lg transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Hapus: form Laravel (CSRF + DELETE) + Ikon Sampah --}}
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                class="inline-block" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus"
                                    class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-slate-500 font-medium">Belum ada data siswa</p>
                            <p class="text-slate-400 text-sm mt-1">Klik tombol "+ Tambah Siswa" untuk menambah data</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ============================================================
        CARD VIEW — Mobile Only (hidden on desktop)
============================================================ --}}
<div class="grid grid-cols-1 gap-4 px-4 pb-6 md:hidden">
    @forelse($students as $index => $student)
        @php
            $cardBorderColor = match ($student->jenis_kelamin) {
                'L' => 'border-l-blue-500',
                'P' => 'border-l-pink-400',
                default => 'border-l-slate-200',
            };
            $classroomName = $student->classroom->name ?? null;
        @endphp
        <div class="bg-white border border-gray-200 border-l-4 {{ $cardBorderColor }} rounded-xl shadow-sm p-4 flex flex-col gap-2 relative overflow-hidden">

            {{-- Body: NIS + Nama --}}
            <div>
                <p class="text-xs text-slate-400 font-medium">{{ $student->nis }}</p>
                <p class="text-[15px] font-bold text-[#00236f] leading-snug mt-0.5">{{ $student->name }}</p>
            </div>

            {{-- Badge Kelas --}}
            <div>
                @if($classroomName)
                    <span class="px-3 py-1 bg-[#E0E7FF] text-[#4338CA] text-[11px] font-bold rounded-full uppercase tracking-tight">
                        {{ $classroomName }}
                    </span>
                @else
                    <span class="text-slate-400 font-medium text-sm">—</span>
                @endif
            </div>

            {{-- Footer: Aksi (Edit + Hapus) --}}
            <div class="border-t border-slate-100 pt-3 flex justify-end gap-3">

                {{-- Edit --}}
                <a href="{{ route('students.edit', $student->id) }}" title="Edit"
                    class="p-2 text-slate-400 hover:text-[#00236f] hover:bg-slate-100 rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>

                {{-- Hapus --}}
                <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                    class="inline-block" onsubmit="return confirmDelete(event)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Hapus"
                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>

            </div>
        </div>
    @empty
        <div class="py-12 text-center col-span-full">
            <div class="flex flex-col items-center justify-center">
                <svg class="w-14 h-14 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-slate-500 font-medium">Belum ada data siswa</p>
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($students->hasPages())
    <div
        class="px-6 pb-6 pt-3 border-t border-slate-100 flex flex-wrap justify-between items-center gap-3 text-sm text-slate-500">
        <div>Menampilkan {{ $students->firstItem() }}–{{ $students->lastItem() }} dari {{ $students->total() }}
            siswa</div>
        <div>
            {{ $students->links() }}
        </div>
    </div>
@endif
