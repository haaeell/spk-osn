<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kriteria;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === User Seeder ===
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@spk.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Penilai MTK',
            'email' => 'penilai.mtk@spk.com',
            'password' => Hash::make('password'),
            'role' => 'penilai',
            'kategori_mapel' => 'mtk',
        ]);

        User::create([
            'name' => 'Penilai IPA',
            'email' => 'penilai.ipa@spk.com',
            'password' => Hash::make('password'),
            'role' => 'penilai',
            'kategori_mapel' => 'ipa',
        ]);

        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@spk.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_sekolah',
        ]);

        // === Siswa Seeder ===
        Siswa::insert([
            ['nis' => '001', 'nama' => 'Andi Wijaya', 'kelas' => '9A', 'jenis_kelamin' => 'L'],
            ['nis' => '002', 'nama' => 'Budi Santoso', 'kelas' => '9A', 'jenis_kelamin' => 'L'],
            ['nis' => '003', 'nama' => 'Citra Ayu', 'kelas' => '9B', 'jenis_kelamin' => 'P'],
        ]);

        // === Kriteria Seeder ===
        Kriteria::insert([
            // MTK
            ['nama_kriteria' => 'Tes Tertulis', 'mapel' => 'mtk', 'bobot' => 0.5],
            ['nama_kriteria' => 'Latihan Soal', 'mapel' => 'mtk', 'bobot' => 0.3],
            ['nama_kriteria' => 'Wawancara', 'mapel' => 'mtk', 'bobot' => 0.2],

            // IPA
            ['nama_kriteria' => 'Praktikum', 'mapel' => 'ipa', 'bobot' => 0.4],
            ['nama_kriteria' => 'Tes Teori', 'mapel' => 'ipa', 'bobot' => 0.4],
            ['nama_kriteria' => 'Presentasi', 'mapel' => 'ipa', 'bobot' => 0.2],
        ]);
    }
}
