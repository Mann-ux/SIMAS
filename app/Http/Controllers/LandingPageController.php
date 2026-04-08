<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Services\SmartRedirectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(SmartRedirectService $smartRedirect): View|RedirectResponse
    {
        if (Auth::check()) {
            $routeName = $smartRedirect->getRedirectRouteName(Auth::user());

            return redirect()->route($routeName);
        }

        $classrooms = Classroom::query()
            ->withCount('students')
            ->orderBy('tingkat')
            ->orderBy('name')
            ->get();

        // Statistik dinamis untuk landing page
        $totalKelas = Classroom::count();
        $totalSiswa = Student::count();
        $totalGuru  = User::where('role', 'wali_kelas')->count();

        return view('landing', compact('classrooms', 'totalKelas', 'totalSiswa', 'totalGuru'));
    }
}
