<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'koneksi.php';

// Proses POST untuk update status & tanggapan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $tanggapan = $_POST['tanggapan'];

    $stmt = $conn->prepare("UPDATE aspirasi SET status=?, tanggapan=? WHERE id=?");
    $stmt->bind_param("ssi", $status, $tanggapan, $id);
    $stmt->execute();
    $stmt->close();

    header('Location: list_aspirasi.php');
    exit;
}

// Ambil data aspirasi berdasarkan ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM aspirasi WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$r = $result->fetch_assoc();
$stmt->close();

if (!$r) { 
    echo "Data aspirasi tidak ditemukan"; 
    exit; 
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanggapi Aspirasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
  <h4>Tanggapi Aspirasi #<?= htmlspecialchars($r['id']) ?></h4>
  <p><strong>Judul:</strong> <?= htmlspecialchars($r['judul']) ?></p>
  <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($r['deskripsi'])) ?></p>
  <?php if ($r['foto']): ?>
    <p><img src="../uploads/<?= htmlspecialchars($r['foto']) ?>" style="max-width:300px"></p>
  <?php endif; ?>
  <form method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($r['id']) ?>">
    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-control">
        <option <?= $r['status']=='Diajukan' ? 'selected':''?>>Diajukan</option>
        <option <?= $r['status']=='Diproses' ? 'selected':''?>>Diproses</option>
        <option <?= $r['status']=='Selesai' ? 'selected':''?>>Selesai</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Tanggapan</label>
      <textarea name="tanggapan" class="form-control"><?= htmlspecialchars($r['tanggapan']) ?></textarea>
    </div>
    <button class="btn btn-success">Kirim</button>
    <a href="list_aspirasi.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>
</body>
</html>
