<?php
header("Content-Type: application/json; charset=UTF-8");
include "../Koneksi.php"; // pastikan ini mendefinisikan $konek

// Ambil data dari POST
$nama       = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$nik        = isset($_POST['nik']) ? trim($_POST['nik']) : '';
$agama      = isset($_POST['agama']) ? trim($_POST['agama']) : '';
$ttl        = isset($_POST['tempat_tanggal_lahir']) ? trim($_POST['tempat_tanggal_lahir']) : '';
$pendidikan = isset($_POST['pendidikan']) ? trim($_POST['pendidikan']) : '';
$alamat     = isset($_POST['alamat_lengkap']) ? trim($_POST['alamat_lengkap']) : '';
$kode_surat = isset($_POST['kode_surat']) ? trim($_POST['kode_surat']) : '';
$username   = isset($_POST['username']) ? trim($_POST['username']) : '';
$id_pejabat = isset($_POST['id_pejabat_desa']) && $_POST['id_pejabat_desa'] !== '' 
                ? trim($_POST['id_pejabat_desa']) 
                : NULL;

// Validasi minimal
if (empty($nama) || empty($nik) || empty($kode_surat) || empty($username)) {
    echo json_encode([
        "status" => 0,
        "message" => "Data wajib diisi!"
    ]);
    exit;
}

// Cek dulu apakah kode_surat ada di data_surat
$cek = $konek->prepare("SELECT kode_surat FROM data_surat WHERE kode_surat = ?");
$cek->bind_param("s", $kode_surat);
$cek->execute();
$cek->store_result();

if ($cek->num_rows === 0) {
    echo json_encode([
        "status" => 0,
        "message" => "Kode surat tidak valid!"
    ]);
    exit;
}
$cek->close();

// Prepare insert query
$sql = "INSERT INTO surat_berkelakuan_baik 
        (nama, nik, agama, tempat_tanggal_lahir, pendidikan, alamat_lengkap, kode_surat, id_pejabat_desa, username)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $konek->prepare($sql);
if ($stmt === false) {
    echo json_encode([
        "status" => 0,
        "message" => "Prepare statement gagal",
        "error" => $konek->error
    ]);
    exit;
}

// Bind parameter
$stmt->bind_param(
    "sssssssss",
    $nama,
    $nik,
    $agama,
    $ttl,
    $pendidikan,
    $alamat,
    $kode_surat,
    $id_pejabat,
    $username
);

$query_result = $stmt->execute();

if ($query_result) {
    echo json_encode([
        "status" => 1,
        "message" => "Data berhasil disimpan"
    ]);
} else {
    echo json_encode([
        "status" => 0,
        "message" => "Gagal menyimpan ke database!",
        "error" => $stmt->error
    ]);
}

$stmt->close();
$konek->close();
?>
