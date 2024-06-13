<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Start session

// Include data.php containing user data
require_once('database/data.php');

require_once('functions.php');

// Check if user is logged in
if (!isset($_SESSION['user_data'])) {
  // Redirect to login if not logged in
  header('Location: login.php');
  exit;
}

// Get the logged-in user's name and saldo
$user_id = $_SESSION['user_data']['id']; // Asumsi 'id' adalah bagian dari data nasabah
$user_name = $_SESSION['user_data']['nama']; // Asumsi 'nama' adalah bagian dari data nasabah
$saldo = $_SESSION['user_data']['saldo']; // Asumsi 'saldo' adalah bagian dari data nasabah

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $virtual_account = $_POST['virtual_account'];
    $jumlah_transfer = $_POST['jumlah_transfer'];

    // Cek apakah VA valid dan saldo cukup untuk transfer
    $va_valid = false;
    foreach ($nasabah as $data_nasabah) {
        if ($data_nasabah['virtual_account'] === $virtual_account) {
            $va_valid = true;
            if ($saldo >= $jumlah_transfer) {
                // Proses transfer
                $saldo -= $jumlah_transfer;
                $_SESSION['user_data']['saldo'] = $saldo; // Update saldo pengguna

                // Simpan transaksi ke riwayat (gunakan fungsi yang sesuai untuk menyimpan riwayat)
                $riwayat_transaksi = getRiwayatTransaksi($_SESSION['user_data']['id']);
                tambahTransaksi($user_id, 'Transfer VA', $jumlah_transfer, $saldo);

                // Tampilkan pesan sukses
                $pesan_sukses = "Transfer sejumlah Rp" . number_format($jumlah_transfer, 2, ',', '.') . " ke VA berhasil.";
                header('Location: konfirmasi-transfer.php'); // Arahkan ke halaman konfirmasi jika diperlukan
            } else {
                // Tampilkan pesan error jika saldo tidak cukup
                $pesan_error = "Saldo tidak cukup untuk melakukan transfer.";
            }
            break;
        }
    }

    if (!$va_valid) {
        // Tampilkan pesan error jika VA tidak valid
        $pesan_error = "Virtual Account tidak valid.";
    }
}
?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem ATM - Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
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
  <div class="container">
    <h1>TRANSFER VIA VIRTUAL ACCOUNT</h1>
    <?php if (isset($pesan_sukses)): ?>
      <p class="alert alert-success"><?php echo $pesan_sukses; ?></p>
    <?php endif; ?>
    <?php if (isset($pesan_error)): ?>
      <p class="alert alert-danger"><?php echo $pesan_error; ?></p>
    <?php endif; ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <ul class="list-group">
        <li class="form-group">
          <label for="virtual_account">Virtual Account Tujuan:</label>
          <input type="number" name="virtual_account" id="virtual_account" placeholder="Masukkan VA tujuan" required>
        </li>
        <li class="form-group">
          <label for="jumlah_transfer">Jumlah Transfer:</label>
          <input type="number" name="jumlah_transfer" id="jumlah_transfer" placeholder="Masukkan jumlah transfer" required>
        </li>
          <button type="submit">Transfer</button>
      </ul>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
