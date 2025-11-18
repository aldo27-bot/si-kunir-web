<?php
include("../Koneksi.php");

$username = mysqli_real_escape_string($konek, $_POST['username']); // aman

$response = array();

$perintah = "
SELECT ps.id_pengajuan_surat, ps.kode_surat, ps.nik, ps.nama, ps.no_pengajuan, l.tanggal, l.status
FROM pengajuan_surat ps
INNER JOIN laporan l ON ps.id_laporan = l.id_laporan
WHERE l.status = 'Masuk' AND ps.username ='$username'
ORDER BY ps.tanggal DESC
";

$eksekusi = mysqli_query($konek, $perintah);

if (!$eksekusi) {
    $response["kode"] = 0;
    $response["pesan"] = "Query Error: " . mysqli_error($konek);
    echo json_encode($response);
    exit;
}

if (mysqli_num_rows($eksekusi) > 0) {
    $response["kode"] = 1;
    $response["pesan"] = "Data Tersedia";
    $response["data"] = array();

    while ($ambil = mysqli_fetch_object($eksekusi)) {
        $F = array();
        $F["id_pengajuan_surat"] = $ambil->id_pengajuan_surat;
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

echo json_encode($response, JSON_UNESCAPED_UNICODE);
mysqli_close($konek);
?>
