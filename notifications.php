<?php
include("koneksi.php");

function getNotifications() {
    global $conn;
    $sql = "SELECT 
            pengajuan_surat.id_pengajuan_surat,
            pengajuan_surat.nama,
            pengajuan_surat.kode_surat,
            pengajuan_surat.tanggal,
            pengajuan_surat.no_pengajuan
            FROM pengajuan_surat
            JOIN laporan ON pengajuan_surat.id_laporan = laporan.id_laporan
            WHERE laporan.status = 'Masuk'
            ORDER BY pengajuan_surat.tanggal DESC
            LIMIT 5";
    
    $result = $conn->query($sql);
    $notifications = array();
    
    while($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    
    return $notifications;
}

if(isset($_POST['action']) && $_POST['action'] == 'get_notifications') {
    $notifications = getNotifications();
    echo json_encode($notifications);
    exit;
}
?>