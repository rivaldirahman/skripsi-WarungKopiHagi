<?php
include('koneksi.php'); // sesuaikan path koneksi.php jika berbeda

if (!isset($_GET['no_pesan'])) {
    echo "No Pesan tidak ditemukan.";
    exit;
}

$no_pesan = $_GET['no_pesan'];

// Ambil data dari database
$query = "SELECT * FROM kontak WHERE no_pesan = '$no_pesan'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Data pesan tidak ditemukan.";
    exit;
}

$data = mysqli_fetch_assoc($result);

// Update jadi sudah dibalas
$update = "UPDATE kontak SET sudah_dibalas = 1 WHERE no_pesan = '$no_pesan'";
mysqli_query($koneksi, $update);

// Buat URL redirect ke Gmail
$to    = urlencode($data['email']);
$subj  = urlencode("Balasan Pesan Kontak Dari Warkop Hagi");
$body  = urlencode('"' . $data['pesan'] . "\"\n\nTerima Kasih Atas Pesan Anda,  untuk menjawab pertanyaan Anda, berikut jawaban dari kami sebagai pihak Warkop Hagi : \n");

// Redirect ke Gmail
$gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=$to&su=$subj&body=$body";

header("Location: $gmailUrl");
exit;
?>