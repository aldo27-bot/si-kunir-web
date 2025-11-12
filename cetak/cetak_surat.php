<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat <?= htmlspecialchars($judul_surat); ?> (<?= htmlspecialchars($kode_surat); ?>)</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            margin: 0;
            padding: 50px;
        }
        .container { width: 800px; margin: auto; }
        .header-kop { 
            display: flex; /* Menggunakan Flexbox untuk logo dan teks */
            align-items: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-logo { width: 15%; text-align: left; }
        .header-text { width: 85%; text-align: center; line-height: 1.1; }
        .kop-title { font-weight: bold; margin: 0; }
        .kop-text-1 { font-size: 16pt; }
        .kop-text-2 { font-size: 20pt; }
        .kop-alamat { font-size: 10pt; margin-top: 5px; }

        .content { margin-top: 20px; }
        .center { text-align: center; }
        .underline { text-decoration: underline; }
        .indented { margin-left: 50px; text-align: justify; text-indent: 50px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; margin-top: 5px; }
        td { padding: 5px 0; vertical-align: top; }
        .td-label { width: 200px; } 
        .ttd-box { width: 50%; float: right; text-align: center; margin-top: 50px; }

        @media print {
            .no-print { display: none; }
            .container { width: 19cm; margin: 0 auto; } 
            body { padding: 0.5cm; }
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="header-kop">
        <div class="header-logo">
            <img src="../assets/img/logonganjuk_hd.png" alt="Logo Nganjuk" style="width: 100px; height: auto;"> 
        </div>

        <div class="header-text">
            <p class="kop-title kop-text-1">PEMERINTAH KABUPATEN NGANJUK</p>
            <p class="kop-title kop-text-1">KECAMATAN NGETOS</p>
            <p class="kop-title kop-text-2">DESA KUNCIR</p>
            <p class="kop-alamat">Jalan Panglima Sudirman no 25 Desa Kuncir, Kode POS 64474</p>
        </div>
    </div>
    <div class="center">
        <p class="underline" style="font-weight: bold; font-size: 14pt; margin-bottom: 5px;">
            <?= htmlspecialchars(strtoupper($judul_surat)); ?>
        </p>
        <p style="margin-top: 0;">
            Nomor: <?= htmlspecialchars($surat['nomor_surat'] ?? '470/ [NOMOR OTOMATIS]'); ?>/<?= htmlspecialchars($kode_surat); ?>/<?= date('Y');?>
        </p>
    </div>

    <div class="content">
        <p class="indented">
            Yang bertanda tangan di bawah ini <?= htmlspecialchars($pejabat_ttd['jabatan'] ?? 'Pejabat Desa') ?> Desa [NAMA DESA], menerangkan bahwa:
        </p>
        
        <table>
            <tr><td class="td-label">Nama Lengkap</td><td>: <?= htmlspecialchars($surat['nama_pemohon'] ?? $surat['nama'] ?? '-'); ?></td></tr>
            <tr><td class="td-label">NIK</td><td>: <?= htmlspecialchars($surat['nik'] ?? '-'); ?></td></tr>
            <tr><td class="td-label">Tempat, Tgl Lahir</td><td>: <?= htmlspecialchars($surat['tempat_lahir'] ?? '-'); ?>, <?= htmlspecialchars($surat['tanggal_lahir'] ?? '-'); ?></td></tr>
            <tr><td class="td-label">Alamat</td><td>: <?= htmlspecialchars($surat['alamat'] ?? '-'); ?></td></tr>
            <tr><td class="td-label">Agama</td><td>: <?= htmlspecialchars($surat['agama'] ?? '-'); ?></td></tr>
            <tr><td class="td-label">Jenis Kelamin</td><td>: <?= htmlspecialchars($surat['jenis_kelamin'] ?? '-'); ?></td></tr>
            <tr><td class="td-label">Kewarganegaraan</td><td>: <?= htmlspecialchars($surat['kewarganegaraan'] ?? '-'); ?></td></tr>
        </table>
        
        <?php if ($kode_surat === 'SKK'): ?>
        <p class="indented">
            Berdasarkan keterangan yang bersangkutan, telah benar-benar kehilangan:
        </p>
        <table>
            <tr><td class="td-label">Keterangan Kehilangan</td><td>: <?= nl2br(htmlspecialchars($surat['keterangan'] ?? 'Belum ada keterangan')); ?></td></tr>
            </table>
        <?php elseif ($kode_surat === 'SKD'): ?>
        <p class="indented">
            Berdasarkan data kependudukan, nama yang bersangkutan benar-benar berdomisili di wilayah Desa Kuncir. Surat ini dibuat untuk keperluan<?= htmlspecialchars($surat['keperluan'] ?? '-'); ?>**.
        </p>
        <?php endif; ?>

        <p class="indented">
            Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <?php if ($pejabat_ttd): ?>
    <div class="ttd-box">
        Desa Kuncir, <?= $tanggal_cetak_indo; ?>
        <br>
        <?= htmlspecialchars(strtoupper($pejabat_ttd['jabatan'])); ?>
        <br><br>
        
        <?php if (!empty($pejabat_ttd['barcode'])): ?>
             <div style="height: 100px;">[Placeholder Barcode/QR Code]</div> 
        <?php else: ?>
            <br><br><br><br> 
        <?php endif; ?>
        
        <span class="underline">
            <?= htmlspecialchars(strtoupper($pejabat_ttd['nama'])); ?>
        </span>
        <br>
        <?php if (!empty($pejabat_ttd['nip'])): ?>
            NIP. <?= htmlspecialchars($pejabat_ttd['nip']); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <div style="clear: both;"></div>
    
    <div class="no-print center" style="margin-top: 50px;">
        <button onclick="window.print()" style="padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; font-size: 16px;">
            🖨️ Cetak Dokumen
        </button>
    </div>

</div>

</body>
</html>