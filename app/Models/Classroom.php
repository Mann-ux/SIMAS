<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'tingkat',
        'wali_kelas_id',
        'academic_year_id',
        'ketua_id',
        'sekretaris_id',
    ];

    /**
     * Get the academic year that owns the classroom.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the wali kelas that owns the classroom.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    /**
     * Get the wali kelas using the wali_kelas_id column.
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    /**
     * Get the students for the classroom.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the ketua (class president) of the classroom.
     */
    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    /**
     * Get the sekretaris (class secretary) of the classroom.
     */
    public function sekretaris(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sekretaris_id');
    }
}
