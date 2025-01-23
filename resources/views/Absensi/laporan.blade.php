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
                <th>Waktu Absensi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($absensis as $absensi)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $absensi->user->name }}</td>
                    <td>{{ $absensi->created_at }}</td>
                    <td>{{ $absensi->status_absensi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
