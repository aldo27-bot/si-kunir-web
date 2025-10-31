<?php
require('koneksi.php');

$user = $_POST['username'];

$sql = "SELECT 
        sum(case when laporan.status = 'Selesai' then 1 else 0 end ) as Selesai,
        sum(case when laporan.status = 'Masuk' then 1 else 0 end ) as Masuk,
        sum(case when laporan.status = 'Tolak' then 1 else 0 end ) as Tolak
        FROM `laporan`
        INNER JOIN pengajuan_surat ON laporan.id = pengajuan_surat.id
        WHERE pengajuan_surat.username = '$user';";

$eksekusi = mysqli_query($konek, $sql);

$response = array();

if ($eksekusi) {
    if (mysqli_num_rows($eksekusi) > 0) {
        // Data ditemukan
        $ambil = mysqli_fetch_object($eksekusi);
        $response['kode'] = true;
        $response['pesan'] = "Berhasil Mengambil Data";
        $response["data"] = array();
        $data["Selesai"] = $ambil->Selesai;
        $data["Masuk"] = $ambil->Masuk;
        $data["Tolak"] = $ambil->Tolak;
        array_push($response["data"], $data);
    } else {
        // Tidak ada data yang sesuai
        $response['kode'] = false;
        $response['pesan'] = "Tidak ada data yang sesuai";
    }
} else {
    // Jika query gagal dieksekusi
    $response['kode'] = false;
    $response['pesan'] =  mysqli_error($konek); // Menampilkan pesan kesalahan
}
echo json_encode($response);
mysqli_close($konek);
?>
