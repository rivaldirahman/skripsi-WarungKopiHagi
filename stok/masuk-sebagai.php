<?php
session_start();
include "koneksi.php";

// cek apakah parameter kodeuser dikirim
if (!isset($_GET['kodeuser'])) {
    header("Location: index.php?page=akun-data");
    exit;
}

$kodeuser = $_GET['kodeuser'];

// Ambil data user berdasarkan kodeuser
$sql = "SELECT * FROM user WHERE kode_user = '$kodeuser'";
$result = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    // Hapus session sebelumnya
    session_unset();
    session_destroy();
    session_start();

    // Set session baru
    if ($user['hak_akses'] == "kasir") {
    $_SESSION['kode_user'] = $user['kode_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_user'] = $user['nama_user'];
    $_SESSION['hak_akses'] = $user['hak_akses'];

    // Arahkan ke dashboard
    header("Location: index.php");
    exit;
    }
} else {
    echo "Akun tidak ditemukan.";
}
?>