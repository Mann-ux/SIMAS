{{--
    Partial: admin/mutasi/partials/siswa-table.blade.php
    Digunakan oleh: MutasiController@index (full & AJAX)
    Variabel: $siswaList (Collection of Student)
--}}
@if($siswaList->isEmpty())
    <tr id="no-results-row">
        <td colspan="5" class="px-5 py-14 text-center">
            <div class="flex flex-col items-center gap-2">
                <span class="material-symbols-outlined text-4xl text-gray-300">manage_search</span>
                <p class="text-sm font-semibold text-gray-400">Tidak ada siswa yang cocok dengan pencarian.</p>
            </div>
        </td>
    </tr>
@else
    @foreach($siswaList as $i => $siswa)
    <tr class="hover:bg-blue-50/30 transition-colors">
        <td class="px-5 py-4 text-center">
            <input
                type="checkbox"
                name="nis_array[]"
                value="{{ $siswa->nis }}"
                class="siswa-checkbox w-4 h-4 rounded accent-[#00236f] cursor-pointer"
            />
        </td>
        <td class="px-5 py-4 text-sm text-gray-400 font-medium">{{ $i + 1 }}</td>
        <td class="px-5 py-4">
            <span class="text-xs font-mono font-bold text-[#00236f] bg-indigo-50 px-2.5 py-1 rounded-lg">{{ $siswa->nis }}</span>
        </td>
        <td class="px-5 py-4">
            <span class="text-sm font-semibold text-gray-800">{{ $siswa->name }}</span>
        </td>
        <td class="px-5 py-4">
            @php $jk = $siswa->jenis_kelamin ?? '-'; @endphp
            <span class="px-3 py-1 rounded-full text-xs font-bold
                {{ $jk === 'L' ? 'bg-blue-100 text-blue-700' : ($jk === 'P' ? 'bg-rose-100 text-rose-700' : 'bg-gray-100 text-gray-500') }}">
                {{ $jk === 'L' ? 'Laki-laki' : ($jk === 'P' ? 'Perempuan' : '-') }}
            </span>
        </td>
    </tr>
    @endforeach
@endif
