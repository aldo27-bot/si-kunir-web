<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "baldes";

$konek = new mysqli($host, $username, $password, $database);

// cek koneksi
if ($konek->connect_error) {
    die("Koneksi gagal: " . $konek->connect_error);
}
?>
