<?php
// Pastikan file koneksi tersedia
require("../koneksi.php"); // Diakses dari folder 'cetak/'

// Definisikan array whitelist untuk keamanan: table dan judul lengkap
$validTables = [
    'SKK' => ['table' => 'surat_kehilangan', 'judul' => 'SURAT KETERANGAN KEHILANGAN'], 
    'SKBB' => ['table' => 'surat_berkelakuan_baik', 'judul' => 'SURAT KETERANGAN BERKELAKUAN BAIK'], 
    'SKD' => ['table' => 'surat_domisili', 'judul' => 'SURAT KETERANGAN DOMISILI'], 
    'SKTM' => ['table' => 'surat_sktm', 'judul' => 'SURAT KETERANGAN TIDAK MAMPU'], 
    'SKKM' => ['table' => 'surat_keterangan_kematian', 'judul' => 'SURAT KETERANGAN KEMATIAN'], 
    'SKBN' => ['table' => 'surat_keterangan_beda_nama', 'judul' => 'SURAT KETERANGAN BEDA NAMA'], 
    'SKU'  => ['table' => 'surat_keterangan_usaha', 'judul' => 'SURAT KETERANGAN USAHA'],
    // Tambahkan kode surat lain di sini
];

// Ambil dan validasi parameter dari URL
$no_pengajuan = $_GET['no_pengajuan'] ?? '';
$kode_surat = strtoupper($_GET['kode_surat'] ?? '');
$ttd_pilihan = $_GET['ttd'] ?? 'kepaladesa'; // Default tanda tangan

if (empty($no_pengajuan) || empty($kode_surat) || !array_key_exists($kode_surat, $validTables)) {
    // Pesan error ini muncul jika salah satu parameter kunci kosong atau kode surat tidak terdaftar
    die("Error: Parameter pengajuan tidak lengkap atau kode surat tidak valid.");
}

// Ambil data yang dibutuhkan dari array $validTables
$table_name = $validTables[$kode_surat]['table'];
$judul_surat = $validTables[$kode_surat]['judul']; 

// --- FUNGSI AMBIL DATA SURAT ---
function getSuratData($conn, $table_name, $no_pengajuan) {
    // Ambil data surat + nama pemohon dari pengajuan_surat
    $query = "SELECT 
                s.*, 
                p.nama AS nama_pemohon
              FROM `$table_name` s
              LEFT JOIN pengajuan_surat p 
                    ON s.no_pengajuan = p.no_pengajuan
              WHERE s.no_pengajuan = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) die("Gagal prepare data surat: " . $conn->error);

    $stmt->bind_param("s", $no_pengajuan); 
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

// --- FUNGSI AMBIL DATA PEJABAT DESA ---
function getPejabatData($conn, $jabatan_key) {

    $map = [
        'kepaladesa' => 'Kepala Desa',
        'sekretaris' => 'Sekretaris Desa'
    ];

    // Jika pilihan tidak ada, return null
    if (!isset($map[$jabatan_key])) {
        return null;
    }

    $jabatan = $map[$jabatan_key];

    $query = "SELECT nama, nip, jabatan, barcode  
              FROM pejabat_desa 
              WHERE jabatan = ?";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) return null;

    $stmt->bind_param("s", $jabatan);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    return $data;
}


// 1. Ambil data surat
$surat = getSuratData($conn, $table_name, $no_pengajuan);

// 2. Ambil data pejabat TTD
$pejabat_ttd = getPejabatData($conn, $ttd_pilihan);

// 3. Logika Fallback TTD: Jika data pejabat tidak ditemukan di DB, gunakan nilai default
if (!$pejabat_ttd) {
    $pejabat_ttd = [
        'nama' => 'Pejabat Belum Terdaftar',
        'nip' => 'NIP. -',
        'jabatan' => 'Pejabat Desa',
        'barcode' => null
    ];
}

$surat = getSuratData($conn, $table_name, $no_pengajuan);
$pejabat_ttd = getPejabatData($conn, $ttd_pilihan);

if (!$surat) {
    die("Data surat tidak ditemukan untuk No. Pengajuan: " . htmlspecialchars($no_pengajuan));
}

// Format tanggal Indonesia
setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'indonesian'); 
$tanggal_cetak_indo = strftime('%d %B %Y'); 

// Memanggil template surat dan menampilkan data
require 'cetak_surat.php'; 
?>