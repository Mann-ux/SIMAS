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
        Schema::table('students', function (Blueprint $table) {
            // Drop foreign key constraint yang lama
            $table->dropForeign(['classroom_id']);
            
            // Ubah kolom menjadi nullable
            $table->unsignedBigInteger('classroom_id')->nullable()->change();
            
            // Tambahkan foreign key baru dengan onDelete set null
            $table->foreign('classroom_id')
                  ->references('id')
                  ->on('classrooms')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop foreign key yang baru
            $table->dropForeign(['classroom_id']);
            
            // Kembalikan kolom ke not nullable
            $table->unsignedBigInteger('classroom_id')->nullable(false)->change();
            
            // Tambahkan kembali foreign key dengan onDelete cascade
            $table->foreign('classroom_id')
                  ->references('id')
                  ->on('classrooms')
                  ->onDelete('cascade');
        });
    }
};
