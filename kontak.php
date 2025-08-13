<?php
include ('stok/koneksi.php');

// Set zona waktu ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

//data form
$namauser = $_POST['namauser'];
$email = $_POST['email'];
$pesan = $_POST['pesan'];

//proses simpan
$sql = "INSERT INTO kontak (namauser, email, pesan, tanggal) VALUES ('$namauser', '$email', '$pesan', NOW())";
$query = mysqli_query($koneksi, $sql);

if ($query) {
    echo "Berhasil";
} else {
    http_response_code(500);
    echo "Error: " . mysqli_error($koneksi); // tambahkan debug kalau perlu
}

?>