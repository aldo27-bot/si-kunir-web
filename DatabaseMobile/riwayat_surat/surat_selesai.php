<?php
include("../Koneksi.php");

$username = $_POST['username'];

// --- Query aman (prepared statement) ---
$sql = "SELECT 
            pengajuan_surat.id_pengajuan_surat,
            pengajuan_surat.kode_surat,
            pengajuan_surat.nik,
            pengajuan_surat.nama,
            pengajuan_surat.no_pengajuan,
            laporan.tanggal,
            laporan.status
        FROM laporan
        INNER JOIN pengajuan_surat 
            ON pengajuan_surat.id_laporan = laporan.id_laporan
        WHERE (laporan.status = 'Selesai' OR laporan.status = 'Tolak')
        AND pengajuan_surat.username = ?
        GROUP BY pengajuan_surat.id_pengajuan_surat
        ORDER BY laporan.tanggal DESC";

$stmt = $konek->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$response = array();

if ($result->num_rows > 0) {
    $response["kode"] = 1;
    $response["pesan"] = "Data Tersedia";
    $response["data"] = array();

    while ($row = $result->fetch_object()) {
        $F["id_pengajuan_surat"] = $row->id_pengajuan_surat;
        $F["kode_surat"] = $row->kode_surat;
        $F["nama"] = $row->nama;
        $F["nik"] = $row->nik;
        $F["no_pengajuan"] = $row->no_pengajuan;
        $F["tanggal"] = $row->tanggal;
        $F["status"] = $row->status;

        array_push($response["data"], $F);
    }

} else {
    $response["kode"] = 0;
    $response["pesan"] = "Data Tidak Tersedia";
}

// Output JSON
echo json_encode($response);

$stmt->close();
$konek->close();
?>
