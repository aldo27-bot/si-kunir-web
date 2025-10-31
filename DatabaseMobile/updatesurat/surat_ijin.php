<?php
include("../Koneksi.php");

$kode = $_POST['kode'] ?? null;
$no = $_POST['no_pengajuan'] ?? null;
// echo $no."<br>";

$sql = "SELECT surat_ijin.*, pengajuan_surat.id FROM surat_ijin 
INNER JOIN pengajuan_surat
on surat_ijin.no_pengajuan = pengajuan_surat.no_pengajuan
WHERE surat_ijin.no_pengajuan ='$no'";
$result = $konek->query($sql);

$response = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $id = $row['id'];
    // echo $id;
}




if ($kode == 0) {
    include("../Koneksi.php");
    
    // $no = $_POST['no_pengajuan'];
    
    $sql = "SELECT * FROM surat_ijin WHERE no_pengajuan ='$no'";
    $result = $konek->query($sql);
    
    $response = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    
        // Pisahkan tempat dan tanggal menggunakan koma sebagai pemisah
        $hasil = explode(", ", $row["Tempat_tanggal_lahir"]);
    
        // $hasil[0] akan berisi tempat, $hasil[1] akan berisi tanggal
        $tempat = $hasil[0];
        $tanggal = $hasil[1];
    
        // Tambahkan data ke dalam array
        $response["kode"] = true;
        $response["pesan"] = "Data Tersedia";
        $response["data"] = array();
        $data = array(
            'Nama' => $row['nama'],
            'Nik' => $row['nik'],
            'tempat' => $tempat,
            'tanggal' => $tanggal,
            'Agama' => $row['agama'],
            'Jenis_kelamin' => $row['jenis_kelamin'],
            'Kewarganegaraan' => $row['kewarganegaraan'],
            'Pekerjaan' => $row['pekerjaan'],
            'Alamat' => $row['alamat'],
            'Tempat_Kerja' => $row['tempat_kerja'],
            'Bagian' => $row['bagian'],
            'Tanggal_Ijin' => $row['tanggal'],
            'Alasan' => $row['alasan']
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
    
    $sql = "UPDATE `surat_ijin` SET 
            `nama`='$Nama',
            `nik`='$NIK',
            `jenis_kelamin`='$Jenis_kelamin',
            `tempat_tanggal_lahir`='$Tempat_tanggal_lahir',
            `kewarganegaraan`='$Kewarganegaraan',
            `agama`='$Agama',
            `pekerjaan`='$Pekerjaan',
            `alamat`='$Alamat',
            `tempat_kerja`='$Tempat_Kerja',
            `bagian`='$Bagian',
            `tanggal`='$Tanggal',
            `alasan`='$Alasan'
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