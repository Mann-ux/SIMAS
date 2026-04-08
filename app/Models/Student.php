<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nis',
        'name',
        'nama_lengkap',
        'jenis_kelamin',
        'classroom_id',
    ];

    /**
     * Alias agar input `nama_lengkap` tetap tersimpan ke kolom `name`.
     */
    public function setNamaLengkapAttribute(?string $value): void
    {
        $this->attributes['name'] = $value;
    }

    /**
     * Alias baca `nama_lengkap` dari kolom `name`.
     */
    public function getNamaLengkapAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    /**
     * Get the classroom that owns the student.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Get the attendances for the student.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
}
