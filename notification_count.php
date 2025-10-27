<?php
include("koneksi.php");

function getNotificationCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM pengajuan_surat 
            JOIN laporan ON pengajuan_surat.id = laporan.id 
            WHERE laporan.status = 'Masuk'";
    
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

if(isset($_POST['action']) && $_POST['action'] == 'get_count') {
    echo getNotificationCount();
    exit;
}
?>