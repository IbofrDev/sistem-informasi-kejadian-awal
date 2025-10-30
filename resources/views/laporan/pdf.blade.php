<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kejadian #{{ str_pad($laporan->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        /* ========================================================== */
        /* ==          INI BAGIAN YANG SAYA KEMBALIKAN (FIXED)       == */
        /* ========================================================== */
        .header {
            text-align: center; /* Teks kembali ke tengah */
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
        }

        .header p {
            margin: 0;
            font-size: 12px;
        }

        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 10px; /* Beri jarak antara logo dan teks di bawahnya */
        }
        /* ========================================================== */
        /* ==                    AKHIR PERUBAHAN CSS                   == */
        /* ========================================================== */

        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
        }

        .main-table td,
        .main-table th {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: top;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inner-table td,
        .inner-table th {
            border: none;
            padding: 2px 0;
        }

        .inner-table th {
            text-align: left;
            width: 35%;
        }

        h6 {
            font-size: 12px;
            margin-top: 0;
            margin-bottom: 10px;
            color: #000;
        }

        .report-content {
            white-space: pre-wrap;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            @php
                $logoPath = public_path('images/logo_ksop.png');
            @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="Logo KSOP" class="logo">
            @endif
            {{-- Teks kembali diletakkan di bawah logo --}}
            <h1>LAPORAN KECELAKAAN KAPAL</h1>
            <p>Sistem Informasi Kecelakaan Kapal - KSOP Kelas I Banjarmasin</p>
        </div>
        {{-- Isi Tabel Utama --}}
        <table class="main-table">
            <tr>
                <td style="width: 50%;">
                    <h6>Identitas Pelapor</h6>
                    <table class="inner-table">
                        <tr>
                            <th>Nama</th>
                            <td>: {{ $laporan->nama_pelapor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: {{ $laporan->telepon_pelapor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>: {{ $laporan->jabatan_pelapor ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <h6>Identitas Kapal</h6>
                    <table class="inner-table">
                        <tr>
                            <th>Nama Kapal</th>
                            <td>: {{ $laporan->nama_kapal ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kapal</th>
                            <td>: {{ $laporan->jenis_kapal ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bendera</th>
                            <td>: {{ $laporan->bendera_kapal ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>GRT</th>
                            <td>: {{ $laporan->grt_kapal ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <h6>Detail Kejadian</h6>
                    <table class="inner-table">
                        <tr>
                            <th>Posisi Lintang</th>
                            <td>: {{ $laporan->posisi_lintang ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Posisi Bujur</th>
                            <td>: {{ $laporan->posisi_bujur ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kejadian</th>
                            <td>: {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <h6>Isi Laporan</h6>
                    <div class="report-content">{{ $laporan->isi_laporan ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Dicetak otomatis oleh Sistem Informasi Kejadian Awal (SIKAP)</p>
        </div>
    </div>
</body>
</html>