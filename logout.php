<?php 

// Memulai Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

// Menghapus Session\
session_destroy();

// Dipindahkan ke index.php
header("location:login.php"); 

exit;
?>