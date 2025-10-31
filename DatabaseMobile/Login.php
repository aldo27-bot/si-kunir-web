<?php
require('Koneksi.php');
require('helpers.php');

// Ambil data login
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$hashedPassword = md5($password);

$response = [];

$sql = "SELECT * FROM akun_user WHERE username = ?";
$stmt = mysqli_prepare($konek, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    if ($row['password'] === $hashedPassword) {
        // ✅ Password cocok

        // Response ke Android tanpa token
        $response["kode"] = 1;
        $response["pesan"] = "Login berhasil";
        $response["data"] = [];

        $data["username"] = $row['username'];
        $data["email"] = $row['email'];
        $data["nama"] = $row['nama'];

        array_push($response["data"], $data);
    } else {
        $response["kode"] = 2;
        $response["pesan"] = "Password salah";
    }
} else {
    $response["kode"] = 0;
    $response["pesan"] = "Username tidak ditemukan";
}

echo json_encode($response);
mysqli_close($konek);
?>
