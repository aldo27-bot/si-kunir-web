<?php
include("koneksi.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // First get the image filename
        $sql = "SELECT gambar FROM informasi_desa WHERE id_informasi_desa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        // Delete the image file if it exists
        if ($row['gambar'] && file_exists('uploads/' . $row['gambar'])) {
            unlink('uploads/' . $row['gambar']);
        }
        
        // Delete the database record
        $sql = "DELETE FROM informasi_desa WHERE id_informasi_desa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header("Location: kabardesa.php?status=deleted");
            exit();
        } else {
            throw new Exception("Error executing query");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: kabardesa.php");
    exit();
}
?>