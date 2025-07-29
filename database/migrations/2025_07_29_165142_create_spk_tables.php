<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'penilai', 'kepala_sekolah'])->after('email');
            $table->enum('kategori_mapel', ['ipa', 'mtk', 'ips'])->nullable()->after('role');
        });

        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('nama');
            $table->string('kelas');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->timestamps();
        });

        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria');
            $table->enum('mapel', ['ipa', 'mtk', 'ips']);
            $table->float('bobot');
            $table->timestamps();
        });

        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_siswa')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('id_kriteria')->constrained('kriteria')->onDelete('cascade');
            $table->foreignId('id_penilai')->constrained('users')->onDelete('cascade');
            $table->float('nilai');
            $table->timestamps();
        });

        Schema::create('hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_siswa')->constrained('siswa')->onDelete('cascade');
            $table->enum('mapel', ['ipa', 'mtk', 'ips']);
            $table->float('nilai_akhir');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil');
        Schema::dropIfExists('penilaian');
        Schema::dropIfExists('kriteria');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('users');
    }
};
