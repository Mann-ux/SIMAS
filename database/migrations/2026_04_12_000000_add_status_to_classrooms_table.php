<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambahkan kolom status (boolean, default 1 = aktif) ke tabel classrooms.
     */
    public function up(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->boolean('status')->default(1)->after('academic_year_id')
                  ->comment('1 = aktif, 0 = diarsipkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
