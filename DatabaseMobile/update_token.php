<?php
include 'koneksi.php';

$username = $_POST['username'] ?? '';
$token = $_POST['token'] ?? '';

if ($username == '' || $token == '') {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    exit;
}

// 🔹 Update token FCM user
$sql = "UPDATE akun_user SET fcm_token = ? WHERE username = ?";
$stmt = mysqli_prepare($konek, $sql);
mysqli_stmt_bind_param($stmt, "ss", $token, $username);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => true, "message" => "Token berhasil diperbarui"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal memperbarui token"]);
}

mysqli_stmt_close($stmt);
mysqli_close($konek);
?>
