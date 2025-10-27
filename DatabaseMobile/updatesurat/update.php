<?php
function updatelaporan($no_pengajuan, $kode_surat){

    include '../Koneksi.php';
    $sql = "Select * from pengajuan_surat 
    where no_pengajuan ='$no_pengajuan'  and kode_surat = '$kode_surat' ";
    $result = $konek->query($sql);

    $response = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $id = $row['id'];
}
     
}
?>