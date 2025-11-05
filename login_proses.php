<?php
include("koneksi.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($user === '' || $password === '') {
        header("Location: login.php?error=1");
        exit;
    }

    // Ambil user dari DB
    $query = "SELECT username, password FROM akun_admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $dbpass = $row['password'];

        $login_ok = false;

        // 1) Password MD5
        if (preg_match('/^[a-f0-9]{32}$/i', $dbpass)) {
            if (md5($password) === strtolower($dbpass)) {
                $login_ok = true;
            }
        }
        // 2) Password bcrypt / password_hash
        elseif (strpos($dbpass, '$2y$') === 0 || strpos($dbpass, '$2a$') === 0) {
            if (password_verify($password, $dbpass)) {
                $login_ok = true;
            }
        }
        // 3) Password plaintext (reset password)
        else {
            if ($password === $dbpass) {
                $login_ok = true;
            }
        }

        if ($login_ok) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            // $_SESSION['nama'] = $row['nama'];

            // Optional: Upgrade MD5 ke password_hash() agar aman
            if (preg_match('/^[a-f0-9]{32}$/i', $dbpass)) {
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $upd = mysqli_prepare($conn, "UPDATE akun_admin SET password = ? WHERE username = ?");
                mysqli_stmt_bind_param($upd, "ss", $new_hash, $user);
                mysqli_stmt_execute($upd);
                mysqli_stmt_close($upd);
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: dashboard.php");
            exit;
        } else {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: login.php?error=1");
            exit;
        }

    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: login.php?error=1");
        exit;
    }
}
?>
