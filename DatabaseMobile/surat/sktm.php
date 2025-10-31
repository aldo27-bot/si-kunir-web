<?php
require("../Koneksi.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];

    $namabapak = $_POST['nama_bapak'];
    $Tempattanggallahirbapak = $_POST['tempat_tanggal_lahir_bapak'];
    $pekerjaanbapapak = $_POST['pekerjaan_bapak'];
    $alamatbapak = $_POST['alamat_bapak'];

    //ibu
    $namaibu = $_POST['nama_ibu'];
    $Tempattanggallahiribu = $_POST['tempat_tanggal_lahir_ibu'];
    $pekerjaanibu = $_POST['pekerjaan_ibu'];
    $alamatibu = $_POST['alamat_ibu'];

    //anak
    $namaanak = $_POST['nama'];
    $Tempattanggallahiranak = $_POST['tempat_tanggal_lahir_anak'];
    $jeniskelaminanak = $_POST['jenis_kelamin_anak'];
    $alamatanak = $_POST['alamat'];

    $sql = "INSERT INTO `sktm`( `username`, `nama_bapak`, `tempat_tanggal_lahir_bapak`, `pekerjaan_bapak`, `alamat_bapak`, `nama_ibu`, `tempat_tanggal_lahir_ibu`, `pekerjaan_ibu`, `alamat_ibu`,
             `nama`, `tempat_tanggal_lahir_anak`, `jenis_kelamin_anak`, `alamat`)
            VALUES ('$username','$namabapak','$Tempattanggallahirbapak','$pekerjaanbapapak','$alamatbapak','$namaibu','$Tempattanggallahiribu','$pekerjaanibu','$alamatibu',
            '$namaanak','$Tempattanggallahiranak','$jeniskelaminanak','$alamatanak')";

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
}
?>