<?php
session_start();
include('stok/koneksi.php');

if (isset($_POST['kirim'])) {
    $email = $_POST['email'];
    $new = $_POST['newPassword'];
    $konfirm = $_POST['confirmPassword'];

    // Simpan email ke session untuk ditampilkan ulang di form
    $_SESSION['reset_data']['email'] = $email;

    // Validasi: email harus terdaftar
    $cek = mysqli_query($koneksi, "SELECT * FROM user_pelanggan WHERE email = '$email'");
    if (mysqli_num_rows($cek) === 0) {
        $_SESSION['reset_error'] = "Email tidak ditemukan!";
        header("Location: index.php?reset=password");
        exit;
    }

    // Validasi: password harus sama
    if ($new !== $konfirm) {
        $_SESSION['reset_error'] = "Password dan konfirmasi tidak sama!";
        header("Location: index.php?reset=password");
        exit;
    }
    
    $user = mysqli_fetch_assoc($cek); // ambil data user
    if ($new === $user['password']) {
        $_SESSION['reset_error'] = "Password baru sama dengan password lama!";
        header("Location: index.php?reset=password");
        exit;
    }

    // Simpan password baru
    // $hash = password_hash($new, PASSWORD_DEFAULT);
    $update = mysqli_query($koneksi, "UPDATE user_pelanggan SET password = '$new' WHERE email = '$email'");

    if ($update) {
        $_SESSION['reset_success'] = true;
    } else {
        $_SESSION['reset_error'] = "Gagal mengubah password.";
    }

    header("Location: index.php?reset=password");
    exit;
}
?>