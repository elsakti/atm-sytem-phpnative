<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Start session

// Include data.php containing user data
require_once('database/data.php');
require_once('functions.php'); // Asumsikan kamu telah membuat file ini untuk menyimpan fungsi getRiwayatTransaksi

// Check if user is logged in
if (!isset($_SESSION['user_data'])) {
  // Redirect to login if not logged in
  header('Location: login.php');
  exit;
}

// Get the logged-in user's transaction history
$riwayat_transaksi = getRiwayatTransaksi($_SESSION['user_data']['id']); // Asumsikan fungsi ini mengambil riwayat transaksi berdasarkan id pengguna
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
    .form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
  }
  .form-group label {
    margin-bottom: 5px;
  }
  .form-group input[type="number"],
  .form-group input[type="radio"] {
    padding: 10px;
    margin-right: 10px;
  }
  .form-group input[type="number"] {
    width: 100%;
    box-sizing: border-box;
  }
  .form-group .radio-group {
    display: flex;
    justify-content: space-between;
  }
  button {
    padding: 10px 20px;
    background-color: #337ab7;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  button:hover {
    background-color: #286090;
  }
</style>
<body>
  <div class="container">
    <h1>Riwayat Transaksi</h1>
    <ul class="list-group">
      <?php foreach ($riwayat_transaksi as $transaksi): ?>
        <li class="list-group-item">
          Tanggal: <?php echo $transaksi['tanggal']; ?><br>
          Tipe: <?php echo $transaksi['tipe']; ?><br>
          Jumlah: Rp<?php echo number_format($transaksi['jumlah'], 2, ',', '.'); ?><br>
          Saldo Akhir: Rp<?php echo number_format($transaksi['saldo_akhir'], 2, ',', '.'); ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <a style="text-decoration: none" href="dashboard.php">Halaman Utama</a>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
