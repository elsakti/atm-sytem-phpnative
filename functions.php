<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inisialisasi array riwayat transaksi jika belum ada
if (!isset($_SESSION['riwayat_transaksi'])) {
    $_SESSION['riwayat_transaksi'] = [];
}
// Fungsi untuk mendapatkan riwayat transaksi berdasarkan ID pengguna
function getRiwayatTransaksi($user_id) {
    // Filter transaksi berdasarkan id_pengguna dari sesi
    $riwayat_user = array_filter($_SESSION['riwayat_transaksi'], function ($transaksi) use ($user_id) {
        return $transaksi['id_pengguna'] === $user_id;
    });

    return array_values($riwayat_user); // Mengembalikan ulang array yang telah difilter
}

// Fungsi untuk menambahkan transaksi baru ke sesi pengguna
function tambahTransaksi($user_id, $tipe, $jumlah, $saldo_akhir) {
    // Tambahkan transaksi baru ke array sesi
    $_SESSION['riwayat_transaksi'][] = [
        'id_pengguna' => $user_id,
        'tanggal' => date('Y-m-d'),
        'tipe' => $tipe,
        'jumlah' => $jumlah,
        'saldo_akhir' => $saldo_akhir
    ];
}

?>
