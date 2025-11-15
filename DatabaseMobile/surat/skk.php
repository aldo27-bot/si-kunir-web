<?php

// Pastikan semua error PHP ditampilkan saat debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Koneksi Database
require("../Koneksi.php"); 

header('Content-Type: application/json');

// Cek apakah $konek berhasil dibuat dan cek error koneksi
if (!isset($konek) || $konek->connect_error) { 
    $error_msg = isset($konek) ? $konek->connect_error : "Variabel koneksi (\$konek) tidak terdefinisi. Cek file Koneksi.php.";
    echo json_encode(["kode" => false, "pesan" => "Koneksi database gagal: " . $error_msg]);
    exit();
}

// 2. Ambil & Validasi Data (Semua data teks ada di $_POST)
// Menggunakan operator coalescing ?? untuk keamanan
$username = $_POST['username'] ?? '';
$nama = $_POST['nama'] ?? '';
$agama = $_POST['agama'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$ttl = $_POST['tempat_tanggal_lahir'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$kewarganegaraan = $_POST['kewarganegaraan'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$kode_surat = $_POST['kode_surat'] ?? 'SKK';

// Cek data wajib
if (empty($username) || empty($nama) || empty($keterangan)) {
    echo json_encode(["kode" => false, "pesan" => "Data wajib (username/nama/keterangan) tidak lengkap."]);
    $konek->close();
    exit();
}

// 3. Penanganan File Upload
$file_upload = NULL;
// Gunakan path absolut/relatif yang benar dari lokasi file PHP Anda ke folder upload
$target_dir = "../surat/upload_surat/"; // Mengasumsikan folder upload_surat berada satu tingkat di atas direktori file surat/skk.php

if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    
    // Cek apakah direktori ada, jika tidak, coba buat
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
             echo json_encode(["kode" => false, "pesan" => "Gagal membuat folder upload: {$target_dir}. Cek izin folder induk."]);
             $konek->close();
             exit();
        }
    }

    $temp = explode(".", $_FILES["file"]["name"]);
    $newfilename = round(microtime(true)) . '.' . end($temp);
    $target_file = $target_dir . $newfilename;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $file_upload = $newfilename;
    } else {
        // Tambahkan detail error upload file
        $upload_error = $_FILES["file"]["error"];
        echo json_encode(["kode" => false, "pesan" => "Gagal memindahkan file upload. Error code: {$upload_error}."]);
        $konek->close();
        exit();
    }
}

// 4. Prepared Statement
$sql = "INSERT INTO surat_kehilangan 
        (nama, agama, jenis_kelamin, tempat_tanggal_lahir, alamat, kewarganegaraan, keterangan, file, kode_surat, username) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $konek->prepare($sql);

if ($stmt === false) {
    // Tampilkan error prepare (biasanya karena sintaks SQL salah atau kolom tidak ada)
    echo json_encode(["kode" => false, "pesan" => "Gagal mempersiapkan query (Prepare): " . $konek->error]);
    $konek->close();
    exit();
}

// Binding Parameters (10 string parameters: ssssssssss)
$stmt->bind_param("ssssssssss", 
    $nama, 
    $agama, 
    $jenis_kelamin, 
    $ttl, 
    $alamat, 
    $kewarganegaraan, 
    $keterangan, 
    $file_upload, 
    $kode_surat, 
    $username
);

// 5. Eksekusi
if ($stmt->execute()) {
    echo json_encode(["kode" => true, "pesan" => "Surat Kehilangan berhasil diajukan!"]);
} else {
    // ERROR EKSEKUSI! Ini adalah sumber masalah utama Anda.
    $error_pesan = "Gagal eksekusi query (Execute): " . $stmt->error;
    
    // Cek Foreign Key Constraint (Paling Sering Terjadi)
    if (strpos($stmt->error, 'foreign key constraint') !== false) {
        $error_pesan .= " (FK GAGAL! Pastikan 'username' ada di akun_user dan 'SKK' ada di data_surat).";
    }

    echo json_encode(["kode" => false, "pesan" => $error_pesan]);
}

$stmt->close();
$konek->close();
?>