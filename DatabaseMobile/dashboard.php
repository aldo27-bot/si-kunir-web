<?php
require('koneksi.php');

$username = $_POST['username'] ?? '';

$sql = "SELECT 
        SUM(CASE WHEN laporan.status = 'Selesai' THEN 1 ELSE 0 END) AS Selesai,
        SUM(CASE WHEN laporan.status = 'Masuk' THEN 1 ELSE 0 END) AS Masuk,
        SUM(CASE WHEN laporan.status = 'Tolak' THEN 1 ELSE 0 END) AS Tolak
        FROM laporan
        INNER JOIN pengajuan_surat 
            ON laporan.id_laporan = pengajuan_surat.id_laporan
        WHERE pengajuan_surat.username = '$username';";

$eksekusi = mysqli_query($konek, $sql);

$response = array();

if ($eksekusi) {
    if (mysqli_num_rows($eksekusi) > 0) {
        $ambil = mysqli_fetch_object($eksekusi);
        $response['kode'] = true;
        $response['pesan'] = "Berhasil Mengambil Data";
        $response["data"] = array();
        $data["Selesai"] = $ambil->Selesai ?? "0";
        $data["Masuk"] = $ambil->Masuk ?? "0";
        $data["Tolak"] = $ambil->Tolak ?? "0";
        array_push($response["data"], $data);
    } else {
        $response['kode'] = false;
        $response['pesan'] = "Tidak ada data yang sesuai";
    }
} else {
    $response['kode'] = false;
    $response['pesan'] = mysqli_error($konek);
}

echo json_encode($response);
mysqli_close($konek);
?>
