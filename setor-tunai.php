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
    $jumlah_setoran = $_POST['jumlah_setoran'];

    // Cek apakah jumlah setoran valid
    if ($jumlah_setoran > 0) {
        // Proses setoran
        $saldo += $jumlah_setoran;
        $_SESSION['user_data']['saldo'] = $saldo; // Update saldo pengguna

        // Simpan transaksi ke riwayat
        $riwayat_transaksi = getRiwayatTransaksi($_SESSION['user_data']['id']);
        tambahTransaksi($_SESSION['user_data']['id'], 'Setor Tunai', $jumlah_setoran, $saldo);

        // Tampilkan pesan sukses
        $pesan_sukses = "Setoran sejumlah Rp" . number_format($jumlah_setoran, 2, ',', '.') . " berhasil.";
    } else {
        // Tampilkan pesan error jika jumlah setoran tidak valid
        $pesan_error = "Jumlah setoran tidak valid.";
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
  <?php if (isset($pesan_sukses)): ?>
      <p class="alert alert-success"><?php echo $pesan_sukses; ?></p>
    <?php endif; ?>
    <?php if (isset($pesan_error)): ?>
      <p class="alert alert-danger"><?php echo $pesan_error; ?></p>
    <?php endif; ?>
    <h1>SETOR TUNAI</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <ul class="list-group">
        <li class="form-group">
          <label for="jumlah_setoran">Jumlah Setoran:</label>
          <input type="number" name="jumlah_setoran" id="jumlah_setoran" placeholder="Masukkan jumlah yang ingin disetor" required>
        </li>
          <button type="submit">Setor</button><br><br>
          <a style="text-decoration: none;" href="dashboard.php" >Halaman Utama</a>
      </ul>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
