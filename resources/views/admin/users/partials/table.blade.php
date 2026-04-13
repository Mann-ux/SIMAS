{{-- ============================================================
     TABLE VIEW — Desktop Only (hidden on mobile)
============================================================ --}}
<div class="hidden md:block w-full overflow-x-auto px-6 pb-6">
    <table class="w-full text-left">
        <thead>
            <tr class="text-slate-400 text-[11px] uppercase tracking-[0.15em] font-bold">
                <th class="pb-5 w-16">No</th>
                <th class="pb-5">Nama Guru</th>
                <th class="pb-5">Email</th>
                <th class="pb-5">Role</th>
                <th class="pb-5 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($users as $index => $user)
                <tr class="group hover:bg-slate-50 transition-colors">

                    {{-- NO --}}
                    <td class="py-5 text-sm font-medium text-slate-400">
                        {{ $users->firstItem() + $index }}
                    </td>

                    {{-- NAMA — tanpa avatar/inisial --}}
                    <td class="py-5 text-[15px] font-semibold text-[#00236f]">
                        {{ $user->name }}
                    </td>

                    {{-- EMAIL --}}
                    <td class="py-5 text-sm text-slate-400">
                        {{ $user->email }}
                    </td>

                    {{-- ROLE BADGE (pastel transparan) --}}
                    <td class="py-5">
                        @if($user->role === 'admin')
                            <span
                                class="px-3 py-1 bg-[#DBEAFE] text-[#1E40AF] text-[11px] font-bold rounded-full uppercase tracking-tight">
                                Administrator
                            </span>
                        @elseif($user->role === 'guru')
                            <span
                                class="px-3 py-1 bg-[#D1FAE5] text-[#065F46] text-[11px] font-bold rounded-full uppercase tracking-tight">
                                Guru
                            </span>
                        @elseif($user->role === 'wali_kelas')
                            <span
                                class="px-3 py-1 bg-[#E0E7FF] text-[#4338CA] text-[11px] font-bold rounded-full uppercase tracking-tight">
                                Wali Kelas {{ $user->classroom?->name ?? '-' }}
                            </span>
                        @else
                            <span
                                class="px-3 py-1 bg-slate-100 text-slate-500 text-[11px] font-bold rounded-full uppercase tracking-tight">
                                {{ ucfirst($user->role) }}
                            </span>
                        @endif
                    </td>

                    {{-- AKSI — ikon SVG murni --}}
                    <td class="py-5">
                        <div class="flex justify-end gap-2">

                            {{-- Edit: ikon Pensil --}}
                            <a href="{{ route('users.edit', $user->id) }}" title="Edit"
                                class="p-2 text-slate-400 hover:text-[#00236f] hover:bg-slate-100 rounded-lg transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Hapus: form Laravel (CSRF + DELETE method) + ikon Sampah --}}
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
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
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="text-slate-500 font-medium">Belum ada data user</p>
                            <p class="text-slate-400 text-sm mt-1">Klik tombol "+ Tambah User" untuk menambah data</p>
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
    @forelse($users as $index => $user)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col gap-2">

            {{-- Body: Nama + Email --}}
            <div>
                <p class="text-[15px] font-bold text-[#00236f] leading-snug">{{ $user->name }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ $user->email }}</p>
            </div>

            {{-- Badge Role --}}
            <div>
                @if($user->role === 'admin')
                    <span class="px-3 py-1 bg-[#DBEAFE] text-[#1E40AF] text-[11px] font-bold rounded-full uppercase tracking-tight">
                        Administrator
                    </span>
                @elseif($user->role === 'guru')
                    <span class="px-3 py-1 bg-[#D1FAE5] text-[#065F46] text-[11px] font-bold rounded-full uppercase tracking-tight">
                        Guru
                    </span>
                @elseif($user->role === 'wali_kelas')
                    <span class="px-3 py-1 bg-[#E0E7FF] text-[#4338CA] text-[11px] font-bold rounded-full uppercase tracking-tight">
                        Wali Kelas {{ $user->classroom?->name ?? '-' }}
                    </span>
                @else
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[11px] font-bold rounded-full uppercase tracking-tight">
                        {{ ucfirst($user->role) }}
                    </span>
                @endif
            </div>

            {{-- Footer: Aksi (Edit + Hapus) --}}
            <div class="border-t border-slate-100 pt-3 mt-1 flex justify-end gap-3">

                {{-- Edit --}}
                <a href="{{ route('users.edit', $user->id) }}" title="Edit"
                    class="p-2 text-slate-400 hover:text-[#00236f] hover:bg-slate-100 rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>

                {{-- Hapus --}}
                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p class="text-slate-500 font-medium">Belum ada data user</p>
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($users->hasPages())
    <div
        class="px-6 pb-6 pt-3 border-t border-slate-100 flex flex-wrap justify-between items-center gap-3 text-sm text-slate-500">
        <div>Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna</div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
@endif
