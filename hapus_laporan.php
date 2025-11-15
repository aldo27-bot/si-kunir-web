<?php
session_start();
include "koneksi.php";

if (!isset($_GET['id'])) {
    die("ID laporan tidak ditemukan.");
}

$id_laporan = intval($_GET['id']);

mysqli_begin_transaction($conn);

try {

    // Ambil no_pengajuan dari pengajuan_surat
    $stmt1 = mysqli_prepare($conn, "SELECT no_pengajuan FROM pengajuan_surat WHERE id_laporan = ?");
    mysqli_stmt_bind_param($stmt1, "i", $id_laporan);
    mysqli_stmt_execute($stmt1);
    $result = mysqli_stmt_get_result($stmt1);

    if ($result->num_rows == 0) {
        throw new Exception("Data pengajuan tidak ditemukan.");
    }

    $row = $result->fetch_assoc();
    $no_pengajuan = $row['no_pengajuan'];

    mysqli_stmt_close($stmt1);


    // Hapus data dari pengajuan_surat
    $stmt2 = mysqli_prepare($conn, "DELETE FROM pengajuan_surat WHERE id_laporan = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id_laporan);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);


    // Hapus data dari laporan
    $stmt3 = mysqli_prepare($conn, "DELETE FROM laporan WHERE id_laporan = ?");
    mysqli_stmt_bind_param($stmt3, "i", $id_laporan);
    mysqli_stmt_execute($stmt3);
    mysqli_stmt_close($stmt3);


    mysqli_commit($conn);

    header("Location: laporan.php?msg=deleted");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    die("Gagal menghapus data: " . $e->getMessage());
}
?>
