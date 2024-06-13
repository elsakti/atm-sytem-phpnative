<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
if (!isset($_SESSION['user_data'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['metode_transaksi'])) {
        $metode_transaksi = $_POST['metode_transaksi'];

        // Redirect based on selected transaction method
        if ($metode_transaksi === 'transfer_bank') {
            header('Location: transfer_bank.php');
            exit;
        } elseif ($metode_transaksi === 'virtual_account') {
            header('Location: virtual_account.php');
            exit;
        }
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
</style>
<body>
<div class="container">
    <h1>Silahkan Memilih Metode Transaksi</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <ul class="list-group">
        <li class="list-group-item">
          <a href="transfer-bank.php" >Transfer Bank</a>
        </li>
        <li class="list-group-item">
          <a href="virtual-account.php">Virtual Account</a>
        </li>
      </ul>
    </form>
  </div>
  <script src="assets/js/script.js"></script>
</body>
</html>
