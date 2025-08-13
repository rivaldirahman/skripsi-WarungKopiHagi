<?php
include('../stok/koneksi.php');
header('Content-Type: application/json');

$order_id = $_GET['order_id'] ?? '';
if (!$order_id) exit(json_encode(['status'=>'INVALID']));

// Ambil 1 baris dari transaksi (karena memang 1 baris per transaksi)
$query = mysqli_query($koneksi,
  "SELECT nama_barang, atasnama, nowa, grandtotal, tanggal FROM transaksi WHERE order_id='$order_id' LIMIT 1"
);

if (!$query || mysqli_num_rows($query) === 0)
  exit(json_encode(['status'=>'NOT FOUND']));

$detail = mysqli_fetch_assoc($query);

// 🔍 Parse field nama_barang jadi item array
$items = [];
$pattern = '/(.*?) \(Rp\. (\d+)\) x (\d+) = Rp\. (\d+)/';
preg_match_all($pattern, $detail['nama_barang'], $matches, PREG_SET_ORDER);
foreach ($matches as $m) {
  $items[] = [
    'name'     => trim($m[1]),
    'price'    => (int)$m[2],
    'quantity' => (int)$m[3],
  ];
}

// Kirim response lengkap
echo json_encode([
  'status'     => 'PAID',
  'order_id'   => $order_id,
  'atasnama'   => $detail['atasnama'],
  'tanggal'   => $detail['tanggal'],
  'nowa'       => $detail['nowa'],
  'grandtotal' => (int)$detail['grandtotal'],
  'items'      => $items
]);
?>