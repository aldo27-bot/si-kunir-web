<?php
include("koneksi.php");
// digunakan grafik dashboard
// Tentukan tanggal 7 hari yang lalu
$endDate = new DateTime(); // Tanggal hari ini
$startDate = (new DateTime())->modify('-6 days'); // 7 hari terakhir termasuk hari ini

// Buat array untuk menampung hasil akhir
$data = [];

// Ambil data dari database
$query = "SELECT DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal, COUNT(*) as jumlah 
          FROM pengajuan_surat 
          WHERE tanggal BETWEEN '{$startDate->format('Y-m-d')}' AND '{$endDate->format('Y-m-d')}' 
          GROUP BY tanggal";
$result = $conn->query($query);

// Buat array sementara untuk data yang ada di database
$tempData = [];
while ($row = $result->fetch_assoc()) {
    $tempData[$row['tanggal']] = (int)$row['jumlah'];
}

// Isi data dengan rentang tanggal dari 7 hari terakhir
$period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate->modify('+1 day'));
foreach ($period as $date) {
    $formattedDate = $date->format('Y-m-d');
    // Jika ada data untuk tanggal ini, gunakan; jika tidak, setel jumlahnya ke 0
    $data[] = [
        'tanggal' => $formattedDate,
        'jumlah' => $tempData[$formattedDate] ?? 0
    ];
}

// Kembalikan data dalam format JSON
echo json_encode($data);
?>
