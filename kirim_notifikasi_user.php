<?php
require __DIR__ . '/DatabaseMobile/vendor/autoload.php';
include 'koneksi.php';

use GuzzleHttp\Client;
use Google\Auth\Credentials\ServiceAccountCredentials;

// Pastikan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['kode' => 0, 'pesan' => 'Metode tidak diizinkan']);
    exit;
}

// Ambil data POST
$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? '';
$tanggapan = $_POST['tanggapan'] ?? '';

if (!$id) {
    echo json_encode(['kode' => 0, 'pesan' => 'ID aspirasi tidak dikirim']);
    exit;
}

// 🔹 Ambil data aspirasi
$qUser = "SELECT username, judul, status, tanggapan FROM aspirasi WHERE id = '$id'";
$rUser = mysqli_query($conn, $qUser);

if (!$rUser || mysqli_num_rows($rUser) == 0) {
    echo json_encode(['kode' => 0, 'pesan' => 'Aspirasi tidak ditemukan']);
    exit;
}

$data = mysqli_fetch_assoc($rUser);
$username = $data['username'];
$judul = $data['judul'];
$status = $data['status'];
$tanggapan = $data['tanggapan'];

// 🔹 Ambil FCM token user
$qToken = "SELECT fcm_token FROM akun_user WHERE username = '$username'";
$rToken = mysqli_query($conn, $qToken);

if (!$rToken || mysqli_num_rows($rToken) == 0) {
    echo json_encode(['kode' => 0, 'pesan' => "Token FCM tidak ditemukan untuk user $username"]);
    exit;
}

$rowToken = mysqli_fetch_assoc($rToken);
$token = $rowToken['fcm_token'];

if (empty($token)) {
    echo json_encode(['kode' => 0, 'pesan' => "Token FCM kosong untuk user $username"]);
    exit;
}

// 🔹 Path ke service account Firebase
$pathToServiceAccount = __DIR__ . '/firebase-key.json';
if (!file_exists($pathToServiceAccount)) {
    echo json_encode(['kode' => 0, 'pesan' => 'File firebase-key.json tidak ditemukan']);
    exit;
}

// 🔹 Ambil akses token Google
$scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
$credentials = new ServiceAccountCredentials($scopes, $pathToServiceAccount);
$accessToken = $credentials->fetchAuthToken()['access_token'];

// 🔹 Project ID Firebase
$projectId = 'elayangdesa-5d5c3'; // <-- Ganti dengan Project ID kamu

// 🔹 Client Guzzle
$client = new Client([
    'base_uri' => 'https://fcm.googleapis.com/v1/',
    'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type'  => 'application/json',
    ],
]);

// 🔹 Siapkan payload
$payload = [
    'message' => [
        'token' => $token,
        'notification' => [
            'title' => "Balasan Aspirasi: $judul",
            'body'  => "Status: $status\n$tanggapan"
        ],
        'data' => [
            'id' => (string)$id,
            'username' => $username,
            'status' => $status,
            'tanggapan' => $tanggapan,
            'click_action' => 'OPEN_ASPIRASI'
        ],
    ]
];

// 🔹 Kirim notifikasi
try {
    $response = $client->post("projects/$projectId/messages:send", [
        'json' => $payload
    ]);

    $result = json_decode($response->getBody(), true);

    // Log respons ke file
    file_put_contents('log_fcm.txt', json_encode($result, JSON_PRETTY_PRINT));

    echo json_encode([
        'kode' => 1,
        'pesan' => "Notifikasi berhasil dikirim ke $username",
        'fcm_result' => $result
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    file_put_contents('log_fcm.txt', 'Error: ' . $e->getMessage());
    echo json_encode([
        'kode' => 0,
        'pesan' => 'Gagal mengirim notifikasi',
        'error' => $e->getMessage()
    ]);
}
?>
