<?php
// Endpoint untuk mengambil data pengguna berdasarkan username
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['username'])) {
    $username = $_GET['username'];

    // Query untuk mengambil data pengguna
    $query = "SELECT * FROM akun_user WHERE username = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
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
}
?>