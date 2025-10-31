<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include("koneksi.php"); // Pastikan koneksi pakai $conn

if (!isset($_SESSION['flash'])) $_SESSION['flash'] = null;

// Proses saat form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $status = $_POST['status'] ?? '';
    $tanggapan = $_POST['tanggapan'] ?? '';

    if ($id <= 0 || trim($tanggapan) === '') {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Data tidak lengkap (ID atau tanggapan kosong).'];
        header('Location: respond.php?id=' . $id);
        exit;
    }

    try {
        // 🔹 Update status & tanggapan aspirasi
        $stmt = $conn->prepare("UPDATE aspirasi SET status=?, tanggapan=? WHERE id=?");
        $stmt->bind_param("ssi", $status, $tanggapan, $id);
        $ok = $stmt->execute();
        $stmt->close();

        if (!$ok) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan tanggapan: '.$conn->error];
            header('Location: respond.php?id=' . $id);
            exit;
        }

        // 🔹 Ambil username dari tabel aspirasi
        $getUser = $conn->prepare("SELECT username FROM aspirasi WHERE id=?");
        $getUser->bind_param("i", $id);
        $getUser->execute();
        $resUser = $getUser->get_result();
        $userData = $resUser->fetch_assoc();
        $getUser->close();

        // 🔹 Tentukan penerima notifikasi
        if ($userData && !empty($userData['username'])) {
            $username = $userData['username']; // user pengaju aspirasi
        } else {
            $username = $_SESSION['username']; // fallback: admin yang login
        }

        // 🔹 Simpan notifikasi
        $pesan = "Aspirasi telah ditanggapi. Status: $status. Tanggapan: $tanggapan";
        $notif = $conn->prepare("INSERT INTO notifikasi (username, pesan) VALUES (?, ?)");
        $notif->bind_param("ss", $username, $pesan);
        $notif->execute();
        $notif->close();

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tanggapan berhasil disimpan dan notifikasi dikirim.'];
        header('Location: list_aspirasi.php');
        exit;

    } catch (Exception $e) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Error: ' . $e->getMessage()];
        header('Location: respond.php?id=' . $id);
        exit;
    }
}

// 🔹 GET: ambil data aspirasi
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "ID aspirasi tidak diberikan.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM aspirasi WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$r = $result->fetch_assoc();
$stmt->close();

if (!$r) {
    echo "Data aspirasi tidak ditemukan.";
    exit;
}

$flash = $_SESSION['flash'];
$_SESSION['flash'] = null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tanggapi Aspirasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4 bg-light">
    <div class="container">
        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <h1 class="mb-4">Tanggapi Aspirasi</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h4><?= htmlspecialchars($r['judul']) ?></h4>
                <small class="text-muted"><?= htmlspecialchars($r['tanggal'] ?? '-') ?></small>
                <p class="mt-3"><?= nl2br(htmlspecialchars($r['deskripsi'])) ?></p>

                <?php if (!empty($r['foto'])): ?>
                    <img src="uploads/<?= htmlspecialchars($r['foto']) ?>" style="max-width:300px; border-radius:8px;">
                <?php endif; ?>

                <?php if (!empty($r['tanggapan'])): ?>
                    <div class="mt-3 p-3 bg-light border rounded">
                        <strong>Tanggapan sebelumnya:</strong><br>
                        <?= nl2br(htmlspecialchars($r['tanggapan'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($r['id']) ?>">
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option <?= $r['status']=='Diajukan' ? 'selected':'' ?>>Diajukan</option>
                    <option <?= $r['status']=='Diproses' ? 'selected':'' ?>>Diproses</option>
                    <option <?= $r['status']=='Selesai' ? 'selected':'' ?>>Selesai</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggapan</label>
                <textarea name="tanggapan" class="form-control" rows="5" required><?= htmlspecialchars($r['tanggapan']) ?></textarea>
            </div>

            <button class="btn btn-success"><i class="fas fa-paper-plane"></i> Kirim</button>
            <a href="list_aspirasi.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
