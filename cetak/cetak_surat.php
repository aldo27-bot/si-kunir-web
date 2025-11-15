<?php
// Validasi minimal: pastikan $surat dan $pejabat_ttd tersedia
if (!isset($surat) || !is_array($surat)) {
    die("Error: Data surat tidak valid.");
}
if (!isset($pejabat_ttd) || !is_array($pejabat_ttd)) {
    die("Error: Data pejabat tidak tersedia.");
}

// Validasi opsional: pastikan surat sudah disetujui (status 'Selesai')
// Uncomment jika Anda menyimpan status di $surat['status']
/*
if (($surat['status'] ?? '') !== 'Selesai') {
    die("Surat ini belum disetujui. Tidak dapat dicetak.");
}
*/

// Data bantuan untuk logika tampilan
$with_ttl_alamat = ['SKBB', 'SKBN', 'SKK', 'SKKM', 'SKD', 'SKU'];
$with_agama_jk   = ['SKBB', 'SKK', 'SKKM', 'SKD'];
$with_pekerjaan  = ['SKBN', 'SKD'];

// Tanggal cetak dalam format Indonesia
function formatTanggalIndo($date)
{
    $bulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];
    $parts = explode('-', date('Y-m-d', strtotime($date ?? 'now')));
    return (int)$parts[2] . ' ' . $bulan[$parts[1]] . ' ' . $parts[0];
}

$tanggal_cetak_indo = formatTanggalIndo(date('Y-m-d'));
$kode_surat = htmlspecialchars($kode_surat ?? ''); // dari URL
$judul_surat = htmlspecialchars($judul_surat ?? 'SURAT KETERANGAN'); // dari $validTables

// Nomor surat: pastikan sudah di-generate saat disetujui
$nomor_surat = !empty($surat['nomor_surat'])
    ? htmlspecialchars($surat['nomor_surat'])
    : '470/'; // Harus diganti dengan sistem nomor otomatis di backend

