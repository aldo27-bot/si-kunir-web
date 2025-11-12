<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat <?= htmlspecialchars($kode_surat); ?></title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            margin: 0;
            padding: 50px;
        }
        .container { width: 800px; margin: auto; }
        .header, .content, .footer { margin-bottom: 20px; }
        .center { text-align: center; }
        .underline { text-decoration: underline; }
        .indented { margin-left: 50px; text-align: justify; text-indent: 50px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        td { padding: 5px 0; vertical-align: top; }
        .ttd-box { width: 50%; float: right; text-align: center; margin-top: 50px; }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="header center">
        <h2>PEMERINTAH KABUPATEN [NAMA KABUPATEN]</h2>
        <h3>KECAMATAN [NAMA KECAMATAN]</h3>
        <h1>DESA [NAMA DESA]</h1>
        <hr style="border: 2px solid #000;">
        <br>
        <p class="underline">
            SURAT KETERANGAN KEHILANGAN (SKK)
        </p>
        <p>
            Nomor: [NOMOR OTOMATIS]/<?= htmlspecialchars($kode_surat); ?>/[TAHUN]
        </p>
    </div>

    <div class="content">
        <p class="indented">
            Yang bertanda tangan di bawah ini menerangkan bahwa:
        </p>
        
        <table>
            <tr><td style="width: 200px;">Nama Lengkap</td><td>: <?= htmlspecialchars($surat['nama'] ?? $surat['nama_pemohon']); ?></td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>: <?= htmlspecialchars($surat['tempat_tanggal_lahir']); ?></td></tr>
            <tr><td>Alamat</td><td>: <?= htmlspecialchars($surat['alamat']); ?></td></tr>
            <tr><td>Agama</td><td>: <?= htmlspecialchars($surat['agama']); ?></td></tr>
            <tr><td>Jenis Kelamin</td><td>: <?= htmlspecialchars($surat['jenis_kelamin']); ?></td></tr>
            <tr><td>Kewarganegaraan</td><td>: <?= htmlspecialchars($surat['kewarganegaraan']); ?></td></tr>
        </table>
        
        <p class="indented">
            Berdasarkan keterangan yang bersangkutan, telah benar-benar kehilangan:
        </p>
        
        <table>
            <tr><td style="width: 200px;">Keterangan Kehilangan</td><td>: **<?= nl2br(htmlspecialchars($surat['keterangan'])); ?>**</td></tr>
        </table>

        <p class="indented">
            Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <?php if ($pejabat_ttd): ?>
    <div class="ttd-box">
        [NAMA DESA], <?= $tanggal_cetak_indo; ?>
        <br>
        <?= htmlspecialchars(strtoupper($pejabat_ttd['jabatan'])); ?>
        <br><br><br><br>
        <br>
        <span class="underline">
            **<?= htmlspecialchars(strtoupper($pejabat_ttd['nama'])); ?>**
        </span>
        <br>
        <?php if (!empty($pejabat_ttd['nip'])): ?>
            NIP. <?= htmlspecialchars($pejabat_ttd['nip']); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <div style="clear: both;"></div>
    
    <div class="no-print" style="margin-top: 50px;">
        <button onclick="window.print()" style="padding: 10px; background: #007bff; color: white; border: none; cursor: pointer;">Cetak Dokumen</button>
    </div>

</div>

</body>
</html>