<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $afterColumn = Schema::hasColumn('students', 'nama_lengkap')
            ? 'nama_lengkap'
            : (Schema::hasColumn('students', 'name') ? 'name' : null);

        Schema::table('students', function (Blueprint $table) use ($afterColumn) {
            $column = $table->enum('jenis_kelamin', ['L', 'P'])->nullable();

            if ($afterColumn) {
                $column->after($afterColumn);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });
    }
};
