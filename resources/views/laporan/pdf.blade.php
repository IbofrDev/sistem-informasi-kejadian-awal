<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kejadian #{{ str_pad($laporan->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            /* * Menggunakan Arial/Helvetica sebagai fallback jika DejaVu Sans tidak ada.
             * Arial lebih umum dan mirip dengan di gambar. 
            */
            font-family: Arial, Helvetica, sans-serif;
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
        /* ==            INI BAGIAN YANG SAYA MODIFIKASI           == */
        /* ========================================================== */
        .header {
            width: 100%;
            margin-bottom: 15px; /* Disesuaikan agar pas dengan <hr> */
        }

        /* CSS ini tidak diperlukan lagi karena styling ada di inline tabel */
        /*
         .header h1 { ... }
         .header p { ... }
         .logo { ... }
        */
        /* ========================================================== */
        /* ==               AKHIR PERUBAHAN CSS                    == */
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
        {{-- ================================================== --}}
        {{-- ==      HEADER BARU (ALAMAT DI BAWAH JUDUL)     == --}}
        {{-- ================================================== --}}
        <div class="header">
            @php
                // gunakan path absolut dengan prefix file:// agar bisa dibaca oleh DomPDF
                $logoPath = public_path('images/logo_ksop.png'); // Asumsi logo_ksop.png adalah logo Kemenhub
                $logoSrc = 'file://' . str_replace('\\', '/', $logoPath);
            @endphp

            <table width="100%" style="border-collapse: collapse;">
                <tr>
                    <td width="15%" style="text-align: center; vertical-align: top;">
                        @if (file_exists($logoPath))
                            <img src="{{ $logoSrc }}" alt="Logo" style="width: 80px; height: auto;">
                        @endif
                    </td>

                    <td width="85%"
                        style="text-align: center; vertical-align: top; line-height: 1.4; padding-left: 10px;">
                        
                        <div style="margin-bottom: 8px;">
                            <strong style="font-size: 13px; display: block; margin: 0;">
                                KEMENTERIAN PERHUBUNGAN
                            </strong>
                            <strong style="font-size: 13px; display: block; margin: 0;">
                                DIREKTORAT JENDERAL PERHUBUNGAN LAUT
                            </strong>
                            <strong style="font-size: 14px; display: block; margin: 0;">
                                KANTOR KESYAHBANDARAN DAN OTORITAS PELABUHAN BANJARMASIN
                            </strong>
                        </div>

                        <div style="font-size: 10px; line-height: 1.3;">
                            Jl. Duyung Raya, Komplek Lumba-Lumba No. 45, Banjarmasin, 70119<br>
                            Telepon: (0511) 3352640 - 3354775 | Fax.: 3353734 | email: adpel_bmasin@yahoo.co.id
                        </div>

                    </td>
                </tr>
            </table>

            <hr style="border: 0; border-top: 3px solid #000; margin-top: 10px;">
        </div>
        {{-- ================================================== --}}
        {{-- ==             AKHIR MODIFIKASI HEADER          == --}}
        {{-- ================================================== --}}


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