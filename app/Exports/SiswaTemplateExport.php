<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromArray, WithHeadings
{
    /**
     * Define the headings for the Excel template.
     */
    public function headings(): array
    {
        return [
            'nis',
            'nama',
            'kelas',
            'jenis_kelamin',
        ];
    }

    /**
     * Provide sample data for the template.
     */
    public function array(): array
    {
        return [
            ['123456', 'John Doe', 'XII IPA', 'L'],
            ['123457', 'Jane Smith', 'XII IPS', 'P'],
        ];
    }
}
