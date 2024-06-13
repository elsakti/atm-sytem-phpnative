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

// Get the logged-in user's name and saldo
$user_name = $_SESSION['user_data']['nama']; // Asumsi 'nama' adalah bagian dari data nasabah
$saldo = $_SESSION['user_data']['saldo']; // Asumsi 'saldo' adalah bagian dari data nasabah

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nomor_rekening = $_POST['nomor_rekening'];
    $kode_bank = $_POST['kode_bank'];
    $jumlah_transfer = $_POST['jumlah_transfer'];

    // Tentukan biaya admin berdasarkan bank tujuan
    $biaya_admin = $kode_bank === $_SESSION['user_data']['kode_bank'] ? 2000 : 6500;

    // Cek apakah saldo cukup untuk transfer dan biaya admin
    if ($saldo >= $jumlah_transfer + $biaya_admin) {
        // Proses transfer
        $saldo -= ($jumlah_transfer + $biaya_admin);
        $_SESSION['user_data']['saldo'] = $saldo; // Update saldo pengguna

        // Simpan transaksi ke riwayat (gunakan fungsi yang sesuai untuk menyimpan riwayat)
        // tambahTransaksi($user_id, 'Transfer', $jumlah_transfer, $saldo);

        // Tampilkan pesan sukses atau arahkan ke halaman konfirmasi
        $pesan_sukses = "Transfer sejumlah Rp" . number_format($jumlah_transfer, 2, ',', '.') . " berhasil.";
        // header('Location: konfirmasi_transfer.php'); // Arahkan ke halaman konfirmasi jika diperlukan
    } else {
        // Tampilkan pesan error jika saldo tidak cukup
        $pesan_error = "Saldo tidak cukup untuk melakukan transfer.";
    }

    $saldo = $_SESSION['user_data']['saldo'];
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
</style>
<body>
  <div class="container">
    <h1>TRANSFER BERHASIL</h1>
      <ul class="list-group">
        <P>Sisa Saldo Anda: Rp. <?php echo number_format($saldo, 2, ',', '.'); ?></P>
        <li class="list-group-item"><a href="metode-transfer.php">TRANSFER LAGI</a></li>
        <li class="list-group-item"><a href="dashboard.php">Halaman Utama</a></li>
      </ul>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
