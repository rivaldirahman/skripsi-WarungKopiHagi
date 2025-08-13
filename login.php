<?php
session_start();
include('stok/koneksi.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM user_pelanggan WHERE email = '$email' AND password = '$password'");
    $data = mysqli_fetch_assoc($query);      

    if ($data) {
        $_SESSION['login'] = true;
        $_SESSION['nama_pengguna'] = $data['nama_lengkap'];
        $_SESSION['email'] = $data['email'];
        echo "<script>alert('Login berhasil!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Email atau password salah'); window.location.href='index.php';</script>";
    }
}
?>