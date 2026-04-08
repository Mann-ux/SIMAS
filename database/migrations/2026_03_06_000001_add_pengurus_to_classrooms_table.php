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
            $table->foreignId('ketua_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sekretaris_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['ketua_id']);
            $table->dropForeign(['sekretaris_id']);
            $table->dropColumn(['ketua_id', 'sekretaris_id']);
        });
    }
};
