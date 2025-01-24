<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
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
    <div class="mt-4">
        <h1 style="text-align: center;">Laporan Absensi</h1>
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
            </tbody>
        </table>
    </div>
</body>

</html>
