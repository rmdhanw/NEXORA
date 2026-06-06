<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('respondents', function (Blueprint $table) {
            // Membuang sisa kolom bawaan
            $table->dropColumn(['nama', 'tanggal_lahir']);
        });
    }

    public function down(): void
    {
        Schema::table('respondents', function (Blueprint $table) {
            $table->string('nama')->nullable();
            $table->date('tanggal_lahir')->nullable();
        });
    }
};
