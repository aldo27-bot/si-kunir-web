<?php
require('koneksi.php');

// Username yang diinginkan
$username = $_POST['username'];

// SQL query
$sql = "SELECT pengajuan_surat.id, 
               pengajuan_surat.no_pengajuan, 
               pengajuan_surat.kode_surat, 
               Date(laporan.tanggal) as tanggal,
               Time(laporan.tanggal) as jam, 
               laporan.status, 
               laporan.alasan 
        FROM pengajuan_surat
        JOIN laporan ON laporan.id = pengajuan_surat.id
        WHERE pengajuan_surat.username = '$username' AND laporan.status ='Tolak' or laporan.status ='Selesai'
        GROUP BY pengajuan_surat.id
        ORDER BY laporan.tanggal DESC";

$result = $konek->query($sql);

$response = array();

if ($result->num_rows > 0) {
    // Ganti $eksekusi dengan $result
    // $row = mysqli_fetch_object($eksekusi);
    $row = $result->fetch_object();

    $response['kode'] = 1;
    $response['nopengajuan'] = $row->no_pengajuan;
    $response['status'] = $row->status;
    $response['pesan'] = "Data Tersedia";
    $response['alasan'] = $row->alasan;
    $response['tanggal'] = $row->tanggal;
    $response['jam'] = $row->jam;
    // $response["data"] = array();
    // while ($row = $result->fetch_object()) {
    //     $data['id'] = $row->id;
    //     $data["nopengajuan"] = $row->no_pengajuan;
    //     $data["kode"] = $row->kode_surat;
    //     $data['tanggal'] = $row->tanggal;
    //     $data['status'] = $row->status;
    //     $data['alasan'] = $row->alasan;
    //     array_push($response["data"], $data);
    // }
} else {
    $response['kode'] = 0;
    $response['pesan'] = "Data Tidak Tersedia";
}

// Mengirim data sebagai JSON
header('Content-Type: application/json');
echo json_encode($response);
$konek->close();
?>
