<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
        h1 {
            color: #2c3e50;
            margin: 0;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-hadir {
            color: #27ae60;
            font-weight: bold;
        }
        .status-terlambat {
            color: #f39c12;
            font-weight: bold;
        }
        .status-absen {
            color: #e74c3c;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="font-size: 24px;">Absensi Karyawan Infracom Telesarana</h1>
        </div>

        <div class="info">
            <p><strong>Tanggal Laporan:</strong> {{ now()->format('d F Y') }}</p>
            <p><strong>Total Absensi:</strong> {{ $data->count() }}</p>
            @if($selectedUser)
                <p><strong>Nama Karyawan:</strong> {{ $selectedUser->name }}</p>
            @else
                <p><strong>Data untuk:</strong> Semua Karyawan</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Tipe</th>
                    <th>Shift</th>
                    <th>Waktu Absen</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $absen)
                <tr>
                    <td>{{ $absen->user->name }}</td>
                    <td>{{ $absen->user->email }}</td>
                    <td class="status-{{ strtolower($absen->status) }}">{{ $absen->status }}</td>
                    <td>{{ $absen->type }}</td>
                    <td>{{ $absen->shift->shift }}</td>
                    <td>{{ $absen->jam_absen }}</td>
                    <td>{{ $absen->tanggal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Laporan ini dibuat secara otomatis pada {{ now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
