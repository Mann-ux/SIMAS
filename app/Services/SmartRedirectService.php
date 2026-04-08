<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\User;

class SmartRedirectService
{
    public function getRedirectRouteName(User $user): string
    {
        if ($user->role === 'admin') {
            return 'admin.dashboard';
        }

        if ($user->role === 'wali_kelas') {
            return $this->hasWaliKelasClass($user) ? 'wali-kelas.absen.create' : 'wali-kelas.dashboard';
        }

        if ($this->isPengurusRole($user) || $this->isPengurusStudent($user)) {
            return 'pengurus.dashboard';
        }

        return 'dashboard';
    }

    private function isPengurusRole(User $user): bool
    {
        return in_array($user->role, ['sekretaris', 'ketua_kelas'], true);
    }

    private function hasWaliKelasClass(User $user): bool
    {
        return Classroom::where('wali_kelas_id', $user->id)->exists();
    }

    private function isPengurusStudent(User $user): bool
    {
        return Classroom::where('ketua_id', $user->id)
            ->orWhere('sekretaris_id', $user->id)
            ->exists();
    }
}
