<?php
// utility/proses_selesai.php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['no_pengajuan']) ? $_GET['no_pengajuan'] : null;

    if ($id) {
        require("../koneksi.php");

        $sql = "UPDATE `laporan` SET status='Selesai' WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        $eksekusi = mysqli_stmt_execute($stmt);

        if ($eksekusi) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';
            header("Location: " . $redirectUrl);
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Parameter 'no_pengajuan' tidak ditemukan.";
    }
} else {
    echo "Invalid request. 'no_pengajuan' and 'kode_surat' parameters are missing.";
}
?>
