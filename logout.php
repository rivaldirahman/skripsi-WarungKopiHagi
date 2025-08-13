<?php
session_start();

// Hapus semua data sesi
session_unset();
session_destroy();

// Redirect kembali ke halaman utama
header("Location: index.php");
exit;
?>