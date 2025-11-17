<?php
// surat_beda_nama.php (Sudah selaras dan diperkuat error handling)
ob_start();

// Pastikan semua error PHP ditampilkan saat debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Koneksi Database
require("../Koneksi.php"); 

header('Content-Type: application/json');

// Cek koneksi: Jika gagal, kirim JSON error (HANYA INI YANG TERCETAK)
if (!isset($konek) || $konek->connect_error) { 
    ob_clean();
    $error_msg = isset($konek) ? $konek->connect_error : "Variabel koneksi (\$konek) tidak terdefinisi. Cek file Koneksi.php.";
    echo json_encode(["kode" => 0, "pesan" => "Koneksi database gagal: " . $error_msg]);
    ob_end_flush();
    exit();
}

// 2. Ambil & Validasi Data (Semua key selaras dengan Android)
$nama_lama = $_POST['nama_lama'] ?? '';
$nama_baru = $_POST['nama_baru'] ?? '';
$nik = $_POST['nik'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$tempat_tanggal_lahir = $_POST['tempat_tanggal_lahir'] ?? '';
$pekerjaan = $_POST['pekerjaan'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$username = $_POST['username'] ?? '';
$kode_surat = 'SBN';

// Cek data wajib
if (empty($nama_lama) || empty($nama_baru) || empty($nik) || empty($alamat) || empty($tempat_tanggal_lahir) || empty($pekerjaan) || empty($keterangan) || empty($username)) {
    ob_clean();
    echo json_encode(["kode" => 0, "pesan" => "Data wajib tidak lengkap. Pastikan semua field diisi."]);
    $konek->close();
    ob_end_flush();
    exit();
}

// 3. Penanganan File Upload (Logic sudah benar)
$file_upload = NULL;
$target_dir = "../surat/upload_surat/"; 

if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    
    // Cek apakah direktori ada, jika tidak, coba buat
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            ob_clean();
            echo json_encode(["kode" => 0, "pesan" => "Gagal membuat folder upload. Cek izin folder induk."]);
            $konek->close();
            ob_end_flush();
            exit();
        }
    }

    // Buat nama file unik
    $temp = explode(".", $_FILES["file"]["name"]);
    $newfilename = round(microtime(true)) . '.' . end($temp);
    $target_file = $target_dir . $newfilename;

    // Pindahkan file yang di-upload
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $file_upload = $newfilename;
    } else {
        ob_clean();
        echo json_encode(["kode" => 0, "pesan" => "Gagal memindahkan file upload. Error code: " . $_FILES["file"]["error"]]);
        $konek->close();
        ob_end_flush();
        exit();
    }
}

// 4. Prepared Statement
$sql = "INSERT INTO surat_keterangan_beda_nama 
        (nama_lama, nama_baru, nik, alamat, tempat_tanggal_lahir, pekerjaan, keterangan, file, kode_surat, username)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $konek->prepare($sql);

if ($stmt === false) {
    ob_clean();
    echo json_encode(["kode" => 0, "pesan" => "Gagal mempersiapkan query (Prepare): " . $konek->error]);
    $konek->close();
    ob_end_flush();
    exit();
}

// Binding Parameters
$stmt->bind_param("ssssssssss", 
    $nama_lama, 
    $nama_baru, 
    $nik, 
    $alamat, 
    $tempat_tanggal_lahir, 
    $pekerjaan, 
    $keterangan, 
    $file_upload, 
    $kode_surat, 
    $username
);

// 5. Eksekusi
if ($stmt->execute()) {
    ob_clean();
    echo json_encode(["kode" => 1, "pesan" => "Pengajuan Surat Beda Nama berhasil dikirim!"]);
} else {
    ob_clean();
    $error_pesan = "Gagal eksekusi query (Execute): " . $stmt->error;
    echo json_encode(["kode" => 0, "pesan" => $error_pesan]);
}

$stmt->close();
$konek->close();
ob_end_flush();
?>