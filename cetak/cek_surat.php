<?php
// Pastikan file koneksi tersedia
require("../koneksi.php");

// Definisikan array whitelist untuk keamanan nama tabel
$validTables = [
    'SKK' => 'surat_kehilangan', 
    'SKBB' => 'surat_berkelakuan_baik', 
    'SKD' => 'surat_domisili', 
    // Tambahkan kode surat lain di sini
];

// Ambil dan validasi parameter dari URL
$no_pengajuan = $_GET['no_pengajuan'] ?? '';
$kode_surat = strtoupper($_GET['kode_surat'] ?? '');
$ttd_pilihan = $_GET['ttd'] ?? 'kepaladesa'; // Default tanda tangan

if (empty($no_pengajuan) || empty($kode_surat) || !array_key_exists($kode_surat, $validTables)) {
    die("Error: Parameter pengajuan tidak lengkap atau kode surat tidak valid.");
}

$table_name = $validTables[$kode_surat];

// --- FUNGSI AMBIL DATA SURAT ---
function getSuratData($conn, $table_name, $no_pengajuan) {
    // Menggunakan Prepared Statement untuk mengambil semua kolom dari tabel surat spesifik
    $query = "SELECT s.*, u.nama AS nama_pemohon 
              FROM `$table_name` s
              LEFT JOIN akun_user u ON s.username = u.username
              WHERE s.no_pengajuan = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) die("Gagal prepare data surat: " . $conn->error);

    // Asumsi no_pengajuan bisa berupa string (s)
    $stmt->bind_param("s", $no_pengajuan); 
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

// --- FUNGSI AMBIL DATA PEJABAT DESA ---
function getPejabatData($conn, $jabatan_key) {
    // Anda harus menentukan bagaimana data pejabat desa disimpan dan dicari
    // Asumsi: Anda menyimpan data pejabat di tabel 'pejabat_desa'
    if ($jabatan_key == 'kepaladesa') {
        $jabatan = 'Kepala Desa';
    } elseif ($jabatan_key == 'sekretaris') {
        $jabatan = 'Sekretaris Desa';
    } else {
        return null;
    }
    
    $query = "SELECT nama, nip, jabatan, barcode FROM pejabat_desa WHERE jabatan = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) return null;
    
    $stmt->bind_param("s", $jabatan);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

$surat = getSuratData($conn, $table_name, $no_pengajuan);
$pejabat_ttd = getPejabatData($conn, $ttd_pilihan);

if (!$surat) {
    die("Data surat tidak ditemukan untuk No. Pengajuan: " . htmlspecialchars($no_pengajuan));
}

// Format tanggal
$tanggal_cetak = date('d F Y');
$tanggal_cetak_indo = strftime('%d %B %Y', strtotime($tanggal_cetak));

// Panggil fungsi render template
include 'surat_template.php';

?>