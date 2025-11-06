<?php
require __DIR__ . '/vendor/autoload.php';
include 'koneksi.php';

use GuzzleHttp\Client;
use Google\Auth\Credentials\ServiceAccountCredentials;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // ID aspirasi yang ditanggapi admin

    // 🔹 Ambil data aspirasi
    $qAspirasi = "SELECT username, judul, tanggapan FROM aspirasi WHERE id = '$id'";
    $rAspirasi = mysqli_query($konek, $qAspirasi);

    if ($rAspirasi && mysqli_num_rows($rAspirasi) > 0) {
        $data = mysqli_fetch_assoc($rAspirasi);
        $username = $data['username'];
        $judul = $data['judul'];
        $tanggapan = $data['tanggapan'];

        // 🔹 Ambil token FCM user berdasarkan username
        $qUser = "SELECT fcm_token FROM akun_user WHERE username = '$username'";
        $rUser = mysqli_query($konek, $qUser);

        if ($rUser && mysqli_num_rows($rUser) > 0) {
            $user = mysqli_fetch_assoc($rUser);
            $token = trim($user['fcm_token']);

            if (empty($token)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Token FCM kosong untuk user $username"
                ]);
                exit;
            }

            // 🔹 File service account
            $pathToServiceAccount = __DIR__ . '/firebase-key.json';

            if (!file_exists($pathToServiceAccount)) {
                echo json_encode([
                    "success" => false,
                    "message" => "File firebase-key.json tidak ditemukan di folder API"
                ]);
                exit;
            }

            // 🔹 Ambil token akses Google
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new ServiceAccountCredentials($scopes, $pathToServiceAccount);
            $accessTokenInfo = $credentials->fetchAuthToken();
            $accessToken = $accessTokenInfo['access_token'] ?? '';

            if (empty($accessToken)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Gagal mengambil access token dari Firebase key"
                ]);
                exit;
            }

            // 🔹 Client HTTP ke Firebase
            $client = new Client([
                'base_uri' => 'https://fcm.googleapis.com/v1/',
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]
            ]);

            // 🔹 ID Project Firebase (harus sama persis dari firebase console)
            $projectId = 'elayangdesa-5d5c3';
            $url = "projects/$projectId/messages:send";

            // 🔹 Payload notifikasi FCM
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => "Balasan Aspirasi: $judul",
                        'body' => $tanggapan ?: 'Admin telah menanggapi aspirasi Anda.'
                    ],
                    'data' => [
                        'id_aspirasi' => (string)$id,
                        'username' => $username,
                        'click_action' => 'OPEN_ASPIRASI' // nanti bisa diarahkan di Android
                    ],
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'click_action' => 'OPEN_ASPIRASI',
                        ]
                    ]
                ]
            ];

            // 🔹 Kirim ke FCM
            try {
                $response = $client->post($url, ['json' => $payload]);
                $result = json_decode($response->getBody(), true);

                // Simpan juga ke tabel notifikasiaspirasi
                $pesan = $tanggapan ?: 'Admin telah menanggapi aspirasi Anda.';
                mysqli_query($konek, "INSERT INTO notifikasiaspirasi (username, judul, pesan, tanggal) VALUES ('$username', '$judul', '$pesan', NOW())");

                echo json_encode([
                    "success" => true,
                    "message" => "Notifikasi aspirasi berhasil dikirim",
                    "fcm_response" => $result
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    "success" => false,
                    "message" => "Gagal mengirim notifikasi ke FCM",
                    "error" => $e->getMessage()
                ]);
            }

        } else {
            echo json_encode([
                "success" => false,
                "message" => "User $username tidak ditemukan di tabel akun_user"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Data aspirasi tidak ditemukan untuk ID $id"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gunakan metode POST"
    ]);
}
?>
