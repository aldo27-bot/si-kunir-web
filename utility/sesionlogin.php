<?php
include 'koneksi.php';

if (!$conn) {
    die("Koneksi ke database gagal.");
}
// $user = $_GET['user'];
session_start();
$user = $_SESSION['username_admin'];

$query = "SELECT * FROM akun_admin WHERE username_admin = ? ";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {

} else {
    header("Location: login.php");
}
?>