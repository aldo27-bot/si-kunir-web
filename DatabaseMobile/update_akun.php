<?php
// Include file koneksi database
require("Koneksi.php");

// Cek apakah request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Hash password
    $hashedPassword = md5($password);

    // Ambil data dari request
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $nama = isset($_POST['nama']) ? $_POST['nama'] : null;

    // Periksa data yang kosong
    $errors = [];
    if (!$username) $errors[] = 'username';
    // if (!$password) $errors[] = 'password';
    if (!$email) $errors[] = 'email';
    if (!$nama) $errors[] = 'nama';

    if (count($errors) > 0) {
        // Jika ada data yang kosong, kirimkan respon error
        echo json_encode([
            'kode' => false,
            'pesan' => 'Beberapa data wajib diisi.',
            'data_kosong' => $errors
        ]);
        exit;
    }


    // Proses file gambar jika ada
    $profileImage = null; // Default null jika tidak ada file
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        // Lokasi folder upload, keluar 1 folder lalu masuk ke folder uploads
        $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR; // Lokasi folder upload

        // Menghasilkan nama file acak
        $fileExtension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION); // Mendapatkan ekstensi file
        $randomFileName = uniqid('profile_', true) . '.' . $fileExtension; // Menghasilkan nama file acak

        $targetPath = $uploadDir . $randomFileName; // Lokasi target file

        // Pastikan folder uploads ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Pindahkan file yang diunggah ke folder target
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
            $profileImage = $randomFileName; // Path relatif untuk disimpan di database
        } else {
            echo json_encode([
                'kode' => false,
                'pesan' => 'Gagal mengunggah file.'
            ]);
            exit;
        }
    }

    // Buat query untuk update data akun
    // $query = "UPDATE akun_user SET password = ?, email = ?, nama = ?, profile_image = ? WHERE username = ?";
    // $stmt = $konek->prepare($query);

    // if (!$stmt) {
    //     echo json_encode([
    //         'kode' => false,
    //         'pesan' => 'Gagal menyiapkan statement: ' . $konek->error
    //     ]);
    //     exit;
    // }
    $query = "UPDATE akun_user SET email = ?, nama = ?";
    $params = [$email, $nama]; // Parameter awal untuk query

    if ($password) {
        $hashedPassword = md5($password);
        $query .= ", password = ?";
        $params[] = $hashedPassword;
    }

    if ($profileImage) {
        $query .= ", profile_image = ?";
        $params[] = $profileImage;
    }

    $query .= " WHERE username = ?";
    $params[] = $username;

    // Siapkan statement
    $stmt = $konek->prepare($query);

    if (!$stmt) {
        echo json_encode([
            'kode' => false,
            'pesan' => 'Gagal menyiapkan statement: ' . $konek->error
        ]);
        exit;
    }

    // // Bind parameter
    // $stmt->bind_param("sssss", $hashedPassword, $email, $nama, $profileImage, $username);
    
    // Bind parameter secara dinamis
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);

    // Eksekusi statement
    if ($stmt->execute()) {
        echo json_encode([
            'kode' => true,
            'pesan' => 'Akun berhasil diperbarui'
        ]);
    } else {
        echo json_encode([
            'kode' => false,
            'pesan' => 'Gagal memperbarui akun: ' . $stmt->error
        ]);
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $konek->close();
} else {
    echo json_encode([
        'kode' => false,
        'pesan' => 'Metode request tidak valid.'
    ]);
}
?>