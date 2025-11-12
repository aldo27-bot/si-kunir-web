<?php
session_start();
// Pastikan file koneksi tersedia, ganti path jika perlu
require("../koneksi.php");

// Definisikan pesan error default
$error_message = "Terjadi kesalahan yang tidak diketahui.";

// 1. Cek Metode dan Ambil ID
// Di halaman suratmasuk.php, tombol "Selesai" mengirim parameter 'id' (yang berisi ID Pengajuan Surat)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_pengajuan_surat = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id_pengajuan_surat) {

        // Mulai Transaksi untuk memastikan dua langkah berhasil
        mysqli_begin_transaction($conn);

        try {
            // --- Langkah 1: Ambil id_laporan dari tabel pengajuan_surat ---
            $sql_select = "SELECT id_laporan FROM `pengajuan_surat` WHERE id_pengajuan_surat = ?";
            $stmt_select = mysqli_prepare($conn, $sql_select);

            if (!$stmt_select) throw new Exception("Prepare SELECT Gagal: " . mysqli_error($conn));

            // Perhatian: Jika id_pengajuan_surat adalah INT, gunakan "i", jika VARCHAR/STRING gunakan "s"
            mysqli_stmt_bind_param($stmt_select, "i", $id_pengajuan_surat); // Asumsi ID adalah Integer (i)
            mysqli_stmt_execute($stmt_select);
            $result = mysqli_stmt_get_result($stmt_select);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt_select);

            if (!$row || empty($row['id_laporan'])) {
                throw new Exception("ID Laporan tidak ditemukan untuk Pengajuan ID: {$id_pengajuan_surat}");
            }

            $id_laporan = $row['id_laporan'];


            // --- Langkah 2: Update status di tabel laporan ---
            $sql_update = "UPDATE `laporan` SET status='Selesai' WHERE id_laporan = ?";
            $stmt_update = mysqli_prepare($conn, $sql_update);

            if (!$stmt_update) throw new Exception("Prepare UPDATE Gagal: " . mysqli_error($conn));

            // Perhatian: id_laporan adalah INT, gunakan "i"
            mysqli_stmt_bind_param($stmt_update, "i", $id_laporan);

            if (!mysqli_stmt_execute($stmt_update)) {
                throw new Exception("Eksekusi UPDATE Gagal: " . mysqli_stmt_error($stmt_update));
            }
            mysqli_stmt_close($stmt_update);

            // Jika semua berhasil, COMMIT Transaksi
            mysqli_commit($conn);

            $_SESSION['success_message'] = "Status pengajuan berhasil diselesaikan!";
        } catch (Exception $e) {
            // Jika ada yang gagal, ROLLBACK
            mysqli_rollback($conn);
            $error_message = "Gagal memproses status. Detail: " . $e->getMessage();
            $_SESSION['error_message'] = $error_message;
        }

        mysqli_close($conn);

        // Redirect kembali ke halaman sebelumnya atau halaman default
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'suratmasuk.php';
        header("Location: " . $redirectUrl);
        exit();
    } else {
        // Jika parameter ID tidak ada
        $error_message = "Parameter ID pengajuan tidak ditemukan.";
        $_SESSION['error_message'] = $error_message;
        header("Location: suratmasuk.php");
        exit();
    }
} else {
    // Jika metode request bukan GET
    $error_message = "Metode permintaan tidak valid.";
    $_SESSION['error_message'] = $error_message;
    header("Location: suratmasuk.php");
    exit();
}
