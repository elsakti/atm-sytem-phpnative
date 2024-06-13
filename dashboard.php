<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Start session

// Check if user is logged in
if (!isset($_SESSION['user_data'])) {
  // Redirect to login if not logged in
  header('Location: login.php');
  exit;
}

// Get the logged-in user's name
$user_name = $_SESSION['user_data']['nama']; // Asumsi 'nama' adalah bagian dari data nasabah

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem ATM - Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 80%;
      margin: auto;
      overflow: hidden;
    }
    h1 {
      color: #333;
      text-align: center;
      margin-top: 50px;
    }
    p {
      text-align: center;
      font-size: 18px;
    }
    .list-group {
      list-style: none;
      padding: 0;
      margin-top: 30px;
    }
    .list-group-item {
      background-color: #fff;
      border: 1px solid #ddd;
      padding: 15px;
      margin-bottom: -1px;
      text-align: center;
    }
    .list-group-item a {
      text-decoration: none;
      color: #333;
      display: block;
      transition: background-color 0.3s;
    }
    .list-group-item:hover a {
      background-color: #337ab7;
      color: #fff;
    }
    </style>
<body>
  <div class="container">
    <h1>Selamat Datang, <?php echo htmlspecialchars($user_name); ?>!</h1>

    <p>Silakan pilih transaksi yang ingin Anda lakukan:</p>

    <ul class="list-group">
      <li class="list-group-item"><a href="cek-saldo.php">Cek Saldo</a></li>
      <li class="list-group-item"><a href="tarik-tunai.php">Tarik Tunai</a></li>
      <li class="list-group-item"><a href="setor-tunai.php">Setor Tunai</a></li>
      <li class="list-group-item"><a href="metode-transfer.php">Transfer Uang</a></li>
      <li class="list-group-item"><a href="riwayat-transaksi.php">Riwayat Transaksi</a></li>
      <li class="list-group-item"><a href="logout.php">Logout</a></li>
    </ul>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>