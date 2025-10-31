<?php
require('../Koneksi.php');

    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $tempat = $_POST['tempat_tanggal_lahir'];
    $kebangsaan = $_POST['kebangsaan'];
    $agama = $_POST['agama'];
    $status = $_POST['status_perkawinan'];
    $pekerjaan = $_POST['pekerjaan'];
    $tinggal = $_POST['alamat'];
    $username =$_POST['username'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    $sql ="INSERT INTO `skck`( `nama`, `nik`, `tempat_tgl_lahir`, 
    `kebangsaan`, `agama`, `status_perkawinan`, `pekerjaan`, `alamat`,`username`,`jenis_kelamin`) 
    VALUES ('$nama','$nik','$tempat','$kebangsaan','$agama','$status','$pekerjaan','$tinggal','$username','$jenis_kelamin')";

    $eksekusi = mysqli_query($konek, $sql);

    $response = array();

    if ($eksekusi) {
        // Jika insert berhasil
        $response['kode'] = true;
        $response['pesan'] = "Data berhasil ditambahkan";
    } else {
        // Jika insert gagal
        $response['kode'] = false;
        $response['pesan'] = "Gagal menambahkan data. Error: " . mysqli_error($konek);
    }

    echo json_encode($response);
    mysqli_close($konek);
?>
