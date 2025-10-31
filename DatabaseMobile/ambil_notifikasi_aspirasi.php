<?php
header("Content-Type: application/json");
include "koneksi.php";

$username = $_POST['username'] ?? '';

if (empty($username)) {
    echo json_encode(["kode" => 0, "pesan" => "Username kosong"]);
    exit;
}

$query = "SELECT * FROM notifikasi WHERE username = '$username' ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            "id" => $row["id"],
            "pesan" => $row["pesan"],
            "tanggal" => $row["tanggal"],
            "status" => $row["status"]
        ];
    }

    echo json_encode(["kode" => 1, "pesan" => "Notifikasi ditemukan", "data" => $data]);
} else {
    echo json_encode(["kode" => 0, "pesan" => "Tidak ada notifikasi"]);
}
?>
