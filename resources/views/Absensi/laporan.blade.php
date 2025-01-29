<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi dan Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
            font-size: 12px;
        }

        th {
            font-size: 12px;
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">Laporan Absensi</h1>
    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Waktu Absensi Masuk</th>
            <th>Status Absensi Masuk</th>
            <th>Waktu Absensi Pulang</th>
            <th>Status Absensi Pulang</th>
        </tr>
        </thead>
        <tbody>
        @php $no = 1; @endphp
        @foreach ($absensis as $absensi)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $absensi->user->name }}</td>
                <td>{{ $absensi->waktu_masuk }}</td>
                <td>{{ $absensi->status_absensi_masuk }}</td>
                <td>{{ $absensi->waktu_pulang }}</td>
                <td>{{ $absensi->status_absensi_pulang }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h1 style="text-align: center; margin-top: 30px;">Laporan Gaji</h1>
    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Jabatan</th>
                <th colspan="3">Jumlah</th>
                <th colspan="2">Pinalti  50.000 (>3)</th>
                <th rowspan="2">Pinalti</th>
                <th colspan="2">Gaji</th>
            </tr>
            <tr>
                <th>Izin</th>
                <th>Alpha</th>
                <th>Pulang Cepat</th>
                <th>Masuk</th>
                <th>Pulang</th>
                <th>Pokok</th>
                <th>Akhir</th>
            </tr>
        </thead>
        <tbody>
        @php $no = 1; @endphp
        @foreach ($laporanGaji as $gaji)
            <tr style="font-size: 9px">
                <td>{{ $no++ }}</td>
                <td>{{ $gaji['nama'] }}</td>
                <td>{{ $gaji['jabatan'] }}</td>
                <td>{{ $gaji['jumlah_alpha'] }}</td>
                <td>{{ $gaji['jumlah_izin'] }}</td>
                <td>{{ $gaji['jumlah_pulang_cepat'] }}</td>
                <td>{{ $gaji['total_pinalti_masuk'] }}</td>
                <td>{{ $gaji['total_pinalti_pulang'] }}</td>
                <td>{{ number_format($gaji['total_pinalti'], 0, ',', '.') }}</td>
                <td>{{ number_format($gaji['gaji_pokok'], 0, ',', '.') }}</td>
                <td>{{ number_format($gaji['gaji_akhir'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>

</html>
