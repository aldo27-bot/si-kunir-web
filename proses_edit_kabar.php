<?php
include("koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    
    try {
        // First, get the current image name
        $sql = "SELECT gambar FROM informasi_desa WHERE id_informasi_desa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $current_image = $row['gambar'];
        
        // Handle new file upload
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
                    // Delete old image if exists
                    if ($current_image && file_exists('uploads/' . $current_image)) {
                        unlink('uploads/' . $current_image);
                    }
                    $current_image = $newname;
                } else {
                    die("Error uploading file.");
                }
            } else {
                die("File type not allowed. Please upload image files only (JPG, JPEG, PNG, GIF).");
            }
        }
        
        // Update database
        $sql = "UPDATE informasi_desa SET judul = ?, tanggal = ?, deskripsi = ?, gambar = ? WHERE id_informasi_desa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $judul, $tanggal, $deskripsi, $current_image, $id);
        
        if ($stmt->execute()) {
            header("Location: kabardesa.php?status=updated");
            exit();
        } else {
            throw new Exception("Error executing query");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>