// Helper untuk teks panjang
function safeNl2br($text)
{
    return nl2br(htmlspecialchars((string)$text));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Surat <?= $judul_surat ?> (<?= $kode_surat ?>)</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 50px;
        }

        .container {
            width: 800px;
            margin: auto;
        }

        .header-kop {
            display: flex;
            align-items: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-logo {
            width: 15%;
            text-align: left;
        }

        .header-text {
            width: 85%;
            text-align: center;
            line-height: 1.1;
        }

        .kop-title {
            font-weight: bold;
            margin: 0;
        }

        .kop-text-1 {
            font-size: 16pt;
        }

        .kop-text-2 {
            font-size: 20pt;
        }

        .kop-alamat {
            font-size: 10pt;
            margin-top: 5px;
        }

        .content {
            margin-top: 20px;
        }

        .center {
            text-align: center;
        }

        .underline {
            text-decoration: underline;
        }

        .indented {
            margin-left: 50px;
            text-align: justify;
            text-indent: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            margin-top: 5px;
        }

        td {
            padding: 5px 0;
            vertical-align: top;
        }

        .td-label {
            width: 200px;
        }

        .ttd-box {
            width: 50%;
            float: right;
            text-align: center;
            margin-top: 50px;
        }

        @media print {
            .no-print {
                display: none;
            }

            .container {
                width: 19cm;
                margin: 0 auto;
            }

            body {
                padding: 0.5cm;
            }
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
                <?= strtoupper($judul_surat); ?>
            </p>
            <p style="margin-top: 0;">
                Nomor: <?= $nomor_surat; ?>/<?= $kode_surat; ?>/<?= date('Y'); ?>
            </p>
        </div>

        <div class="content">
            <p class="indented">
                Yang bertanda tangan di bawah ini <?= htmlspecialchars($pejabat_ttd['jabatan'] ?? 'Pejabat Desa') ?> Desa Kuncir, menerangkan bahwa:
            </p>

            <table>
                <?php if ($kode_surat === 'SKBN'): ?>
                    <tr>
                        <td class="td-label">Nama Lama</td>
                        <td>: <?= htmlspecialchars($surat['nama_lama'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">Nama Baru</td>
                        <td>: <?= htmlspecialchars($surat['nama_baru'] ?? '-'); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="td-label">Nama Lengkap</td>
                        <td>: <?= htmlspecialchars($surat['nama_pemohon'] ?? $surat['nama'] ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td class="td-label">NIK</td>
                    <td>: <?= htmlspecialchars($surat['nik'] ?? '-'); ?></td>
                </tr>

                <?php if (in_array($kode_surat, $with_ttl_alamat)): ?>
                    <tr>
                        <td class="td-label">Tempat, Tgl Lahir</td>
                        <td>: <?= htmlspecialchars($surat['tempat_tanggal_lahir'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">Alamat</td>
                        <td>: <?= htmlspecialchars($surat['alamat'] ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if (in_array($kode_surat, $with_agama_jk)): ?>
                    <tr>
                        <td class="td-label">Agama</td>
                        <td>: <?= htmlspecialchars($surat['agama'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">Jenis Kelamin</td>
                        <td>: <?= htmlspecialchars($surat['jenis_kelamin'] ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if ($kode_surat === 'SKBB'): ?>
                    <tr>
                        <td class="td-label">Pendidikan</td>
                        <td>: <?= htmlspecialchars($surat['pendidikan'] ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if (in_array($kode_surat, $with_pekerjaan)): ?>
                    <tr>
                        <td class="td-label">Pekerjaan</td>
                        <td>: <?= htmlspecialchars($surat['pekerjaan'] ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if ($kode_surat === 'SKD'): ?>
                    <tr>
                        <td class="td-label">Status Perkawinan</td>
                        <td>: <?= htmlspecialchars($surat['status_perkawinan'] ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if ($kode_surat === 'SKK' || $kode_surat === 'SKKM'): ?>
                    <tr>
                        <td class="td-label">Kewarganegaraan</td>
                        <td>: <?= htmlspecialchars($surat['kewarganegaraan'] ?? '-'); ?></td>
                    </tr>
                <?php endif; ?>
            </table>

            <?php if ($kode_surat === 'SKK'): // Surat Kehilangan 
            ?>
                <p class="indented">
                    Berdasarkan keterangan yang bersangkutan, telah benar-benar kehilangan:
                </p>
                <table>
                    <tr>
                        <td class="td-label">Keterangan Kehilangan</td>
                        <td>: <?= safeNl2br($surat['keterangan'] ?? ''); ?></td>
                    </tr>
                </table>

            <?php elseif ($kode_surat === 'SKD'): // Surat Domisili 
            ?>
                <p class="indented">
                    Berdasarkan data kependudukan, nama yang bersangkutan benar-benar berdomisili di wilayah Desa Kuncir.
                    Surat ini dibuat untuk keperluan<?= htmlspecialchars($surat['keterangan'] ?? '-'); ?>**.
                </p>

            <?php elseif ($kode_surat === 'SKBN'): // Surat Keterangan Beda Nama 
            ?>
                <p class="indented">
                    Menerangkan bahwa nama yang bersangkutan tercantum dalam dokumen kependudukan dengan perbedaan
                    sebagai berikut:
                </p>
                <table>
                    <tr>
                        <td class="td-label">Keterangan Perbedaan</td>
                        <td>: <?= safeNl2br($surat['keterangan'] ?? ''); ?></td>
                    </tr>
                </table>
                <p class="indented">
                    Perbedaan nama ini telah dikonfirmasi dan disahkan oleh Pemerintah Desa Kuncir.
                </p>

            <?php elseif ($kode_surat === 'SKU'): // Surat Keterangan Usaha 
            ?>
                <p class="indented">
                    Menerangkan bahwa nama yang bersangkutan benar-benar memiliki usaha:
                </p>
                <table>
                    <tr>
                        <td class="td-label">Nama Usaha</td>
                        <td>: <?= htmlspecialchars($surat['nama_usaha'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">Lokasi Usaha</td>
                        <td>: <?= htmlspecialchars($surat['alamat_usaha'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">Jenis Usaha</td>
                        <td>: <?= htmlspecialchars($surat['jenis_usaha'] ?? $surat['sektor_usaha'] ?? '-'); ?></td>
                    </tr>
                </table>

            <?php elseif ($kode_surat === 'SKTM'): // Surat Keterangan Tidak Mampu 
            ?>
                <p class="indented">
                    Berdasarkan data yang ada, nama yang bersangkutan berasal dari keluarga tidak mampu. Surat ini dibuat untuk:
                </p>
                <table>
                    <tr>
                        <td class="td-label">Keperluan</td>
                        <td>: <?= safeNl2br($surat['keperluan'] ?? ''); ?></td>
                    </tr>
                </table>
                <p style="font-weight: bold; margin: 10px 0;">Data Orang Tua / Wali:</p>
                <table>
                    <tr>
                        <td class="td-label">Nama Orang Tua</td>
                        <td>: <?= htmlspecialchars($surat['nama_orangtua'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">NIK Orang Tua</td>
                        <td>: <?= htmlspecialchars($surat['nik_orangtua'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">Alamat Orang Tua</td>
                        <td>: <?= htmlspecialchars($surat['alamat_orangtua'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">Pekerjaan Orang Tua</td>
                        <td>: <?= htmlspecialchars($surat['pekerjaan_orangtua'] ?? '-'); ?></td>
                    </tr>
                </table>

            <?php elseif ($kode_surat === 'SKKM'): // Surat Keterangan Kematian 
            ?>
                <p class="indented">
                    Dengan ini menerangkan bahwa telah meninggal dunia pada:
                </p>
                <table>
                    <tr>
                        <td class="td-label">Hari / Tanggal</td>
                        <td>: <?= htmlspecialchars($surat['hari_meninggal'] ?? '-'); ?> / <?= htmlspecialchars($surat['tanggal_meninggal'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="td-label">Penyebab Kematian</td>
                        <td>: <?= htmlspecialchars($surat['keterangan'] ?? '-'); ?></td>
                    </tr>
                </table>
                <p class="indented">
                    Surat keterangan ini dibuat berdasarkan laporan dari pihak keluarga.
                </p>

            <?php elseif ($kode_surat === 'SKBB'): // Surat Berkelakuan Baik 
            ?>
                <p class="indented">
                    Berdasarkan catatan yang ada, nama yang bersangkutan dikenal baik di lingkungan masyarakat Desa Kuncir dan tidak pernah tersangkut perkara kriminal.
                    Surat ini dibuat untuk keperluan<?= htmlspecialchars($surat['keterangan'] ?? '-'); ?>
                </p>
            <?php endif; ?>

            <p class="indented" style="margin-top: 25px;">
                Demikian Surat Keterangan ini dibuat dengan sesungguhnya untuk dapat dipergunakan sebagaimana mestinya.
            </p>
        </div>

        <div class="ttd-box">
            Desa Kuncir, <?= $tanggal_cetak_indo; ?>
            <br>
            <?= strtoupper(htmlspecialchars($pejabat_ttd['jabatan'])); ?>
            <br><br>
            <?php if (!empty($pejabat_ttd['barcode'])): ?>
                <!-- Jika Anda ingin tampilkan QR Code, uncomment dan sesuaikan path -->
                <img src="../assets/img/TTD_Ferdian.png"<?= htmlspecialchars($pejabat_ttd['barcode']); ?> alt="QR Code" style="width: 100px; height: auto;">
                <div style="height: 100px; display: flex; align-items: center; justify-content: center;">
                </div>
            <?php else: ?>
                <!-- <br><br><br><br> -->
            <?php endif; ?>
            <span class="underline">
                <?= strtoupper(htmlspecialchars($pejabat_ttd['nama'])); ?>
            </span>
            <br>
            <?php if (!empty($pejabat_ttd['nip'])): ?>
                NIP. <?= htmlspecialchars($pejabat_ttd['nip']); ?>
            <?php endif; ?>
        </div>

        <div style="clear: both;"></div>

        <div class="no-print center" style="margin-top: 50px;">
            <button onclick="window.print()" style="padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; font-size: 16px;">
                🖨️ Cetak Dokumen
            </button>
        </div>

    </div>

</body>

</html>