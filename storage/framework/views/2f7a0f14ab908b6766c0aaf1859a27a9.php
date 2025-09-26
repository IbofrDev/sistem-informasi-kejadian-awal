<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kejadian #<?php echo e(str_pad($laporan->id, 6, '0', STR_PAD_LEFT)); ?></title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
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
        }

        .report-content {
            white-space: pre-wrap;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="<?php echo e(public_path('images/logo_ksop.png')); ?>" alt="Logo KSOP"
                style="width: 70px; height: auto; margin-bottom: 10px;">
            <h1>LAPORAN KECELAKAAN KAPAL</h1>
            <p>Sistem Informasi Kecelakaan Kapal - KSOP Kelas I Banjarmasin</p>
        </div>

        <table class="main-table">
            <!-- Identitas Pelapor & Kapal -->
            <tr>
                <td style="width: 50%;">
                    <h6>Identitas Pelapor</h6>
                    <table class="inner-table">
                        <tr>
                            <th scope="row">Nama</th>
                            <td>: <?php echo e($laporan->nama_pelapor); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Telepon</th>
                            <td>: <?php echo e($laporan->telepon_pelapor); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Jabatan</th>
                            <td>: <?php echo e($laporan->jabatan_pelapor); ?></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <h6>Identitas Kapal</h6>
                    <table class="inner-table">
                        <tr>
                            <th scope="row">Nama Kapal</th>
                            <td>: <?php echo e($laporan->nama_kapal); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Jenis Kapal</th>
                            <td>: <?php echo e($laporan->jenis_kapal); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Bendera</th>
                            <td>: <?php echo e($laporan->bendera_kapal); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">GRT</th>
                            <td>: <?php echo e($laporan->grt_kapal); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Detail Kejadian -->
            <tr>
                <td colspan="2">
                    <h6>Detail Kejadian</h6>
                    <table class="inner-table">
                        <tr>
                            <th scope="row">Posisi Lintang</th>
                            <td>: <?php echo e($laporan->posisi_lintang); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Posisi Bujur</th>
                            <td>: <?php echo e($laporan->posisi_bujur); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Tanggal Kejadian</th>
                            <td>: <?php echo e(\Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y, H:i')); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Isi Laporan -->
            <tr>
                <td colspan="2">
                    <h6>Isi Laporan</h6>
                    <div class="report-content"><?php echo e($laporan->isi_laporan); ?></div>
                </td>
            </tr>
        </table>

    </div>
</body>

</html><?php /**PATH C:\PKL\sistem_kejadian_awal\resources\views/laporan/pdf.blade.php ENDPATH**/ ?>