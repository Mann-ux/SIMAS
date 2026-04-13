<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Imports\RollingKelasImport;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MutasiController extends Controller
{
    /**
     * Tampilkan halaman mutasi / kenaikan kelas.
     * Jika ada filter kelas_asal, ambil siswa dari kelas tersebut.
     */
    public function index(Request $request)
    {
        // Semua kelas aktif untuk dropdown kelas asal & kelas tujuan
        $classrooms = Classroom::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $siswaList = collect();
        $kelasAsal = null;

        if ($request->filled('kelas_asal')) {
            $kelasAsal = Classroom::where('status', 1)->find($request->kelas_asal);

            if ($kelasAsal) {
                $query = Student::where('classroom_id', $kelasAsal->id);

                // Filter pencarian live-search
                if ($request->filled('search')) {
                    $term = '%' . $request->search . '%';
                    $query->where(function ($q) use ($term) {
                        $q->where('nis', 'LIKE', $term)
                          ->orWhere('name', 'LIKE', $term);
                    });
                }

                $siswaList = $query->orderBy('name', 'asc')->get();
            }
        }

        // AJAX request → kembalikan hanya partial HTML (baris tabel)
        if ($request->ajax()) {
            return response()->view(
                'admin.mutasi.partials.siswa-table',
                compact('siswaList')
            );
        }

        return view('admin.mutasi.index', compact('classrooms', 'siswaList', 'kelasAsal'));
    }

    /**
     * Proses pemindahan (mutasi) siswa ke kelas tujuan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_tujuan' => 'required|exists:classrooms,id',
            'nis_array'    => 'required|array|min:1',
            'nis_array.*'  => 'required|string|exists:students,nis',
        ], [
            'kelas_tujuan.required' => 'Kelas tujuan wajib dipilih.',
            'nis_array.required'    => 'Pilih minimal satu siswa untuk dipindahkan.',
            'nis_array.min'         => 'Pilih minimal satu siswa untuk dipindahkan.',
        ]);

        $jumlah = Student::whereIn('nis', $request->nis_array)
            ->update(['classroom_id' => $request->kelas_tujuan]);

        $kelasTujuan = Classroom::find($request->kelas_tujuan);

        return redirect()
            ->route('admin.mutasi.index')
            ->with('success', $jumlah . ' siswa berhasil dipindahkan ke kelas ' . ($kelasTujuan->name ?? '-') . '.');
    }
    /**
     * Download semua data siswa sebagai format Excel untuk rolling kelas.
     */
    public function exportExcel()
    {
        return Excel::download(new SiswaExport, 'format_rolling_kelas.xlsx');
    }

    /**
     * Import rolling kelas dari file Excel.
     * Hanya UPDATE classroom_id siswa yang sudah ada — tidak membuat data baru.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes'    => 'Format file harus .xlsx atau .xls.',
            'file_excel.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $import = new RollingKelasImport();
        Excel::import($import, $request->file('file_excel'));

        $msg = $import->updated . ' siswa berhasil dipindahkan';
        if ($import->skipped > 0) {
            $msg .= ', ' . $import->skipped . ' baris dilewati (NIS/nama kelas tidak valid)';
        }
        $msg .= '.';

        return redirect()
            ->route('admin.mutasi.index')
            ->with('success', $msg);
    }
}
