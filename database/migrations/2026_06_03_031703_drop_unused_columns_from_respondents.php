<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('respondents', function (Blueprint $table) {
            // Kita buang NIK, Alamat, dan Tempat Lahir agar murni dinamis
            $table->dropColumn(['nik', 'alamat', 'tempat_lahir']);

            // Ubah tanggal lahir menjadi boleh kosong (jika modul umur dimatikan)
            $table->date('tanggal_lahir')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('respondents', function (Blueprint $table) {
            $table->string('nik')->nullable();
            $table->text('alamat')->nullable();
            $table->string('tempat_lahir')->nullable();
        });
    }
};
