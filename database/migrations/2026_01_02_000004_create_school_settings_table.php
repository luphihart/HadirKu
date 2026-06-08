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
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->text('alamat_sekolah');
            $table->string('npsn')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->decimal('latitude', 10, 7)->default(-6.2088000);
            $table->decimal('longitude', 10, 7)->default(106.8456000);
            $table->integer('radius_meter')->default(100);
            $table->time('jam_masuk')->default('07:00');
            $table->time('jam_terlambat')->default('07:15');
            $table->time('jam_pulang')->default('14:00');
            $table->time('jam_pulang_akhir')->default('17:00');
            $table->json('hari_aktif')->default('["Senin","Selasa","Rabu","Kamis","Jumat"]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
