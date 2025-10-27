<?php
require('Koneksi.php');

// Menerima data dari Android
$email = isset($_POST['email']) ? trim($_POST['email']) : null;

if (!$email) {
    echo json_encode([
        'kode' => 0,
        'pesan' => 'Email dibutuhkan'
    ]);
    exit;
}

// Cek user di database
$perintah = "SELECT * FROM `akun_user` WHERE email = ?";
$stmt = $konek->prepare($perintah);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$response = [];

if ($result->num_rows > 0) {
    $user = $result->fetch_object();

    $response["kode"] = 1;
    $response["pesan"] = "Akun Terdaftar";
    $response["data"] = [
        [
            "username" => $user->username,
            "email" => $user->email,
            "nama" => $user->nama,
            "kode_otp" => $user->kode_otp
        ]
    ];
} else {
    $response["kode"] = 0;
    $response["pesan"] = "Akun Tidak Terdaftar Silahkan Register";
}

echo json_encode($response);
$stmt->close();
$konek->close();
?>
