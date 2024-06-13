<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include data.php containing user data
require_once('database/data.php');

if (isset($_SESSION['user_data'])) {
  header('Location: dashboard.php');
  exit;
}

$error = ''; // Inisialisasi variabel error

// Handle login form submission (if any)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Cari nasabah berdasarkan username
  $user_found = false; // Tandai jika user ditemukan
  foreach ($nasabah as $user) {
    if ($user['username'] === $username) {
      $user_found = true; // User ditemukan
      // Validasi password (harusnya menggunakan password_hash dan password_verify untuk sistem nyata)
      if ($user['password'] === $password) {
        // Login successful, store user data in session
        $_SESSION['user_data'] = $user;
        header('Location: dashboard.php');
        exit;
      } else {
        // Login failed, display error message
        $error = 'Password salah!';
        break;
      }
    }
  }

  // Jika username tidak ditemukan
  if (!$user_found) {
    $error = 'Username tidak ditemukan!';
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem ATM - Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <h1>Sistem ATM - Login</h1>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
