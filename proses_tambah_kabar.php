<?php
include("koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    
    // Handle file upload
    $gambar = "";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Verify file extension
        if (in_array(strtolower($filetype), $allowed)) {
            // Generate unique filename
            $newname = uniqid() . '.' . $filetype;
            $upload_path = 'uploads/' . $newname;
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $gambar = $newname;
            } else {
                die("Error uploading file.");
            }
        } else {
            die("File type not allowed. Please upload image files only (JPG, JPEG, PNG, GIF).");
        }
    }
    
    try {
        $sql = "INSERT INTO informasi_desa (judul, tanggal, deskripsi, gambar) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $judul, $tanggal, $deskripsi, $gambar);
        
        if ($stmt->execute()) {
            header("Location: kabardesa.php?status=success");
            exit();
        } else {
            throw new Exception("Error executing query");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

?>