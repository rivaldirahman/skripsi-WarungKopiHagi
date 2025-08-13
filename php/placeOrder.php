<?php

session_start(); 

// header('Content-Type: application/json');

include ('../stok/koneksi.php');

require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php'; 

//SAMPLE REQUEST START HERE

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-qQ53ze4fG78JBYodw3HAbuWP';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

// $params = array(
//     'transaction_details' => array(
//         'order_id' => rand(),
//         'gross_amount' => $_POST['total'],
//     ),
//     'item_details' => json_decode($_POST['items'], true),
//     'customer_details' => array(
//         'first_name' => $_POST['name'],
//         'email' => $_POST['email'],
//         'phone' => $_POST['phone'],
//     ),
// );

// $snapToken = \Midtrans\Snap::getSnapToken($params);

// echo $snapToken;

// // proses simpan transaksi sementara (keranjang)
// $sql = "INSERT INTO transaksi_tmp (id_pembelian, kode_barang, nama_barang, harga_jual, jumlah, subtotal) VALUES ('', '$params('item_details'['items'['id']])', '$params('item_details'['items'['name']])', '$params('item_details'['items'['price']])', '$params('item_details'['items'['quantity']])', '$params('item_details'['items'['id']])' ";

// $query = mysqli_query($koneksi, $sql);

// ----

$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'anonim';
$email = mysqli_real_escape_string($koneksi, $email);

// Ambil data dari POST
$items = json_decode($_POST['items'], true);
$total = (int) $_POST['total'];
$name = $_POST['namapembeli'];
$phone = $_POST['phone'];
$order_id = 'ORDER-' . uniqid(); // Lebih baik daripada rand()

// // ✅ CEK APAKAH NOMEJA SUDAH DIPESAN
// $cek = mysqli_query($koneksi, "SELECT 1 FROM transaksi_tmp WHERE nomeja = '$nomeja' LIMIT 1");

// if (mysqli_num_rows($cek) > 0) {
//     echo json_encode(['error' => 'nomeja_sudah_dipesan']);
//     exit;
// }

// // Ambil order_id terakhir dari tabel transaksi
// $result = mysqli_query($koneksi, "SELECT  FROM transaksi ORDER BY no_transaksi DESC LIMIT 1");

// if ($row = mysqli_fetch_assoc($result)) {
//     $lastIdNum = (int) substr($row['no_transaksi'], 2); // Ambil angka saja, hilangkan 'TR'
//     $nextIdNum = $lastIdNum + 1;
// } else {
//     $nextIdNum = 1; // Jika belum ada data
// }

// Buat parameter untuk Midtrans
$params = array(
    'transaction_details' => array(
        'order_id' => $order_id,
        'gross_amount' => $total,
    ),
    'item_details' => $items,
    'customer_details' => array(
        'first_name' => $name,
        'phone' => $phone,
    ),
     'enabled_payments' => [
        // E-Wallet
        'qris',
        'gopay',
        'shopeepay',
        // 'dana',
        // QRIS
        // Virtual Account
        // 'mandiri_va',
        'bca_va',
        'bni_va',
        'bri_va',
        'permata_va',
        'cimb_va',
        'danamon_va',
        'maybank_va',
    ],
);

// Dapatkan Snap Token dari Midtrans
$snapToken = \Midtrans\Snap::getSnapToken($params);

// Hapus semua data dari transaksi_tmp terlebih dahulu
mysqli_query($koneksi, "DELETE FROM transaksi_tmp");

// Simpan ke database
foreach ($items as $item) {
    // $kode_barang = $item['id'];
    // $nama_barang = $item['name'];
    // $harga_jual = $item['price'];
    // $jumlah = $item['quantity'];
    // $subtotal = $item['total'];

    $kode_barang = isset($item['id']) ? $item['id'] : '';
    $nama_barang = isset($item['name']) ? $item['name'] : '';
    $harga_jual = isset($item['price']) ? (int) $item['price'] : 0;
    $jumlah = isset($item['quantity']) ? (int) $item['quantity'] : 0;
    $subtotal = isset($item['total']) ? (int) $item['total'] : ($harga_jual * $jumlah);

    if ($kode_barang === '') continue; // Skip jika tidak valid

    $sql = "INSERT INTO transaksi_tmp (order_id, kode_barang, nama_barang, harga_jual, jumlah, subtotal, atasnama, nowa, email)
            VALUES ('$order_id', '$kode_barang', '$nama_barang', '$harga_jual', '$jumlah', '$subtotal', '$name', '$phone', '$email')";
    mysqli_query($koneksi, $sql);
}

// // Kembalikan Snap Token ke JavaScript
// echo $snapToken;

// // balikan token dalam format JSON
// echo json_encode(['token' => $snapToken]);

echo json_encode([
    'token' => $snapToken,
    'order_id' => $order_id
]);



?>