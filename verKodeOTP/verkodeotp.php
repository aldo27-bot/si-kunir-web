<?php
// include "../verKodeOTP/index.html";
include "../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$username ='admin' ;
$kode_otp = $_POST['kode_otp'];

$query = "SELECT * FROM akun_admin WHERE kode_otp = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kode_otp);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    header("Location: ../ubahpassword/");
    exit();
}else{
    $erorMessage = "kode_otp salah!!!";
    echo '<script>';
    echo 'alert("'.$erorMessage.'");';
    echo 'window.location.href = "index.html";';
    echo '</script>';
}
}
?>