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
    $nomor_rekening = $_POST['nomor_rekening'];
    $kode_bank = $_POST['kode_bank'];
    $jumlah_transfer = $_POST['jumlah_transfer'];

    // Validasi nomor rekening dan kode bank
    $rekening_valid = false;
    $kode_bank_valid = false;
    foreach ($nasabah as $data_nasabah) {
        if ($data_nasabah['nomor_rekening'] === $nomor_rekening) {
            $rekening_valid = true;
            if ($data_nasabah['kode_bank'] === $kode_bank) {
                $kode_bank_valid = true;
            }
            break;
        }
    }

    // Jika nomor rekening dan kode bank valid, proses transfer
    if ($rekening_valid && $kode_bank_valid) {
        // Tentukan biaya admin berdasarkan bank tujuan
        $biaya_admin = $kode_bank === $_SESSION['user_data']['kode_bank'] ? 2000 : 6500;

        // Cek apakah saldo cukup untuk transfer dan biaya admin
        if ($saldo >= $jumlah_transfer + $biaya_admin) {
            // Proses transfer
            $saldo -= ($jumlah_transfer + $biaya_admin);
            $_SESSION['user_data']['saldo'] = $saldo; // Update saldo pengguna

            $riwayat_transaksi = getRiwayatTransaksi($_SESSION['user_data']['id']);
            // Simpan transaksi ke riwayat (gunakan fungsi yang sesuai untuk menyimpan riwayat)
            tambahTransaksi($user_id, 'Transfer', $jumlah_transfer, $saldo);

            // Tampilkan pesan sukses atau arahkan ke halaman konfirmasi
            $pesan_sukses = "Transfer sejumlah Rp" . number_format($jumlah_transfer, 2, ',', '.') . " berhasil.";
            header('Location: konfirmasi-transfer.php'); // Arahkan ke halaman konfirmasi jika diperlukan
            } else {
                // Tampilkan pesan error jika saldo tidak cukup
                $pesan_error = "Saldo tidak cukup untuk melakukan transfer.";
                }
                } else {
                    // Tampilkan pesan error jika nomor rekening atau kode bank tidak valid
                    $pesan_error = "Nomor rekening atau kode bank tidak valid.";
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
    <h1>TRANSFER BANK</h1>
    <?php if (isset($pesan_sukses)): ?>
      <p class="alert alert-success"><?php echo $pesan_sukses; ?></p>
    <?php endif; ?>
    <?php if (isset($pesan_error)): ?>
      <p class="alert alert-danger"><?php echo $pesan_error; ?></p>
    <?php endif; ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="form-group">
        <label for="nomor_rekening">Nomor Rekening Tujuan:</label>
        <input type="number" name="nomor_rekening" id="nomor_rekening" placeholder="Masukkan nomor rekening" required>
      </div>
      <div class="form-group">
          <label for="jumlah_transfer">Jumlah Transfer:</label>
          <input type="number" name="jumlah_transfer" id="jumlah_transfer" placeholder="Masukkan jumlah transfer" required>
    </div>
        <div class="form-group">
        <label>Kode Bank Tujuan:</label>
        <div class="radio-group">
            <label><input type="radio" name="kode_bank" value="BNI"> BNI</label>
            <label><input type="radio" name="kode_bank" value="MANDIRI"> MANDIRI</label>
            <label><input type="radio" name="kode_bank" value="BCA"> BCA</label>
            <label><input type="radio" name="kode_bank" value="BRI"> BRI</label>
        </div>
        </div>
      <button type="submit">Transfer</button>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
