<?php
session_start();

// Jika sesi login sudah ada, arahkan ke dashboard
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Login - Desa Kuncir</title>
  <link href="css/styles.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #17a2b8, #0d6efd);
      color: #333;
      font-family: Arial, sans-serif;
    }
    .card {
      width: 600px; /* Membuat card lebih lebar */
      padding: 40px; /* Memberikan lebih banyak padding di dalam card */
      border-radius: 15px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      margin-top: 50px;
    }
    .card-header {
      background-color: #ffffff;
      text-align: center;
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }
    .btn-primary {
      background-color: #0d6efd;
      border: none;
      font-weight: bold;
    }
    .btn-primary:hover {
      background-color: #0b5ed7;
    }
    .small {
      color: #0d6efd;
    }
    .small:hover {
      color: #0b5ed7;
      text-decoration: underline;
    }
  </style>
    <link rel="icon" href="assets/img/logonganjuk.png" type="image/png" /> <!-- Tambahkan baris ini untuk ikon -->
</head>

<body>
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container d-flex justify-content-center">
          <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header">
              <img src="assets/img/logonganjuk.png" class="mx-auto d-block" alt="Logo" style="width: 25%;">
              <h3 class="text-center font-weight-light mt-3">Login</h3>
            </div>
            <div class="card-body">
            <form action="login_proses.php" method="POST" name="login">
    <div class="form-floating mb-3">
        <input class="form-control" id="username" name="username" type="text" placeholder="Username" required />
        <label for="username"><i class="fas fa-user me-2"></i>Username</label>
    </div>
    <div class="form-floating mb-3">
        <input class="form-control" id="password" name="password" type="password" placeholder="Password" required />
        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
        <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;">
            <i class="fa fa-eye"></i>
        </button>
    </div>
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?> 
        <div class="alert alert-danger mt-3" role="alert">
            Login gagal. Silakan periksa kembali username dan password Anda.
        </div>
    <?php endif; ?>
    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
        <a class="small" href="verKodeOTP/proses_kirimkodeotp.php">Lupa Password?</a>
        <input class="btn btn-primary px-4 py-2" type="submit" value="Login">
    </div>
</form>

            </div>
          </div>
        </div>
      </main>
  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
      // Ubah tipe input password
      const type = passwordInput.type === "password" ? "text" : "password";
      passwordInput.type = type;

      // Ubah ikon
      this.querySelector("i").classList.toggle("fa-eye");
      this.querySelector("i").classList.toggle("fa-eye-slash");
    });
  });
</script>

</body>
</html>
