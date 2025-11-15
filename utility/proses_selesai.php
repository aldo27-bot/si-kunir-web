<?php
session_start();

// Redirect langsung jika bukan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Akses tidak diizinkan.";
    header("Location: ../suratmasuk.php");
    exit();
}

// Ambil dan validasi ID
$id_pengajuan_surat = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id_pengajuan_surat || $id_pengajuan_surat <= 0) {
    $_SESSION['error_message'] = "ID pengajuan tidak valid.";
    header("Location: ../suratmasuk.php");
    exit();
}

// Koneksi database
require_once "../koneksi.php";

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['error_message'] = "Koneksi database gagal.";
    header("Location: ../suratmasuk.php");
    exit();
}

// Mulai transaksi
$success = false;
$error_message = '';

mysqli_autocommit($conn, false); // nonaktifkan autocommit

try {
    // 1. Ambil id_laporan
    $stmt = mysqli_prepare($conn, "SELECT id_laporan FROM pengajuan_surat WHERE id_pengajuan_surat = ?");
    if (!$stmt) throw new Exception("Gagal menyiapkan query SELECT.");
    
    mysqli_stmt_bind_param($stmt, "i", $id_pengajuan_surat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$row || empty($row['id_laporan'])) {
        throw new Exception("ID laporan tidak ditemukan.");
    }

    $id_laporan = (int)$row['id_laporan'];

    // 2. Update status
    $stmt2 = mysqli_prepare($conn, "UPDATE laporan SET status = 'Selesai' WHERE id_laporan = ?");
    if (!$stmt2) throw new Exception("Gagal menyiapkan query UPDATE.");
    
    mysqli_stmt_bind_param($stmt2, "i", $id_laporan);
    if (!mysqli_stmt_execute($stmt2)) {
        throw new Exception("Gagal mengupdate status.");
    }
    mysqli_stmt_close($stmt2);

    // Commit
    mysqli_commit($conn);
    $success = true;

} catch (Exception $e) {
    mysqli_rollback($conn);
    $error_message = $e->getMessage();
}

mysqli_autocommit($conn, true);
mysqli_close($conn);

// Set pesan dan redirect
if ($success) {
    $_SESSION['success_message'] = "Surat berhasil ditandai sebagai selesai.";
} else {
    $_SESSION['error_message'] = "Gagal menyelesaikan surat. " . $error_message;
}

header("Location: ../suratmasuk.php");
exit();
?>