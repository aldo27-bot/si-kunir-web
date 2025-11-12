<?php
// Terapkan session start jika diperlukan
// session_start(); 
include "../Koneksi.php";

header('Content-Type: application/json');

// Pastikan koneksi berhasil sebelum melanjutkan
if ($konek->connect_error) {
    echo json_encode(["kode" => false, "pesan" => "Koneksi database gagal: " . $konek->connect_error]);
    exit;
}

// ----------------------------------------------------
// MODE 1: MENGAMBIL DATA (GET/READ)
// ----------------------------------------------------
if(isset($_POST['kode']) && $_POST['kode'] == "0"){
    $no = $_POST['no_pengajuan'] ?? '';
    
    // Gunakan Prepared Statement untuk SELECT
    $sql = "SELECT * FROM surat_kehilangan WHERE no_pengajuan = ?";
    $stmt = $konek->prepare($sql);
    
    if ($stmt === false) {
        echo json_encode(["kode" => false, "pesan" => "Gagal prepare SELECT: " . $konek->error]);
        exit;
    }
    
    $stmt->bind_param("s", $no);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    if (count($data) > 0) {
        echo json_encode(["kode" => true, "pesan" => "Data ditemukan", "data" => $data]);
    } else {
        echo json_encode(["kode" => false, "pesan" => "Data tidak ditemukan", "data" => []]);
    }
    
    $stmt->close();
    $konek->close();
    exit; // Wajib keluar setelah mode 0 selesai
}

// ----------------------------------------------------
// MODE 2: MEMPERBARUI DATA (UPDATE)
// ----------------------------------------------------

// Ambil dan bersihkan data
$no = $_POST['no_pengajuan'] ?? '';
$nama = $_POST['nama'] ?? '';
$agama = $_POST['agama'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$tempat_tanggal_lahir = $_POST['tempat_tanggal_lahir'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$kewarganegaraan = $_POST['kewarganegaraan'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';

$params = [
    'nama' => $nama,
    'agama' => $agama,
    'jenis_kelamin' => $jenis_kelamin,
    'tempat_tanggal_lahir' => $tempat_tanggal_lahir,
    'alamat' => $alamat,
    'kewarganegaraan' => $kewarganegaraan,
    'keterangan' => $keterangan,
];

$set_clauses = [];
$bind_types = "";
$bind_values = [];

// Bangun SET clause secara dinamis
foreach ($params as $key => $value) {
    $set_clauses[] = "`$key` = ?";
    $bind_types .= "s";
    $bind_values[] = $value;
}

// 1. Penanganan File Upload
$target_dir = "../upload_surat/"; // Pastikan path ini benar!
$file_uploaded = false;

if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ""){
    $temp = explode(".", $_FILES["file"]["name"]);
    $file_name = time() . "_" . $no . "." . end($temp); // Nama file lebih unik
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // Tambahkan kolom 'file' ke query UPDATE
        $set_clauses[] = "`file` = ?";
        $bind_types .= "s";
        $bind_values[] = $file_name;
        $file_uploaded = true;
    } else {
        echo json_encode(["kode" => false, "pesan" => "Gagal memindahkan file upload."]);
        $konek->close();
        exit;
    }
}

// 2. Query UPDATE menggunakan Prepared Statement
$sql = "UPDATE surat_kehilangan SET " 
     . implode(", ", $set_clauses) 
     . " WHERE no_pengajuan = ?";

// Tambahkan nilai WHERE ke parameter binding
$bind_types .= "s";
$bind_values[] = $no;

$stmt = $konek->prepare($sql);

if ($stmt === false) {
    echo json_encode(["kode" => false, "pesan" => "Gagal prepare UPDATE: " . $konek->error]);
    $konek->close();
    exit;
}

// Lakukan binding
$bind_values_ref = [];
foreach ($bind_values as $key => $value) {
    $bind_values_ref[$key] = &$bind_values[$key];
}
// Panggil bind_param: call_user_func_array diperlukan untuk dynamic binding
call_user_func_array([$stmt, 'bind_param'], array_merge([$bind_types], $bind_values_ref));

// 3. Eksekusi
if($stmt->execute()){
    echo json_encode(["kode" => true, "pesan" => "Berhasil Mengupdate Surat"]);
} else {
    echo json_encode(["kode" => false, "pesan" => "Gagal Mengupdate Surat: " . $stmt->error]);
}

$stmt->close();
$konek->close();
?>