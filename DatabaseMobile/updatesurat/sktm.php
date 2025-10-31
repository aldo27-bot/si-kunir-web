<?php
include("../Koneksi.php");

$kode = $_POST['kode'] ?? null;
$no = $_POST['no_pengajuan'] ?? null;
// echo $no."<br>";

$sql = "SELECT sktm.*, pengajuan_surat.id FROM sktm 
INNER JOIN pengajuan_surat
on sktm.no_pengajuan = pengajuan_surat.no_pengajuan
WHERE sktm.no_pengajuan ='$no'";
$result = $konek->query($sql);

$response = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $id = $row['id'];
}

if ($kode == 0) {
    include("../Koneksi.php");
    
    // $no = $_POST['no_pengajuan'];
    
    $sql = "SELECT * FROM sktm WHERE no_pengajuan ='$no'";
    $result = $konek->query($sql);
    
    $response = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Pisahkan tempat dan tanggal bapak menggunakan koma sebagai pemisah
        $hasil_bapak = explode(", ", $row["tempat_tanggal_lahir_bapak"]);
        $tempat_lahir_bapak = $hasil_bapak[0];
        $tanggal_lahir_bapak = $hasil_bapak[1];
        
        // Pisahkan tempat dan tanggal ibu menggunakan koma sebagai pemisah
        $hasil_ibu = explode(", ", $row["tempat_tanggal_lahir_ibu"]);
        $tempat_lahir_ibu = $hasil_ibu[0];
        $tanggal_lahir_ibu = $hasil_ibu[1];
        
        // Pisahkan tempat dan tanggal anak menggunakan koma sebagai pemisah
        $hasil_anak = explode(", ", $row["tempat_tanggal_lahir_anak"]);
        $tempat_lahir_anak = $hasil_anak[0];
        $tanggal_lahir_anak = $hasil_anak[1];
        
        // Tambahkan data ke dalam array
        $response["kode"] = true;
        $response["pesan"] = "Data Tersedia";
        $response["data"] = array();
        $data = array(
            'nama_bapak' => $row['nama_bapak'],
            'tempat_lahir_bapak' => $tempat_lahir_bapak,
            'tanggal_lahir_bapak' => $tanggal_lahir_bapak,
            'pekerjaan_bapak' => $row['pekerjaan_bapak'],
            'alamat_bapak' => $row['alamat_bapak'],
            'nama_ibu' => $row['nama_ibu'],
            'tempat_lahir_ibu' => $tempat_lahir_ibu,
            'tanggal_lahir_ibu' => $tanggal_lahir_ibu,
            'pekerjaan_ibu' => $row['pekerjaan_ibu'],
            'alamat_ibu' => $row['alamat_ibu'],
            'nama_anak' => $row['nama'],
            'tempat_lahir_anak' => $tempat_lahir_anak,
            'tanggal_lahir_anak' => $tanggal_lahir_anak,
            'jenis_kelamin_anak' => $row['jenis_kelamin_anak'],
            'alamat_anak' => $row['alamat']
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

$sql = "UPDATE `sktm` SET 
        `nama_bapak`='$namabapak',
        `tempat_tanggal_lahir_bapak`='$Tempattanggallahirbapak',
        `pekerjaan_bapak`='$pekerjaanbapapak',
        `alamat_bapak`='$alamatbapak',
        `nama_ibu`='$namaibu',
        `tempat_tanggal_lahir_ibu`='$Tempattanggallahiribu',
        `pekerjaan_ibu`='$pekerjaanibu',
        `alamat_ibu`='$alamatibu',
        `nama`='$namaanak',
        `tempat_tanggal_lahir_anak`='$Tempattanggallahiranak',
        `jenis_kelamin_anak`='$jeniskelaminanak',
        `alamat`='$alamatanak'
        WHERE `no_pengajuan`='$no'";
$eksekusi = mysqli_query($konek, $sql);

$sql = "UPDATE `laporan` SET 
    `status`='Masuk'
    WHERE `id`='$id'";
    $eksekusi = mysqli_query($konek, $sql);

    $response = array();

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
$response['kode'] = false;
$response['pesan'] = "Error 404 not found";

echo json_encode($response);
// mysqli_close($konek);
}
?>