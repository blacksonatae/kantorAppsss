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
        }

        th {
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
            <th>No</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Gaji Pokok</th>
            <th>Jumlah Keterlambatan</th>
            <th>Pinalti 1x Keterlambatan</th>
            <th>Total Pinalti</th>
            <th>Gaji Akhir</th>
        </tr>
        </thead>
        <tbody>
        @php $no = 1; @endphp
        @foreach ($laporanGaji as $gaji)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $gaji['nama'] }}</td>
                <td>{{ $gaji['jabatan'] }}</td>
                <td>{{ number_format($gaji['gaji_pokok'], 0, ',', '.') }}</td>
                <td>{{ $gaji['jumlah_keterlambatan'] }}</td>
                <td>{{ number_format($gaji['pinalti_per_keterlambatan'], 0, ',', '.') }}</td>
                <td>{{ number_format($gaji['total_pinalti'], 0, ',', '.') }}</td>
                <td>{{ number_format($gaji['gaji_akhir'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>

</html>
