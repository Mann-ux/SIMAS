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
        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('ketua_nis')->nullable();
            $table->string('sekretaris_nis')->nullable();
            
            $table->foreign('ketua_nis')
                  ->references('nis')
                  ->on('students')
                  ->onDelete('set null');
                  
            $table->foreign('sekretaris_nis')
                  ->references('nis')
                  ->on('students')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['ketua_nis']);
            $table->dropForeign(['sekretaris_nis']);
            $table->dropColumn(['ketua_nis', 'sekretaris_nis']);
        });
    }
};
