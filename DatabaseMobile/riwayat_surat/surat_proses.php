<?php
include("../Koneksi.php");

$username = $_POST['username'];

$perintah = "SELECT pengajuan_surat.id_pengajuan_surat, pengajuan_surat.kode_surat, pengajuan_surat.nik, pengajuan_surat.nama, pengajuan_surat.no_pengajuan, laporan.tanggal, laporan.status
FROM `laporan` 
INNER JOIN pengajuan_surat
ON pengajuan_surat.id_laporan = laporan.id_laporan
WHERE  laporan.status = 'Masuk' and pengajuan_surat.username ='$username' 
GROUP by pengajuan_surat.id_pengajuan_surat
order by pengajuan_surat.tanggal desc;";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

$response = array();

if ($cek > 0) {
    $response["kode"] = 1;
    $response["pesan"] = "Data Tersedia";
    $response["data"] = array();

    $ambil = mysqli_fetch_object($eksekusi);
    $F["id_pengajuan_surat"] = $ambil->no_pengajuan;
    $F["kode_surat"] = $ambil->kode_surat;
    $F["nama"] = $ambil->nama;
    $F["nik"] = $ambil->nik;
    $F["no_pengajuan"] = $ambil->no_pengajuan;
    $F["tanggal"] = $ambil->tanggal;
    $F["status"] = $ambil->status;
    array_push($response["data"], $F);

    while ($ambil = mysqli_fetch_object($eksekusi)) {
        $F["id_pengajuan_surat"] = $ambil->no_pengajuan;
        $F["kode_surat"] = $ambil->kode_surat;
        $F["nama"] = $ambil->nama;
        $F["nik"] = $ambil->nik;
        $F["no_pengajuan"] = $ambil->no_pengajuan;
        $F["tanggal"] = $ambil->tanggal;
        $F["status"] = $ambil->status;
        array_push($response["data"], $F);
    }
} else {
    $response["kode"] = 0;
    $response["pesan"] = "Data Tidak Tersedia";
}

echo json_encode($response);
mysqli_close($konek);
?>
