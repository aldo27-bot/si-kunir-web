<?php
require("../Koneksi.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $Nama = $_POST['nama'];
    $NIK = $_POST['nik'];
    $Jenis_kelamin = $_POST['jenis_kelamin'];
    $Tempat_tanggal_lahir = $_POST['tempat_tanggal_lahir'];
    $Kewarganegaraan = $_POST['kewarganegaraan'];
    $Agama = $_POST['agama'];
    $Pekerjaan = $_POST['pekerjaan'];
    $Alamat = $_POST['alamat'];
    $Tempat_Kerja = $_POST['tempat_kerja'];
    $Bagian = $_POST['bagian'];
    $Tanggal = $_POST['tanggal'];
    $Alasan = $_POST['alasan'];

    

    // SQL query
    $sql = "INSERT INTO `surat_ijin`(`username`, `nama`, `nik`, `jenis_kelamin`, `tempat_tanggal_lahir`,
     `kewarganegaraan`, `agama`, `pekerjaan`, `alamat`, `tempat_Kerja`, `bagian`, `tanggal`, `alasan`)
            VALUES ('$username', '$Nama', '$NIK', '$Jenis_kelamin', '$Tempat_tanggal_lahir',
             '$Kewarganegaraan', '$Agama', '$Pekerjaan', '$Alamat', '$Tempat_Kerja', '$Bagian', '$Tanggal', '$Alasan')";

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
} else {
    // Handle non-POST requests
    $response['kode'] = false;
    $response['pesan'] = "Invalid request method";
    echo json_encode($response);
}
?>