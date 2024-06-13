<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek Apakah User Sudah Login
if (isset($_SESSION['username'])) {
  // Ketika sudah login, maka akan dipindahkan ke dashboard.php
  header('Location: dashboard.php');
} else {
  // Jika tidak, maka akan dipindahkan ke login.php
  header('Location: login.php');
}

exit; //Exit Ketika Selesai Redirect
?>
