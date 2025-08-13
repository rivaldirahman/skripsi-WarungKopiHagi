<?php
session_start();
include('stok/koneksi.php');

if (isset($_POST['kirim'])) {
    $nama = $_POST['registerName'];
    $email = $_POST['registerEmail'];
    $password = $_POST['registerPassword'];

    // Cek apakah email sudah digunakan
    $cek_email = mysqli_query($koneksi, "SELECT * FROM user_pelanggan WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        // Simpan data input ke session (kecuali email)
        $_SESSION['register_error'] = "Email sudah digunakan!";
        $_SESSION['register_data'] = [
            'nama' => $nama,
            'password' => $password
        ];
        header("Location: index.php");
        exit;
    }

    // Simpan ke database
    $simpan = mysqli_query($koneksi, "INSERT INTO user_pelanggan (kode_user, nama_lengkap, email, password) VALUES ('', '$nama', '$email', '$password')");

    if ($simpan) {
        $_SESSION['register_success'] = true;
    } else {
        $_SESSION['register_error'] = "Gagal menyimpan data";
    }

    header("Location: index.php");
    exit;
}
?>