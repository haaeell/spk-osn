<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Perhitungan - {{ $tanggal }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .tanggal {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Hasil Perhitungan Seleksi Siswa</h2>
    <div class="tanggal">
        Tanggal Perhitungan: <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Nilai Akhir</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
                @php $hasil = json_decode($item->hasil); @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $hasil->nama ?? '-' }}</td>
                    <td>{{ $hasil->kelas ?? '-' }}</td>
                    <td>{{ number_format($item->nilai_akhir, 4) }}</td>
                    <td>{{ $hasil->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
