<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Opsi untuk menyalakan/mematikan input Foto
            $table->boolean('has_photo')->default(true)->after('deskripsi');

            // Opsi untuk menyalakan/mematikan input TTL & Umur
            $table->boolean('has_age_calc')->default(true)->after('has_photo');

            // Master Data untuk field dinamis (berisi array JSON struktur form)
            $table->json('master_fields')->nullable()->after('has_age_calc');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['has_photo', 'has_age_calc', 'master_fields']);
        });
    }
};
