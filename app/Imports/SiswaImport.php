<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Map Excel rows to the Siswa model.
     */
    public function model(array $row)
    {
        return new Siswa([
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'kelas' => $row['kelas'],
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }

    /**
     * Define validation rules for the Excel data.
     */
    public function rules(): array
    {
        return [
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
        ];
    }
}
