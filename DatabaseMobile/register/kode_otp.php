<?php
require("../Koneksi.php");
require("../sender/phpmailer.php");

// Menerima data dari aplikasi Android
$username = isset($_POST['username']) ? $_POST['username'] : '';

$response = array();

if (empty($username)) {
    $response["kode"] = 0;
    $response["pesan"] = "Username tidak boleh kosong";
    echo json_encode($response);
    exit;
}

// Membuat kode OTP acak 6 digit
$kode_otp = mt_rand(100000, 999999);

// Periksa apakah username terdaftar
$perintah = "SELECT * FROM akun_user WHERE username = '$username'";
$eksekusi = mysqli_query($konek, $perintah);
$cek = mysqli_num_rows($eksekusi);

if ($cek == 0) {
    $response["kode"] = 0;
    $response["pesan"] = "Username tidak tercantum";
} else {
    $data = mysqli_fetch_assoc($eksekusi);
    $email = $data['email'];
    $type = "Register";

    // Kirim email OTP
    $mail = new EmailSender();
    $kirim = $mail->sendEmail($email, $type, $kode_otp);

    if ($kirim) {
        // Simpan kode OTP ke database
        $update = "UPDATE akun_user SET kode_otp = '$kode_otp' WHERE username = '$username'";
        $eksekusi = mysqli_query($konek, $update);

        if ($eksekusi) {
            $response["kode"] = 1;
            $response["pesan"] = "Kode OTP berhasil dikirim ke email $email";
        } else {
            $response["kode"] = 2;
            $response["pesan"] = "Kode OTP gagal disimpan";
        }
    } else {
        $response["kode"] = 3;
        $response["pesan"] = "Gagal mengirim email OTP";
    }
}

echo json_encode($response);
mysqli_close($konek);
?>
