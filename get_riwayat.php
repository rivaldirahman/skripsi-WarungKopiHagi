<?php
session_start();
header('Content-Type: application/json');
include('stok/koneksi.php');

if (!isset($_SESSION['email'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$email = mysqli_real_escape_string($koneksi, $_SESSION['email']);

$sql = "SELECT tanggal, order_id, atasnama, nowa, nama_barang, grandtotal, status FROM transaksi WHERE email = '$email' ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);