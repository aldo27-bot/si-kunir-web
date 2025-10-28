<?php
header('Content-Type: application/json');
include("../koneksi.php");

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan parameter username dikirim
    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        // Query untuk mengambil tanggapan terbaru berdasarkan username
        $query = "
            SELECT tanggapan, tanggal
            FROM aspirasi
            WHERE username = '$username'
            ORDER BY tanggal DESC
            LIMIT 1
        ";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Jika tanggapan ada
            if (!empty($row['tanggapan'])) {
                $response['kode'] = 1;
                $response['tanggapan'] = $row['tanggapan'];
                $response['tanggal'] = $row['tanggal'];
                $response['pesan'] = "Aspirasi sudah ditanggapi.";
            } else {
                $response['kode'] = 0;
                $response['pesan'] = "Belum ada tanggapan untuk aspirasi Anda.";
            }
        } else {
            $response['kode'] = 0;
            $response['pesan'] = "Data tidak ditemukan untuk username ini.";
        }
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "Parameter 'username' tidak dikirim.";
    }
} else {
    $response['kode'] = 0;
    $response['pesan'] = "Metode permintaan tidak valid.";
}

echo json_encode($response);
?>
