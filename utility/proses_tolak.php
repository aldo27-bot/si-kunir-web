<?php
// utility/proses_tolak.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $alasan = $_POST['alasan'];

    require("../koneksi.php");

    $sql = "UPDATE `laporan` SET status='Tolak', alasan = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "si", $alasan, $id);

    $eksekusi = mysqli_stmt_execute($stmt);

    if ($eksekusi) {
        mysqli_stmt_close($stmt);

        mysqli_close($conn);

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    echo "Invalid request. 'id' parameter is missing.";
}
?>