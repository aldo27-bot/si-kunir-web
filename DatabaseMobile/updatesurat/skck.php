<?php
include("../Koneksi.php");
include 'update.php';

$kode = $_POST['kode'] ?? null;
$no = $_POST['no_pengajuan'] ?? null;
// echo $no."<br>";

$sql = "SELECT skck.*, pengajuan_surat.id FROM skck 
INNER JOIN pengajuan_surat
on skck.no_pengajuan = pengajuan_surat.no_pengajuan
WHERE skck.no_pengajuan ='$no'";
$result = $konek->query($sql);

$response = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $id = $row['id'];
}



if ($kode == 0) {
    include("../Koneksi.php");

    // $no = $_POST['no_pengajuan'];

    $sql = "SELECT * FROM skck WHERE no_pengajuan ='$no'";
    $result = $konek->query($sql);

    $response = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // $id = $row['id'];

        // Pisahkan tempat dan tanggal menggunakan koma sebagai pemisah
        $hasil = explode(", ", $row["tempat_tgl_lahir"]);

        // $hasil[0] akan berisi tempat, $hasil[1] akan berisi tanggal
        $tempat = $hasil[0];
        $tanggal = $hasil[1];

        // Tambahkan data ke dalam array
        $response["kode"] = true;
        $response["pesan"] = "Data Tersedia";
        $response["data"] = array();
        $data = array(
            'nama' => $row['nama'],
            'nik' => $row['nik'],
            'tempat' => $tempat,
            'tanggal' => $tanggal,
            'agama' => $row['agama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'status_perkawinan' => $row['status_perkawinan'],
            'pekerjaan' => $row['pekerjaan'],
            'alamat' => $row['alamat']
        );
        array_push($response["data"], $data);
    } else {
        // Data tidak ditemukan
        $response["kode"] = false;
        $response["pesan"] = "Data Tidak Ada";
    }

    echo json_encode($response);

    $konek->close();


} elseif ($kode == 1) {

    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $tempat = $_POST['tempat_tanggal_lahir'];
    $kebangsaan = $_POST['kebangsaan'];
    $agama = $_POST['agama'];
    $status = $_POST['status_perkawinan'];
    $pekerjaan = $_POST['pekerjaan'];
    $tinggal = $_POST['alamat'];
    // $username = $_POST['username'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    $sql = "UPDATE `skck` SET 
        `nama`='$nama',
        `nik`='$nik',
        `tempat_tgl_lahir`='$tempat',
        `kebangsaan`='$kebangsaan',
        `agama`='$agama',
        `status_perkawinan`='$status',
        `pekerjaan`='$pekerjaan',
        `alamat`='$tinggal',
        -- `username`='$username',
        `jenis_kelamin`='$jenis_kelamin'
        WHERE `no_pengajuan`='$no'";
    $eksekusi = mysqli_query($konek, $sql);

    $sql = "UPDATE `laporan` SET 
    `status`='Masuk'
    WHERE `id`='$id'";
    $eksekusi = mysqli_query($konek, $sql);

    $response = array();

    if ($eksekusi) {
        // Jika insert berhasil
        $response['kode'] = true;
        $response['pesan'] = "Data berhasil ditambahkan";

        $kode_surat = "skck";
        updatelaporan($no,$kode_surat);
       
    } else {
        // Jika insert gagal
        $response['kode'] = false;
        $response['pesan'] = "Gagal menambahkan data. Error: " . mysqli_error($konek);
    }


    echo json_encode($response);
    mysqli_close($konek);

} else {
    $response['kode'] = false;
    $response['pesan'] = "Error 404 not found";

    echo json_encode($response);
    // mysqli_close($konek);
}
?>