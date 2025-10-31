<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "baldes";

$konek = new mysqli($host, $username, $password, $database);

if ($konek->connect_error) {
    die("Koneksi gagal: " . $konek->connect_error);
} else {
    echo "Koneksi berhasil!";
}
?>
