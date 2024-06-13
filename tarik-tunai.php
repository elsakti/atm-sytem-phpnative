<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Start session

// Include data.php containing user data
require_once('database/data.php');
require_once('functions.php'); // Asumsikan kamu telah membuat file ini untuk menyimpan fungsi tambahTransaksi

// Check if user is logged in
if (!isset($_SESSION['user_data'])) {
  // Redirect to login if not logged in
  header('Location: login.php');
  exit;
}

// Get the logged-in user's name and saldo
$user_name = $_SESSION['user_data']['nama']; // Asumsi 'nama' adalah bagian dari data nasabah
$saldo = $_SESSION['user_data']['saldo']; // Asumsi 'saldo' adalah bagian dari data nasabah

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $jumlah_penarikan = $_POST['jumlah_penarikan'];

    // Cek apakah jumlah penarikan valid dan saldo cukup
    if ($jumlah_penarikan > 0 && $saldo >= $jumlah_penarikan) {
        // Proses penarikan
        $saldo -= $jumlah_penarikan;
        $_SESSION['user_data']['saldo'] = $saldo; // Update saldo pengguna

        // Simpan transaksi ke riwayat
        $riwayat_transaksi = getRiwayatTransaksi($_SESSION['user_data']['id']);
        tambahTransaksi($_SESSION['user_data']['id'], 'Tarik Tunai', $jumlah_penarikan, $saldo);

        // Tampilkan pesan sukses
        $pesan_sukses = "Penarikan sejumlah Rp" . number_format($jumlah_penarikan, 2, ',', '.') . " berhasil.";
    } else {
        // Tampilkan pesan error jika jumlah penarikan tidak valid atau saldo tidak cukup
        $pesan_error = "Jumlah penarikan tidak valid atau saldo tidak cukup.";
    }
}
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
    <h1>TARIK TUNAI</h1>
    <?php if (isset($pesan_sukses)): ?>
      <p class="alert alert-success"><?php echo $pesan_sukses; ?></p>
    <?php endif; ?>
    <?php if (isset($pesan_error)): ?>
      <p class="alert alert-danger"><?php echo $pesan_error; ?></p>
    <?php endif; ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <ul class="list-group">
        <li class="form-group">
          <label for="jumlah_penarikan">Jumlah Penarikan:</label>
          <input type="number" name="jumlah_penarikan" id="jumlah_penarikan" placeholder="Masukkan jumlah yang ingin ditarik" required>
        </li>
          <button type="submit">Tarik</button><br><br>
          <a style="text-decoration: none;" href="dashboard.php" >Halaman Utama</a>
      </ul>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
