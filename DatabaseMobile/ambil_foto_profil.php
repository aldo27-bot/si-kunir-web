<?php
// Pastikan koneksi database sudah di-include
include "../Koneksi.php"; // sesuaikan path file koneksi

header('Content-Type: application/json'); // agar server selalu mengirim JSON

// Cek apakah method GET dan ada parameter username
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['username'])) {
    $username = $_GET['username'];

    // Cek apakah koneksi $konek berhasil
    if (!$konek) {
        echo json_encode([
            'kode' => false,
            'pesan' => 'Koneksi database gagal'
        ]);
        exit;
    }

    // Query untuk mengambil data pengguna
    $query = "SELECT * FROM akun_user WHERE username = ?";
    $stmt = $konek->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Kirimkan data pengguna termasuk nama file gambar profil
            echo json_encode([
                'kode' => true,
                'username' => $user['username'],
                'email' => $user['email'],
                'nama' => $user['nama'],
                'profile_image' => $user['profile_image'] // Nama file gambar profil
            ]);
        } else {
            echo json_encode([
                'kode' => false,
                'pesan' => 'Pengguna tidak ditemukan.'
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'kode' => false,
            'pesan' => 'Query prepare gagal: ' . $konek->error
        ]);
    }

    $konek->close();
} else {
    echo json_encode([
        'kode' => false,
        'pesan' => 'Request tidak valid'
    ]);
}
?>
