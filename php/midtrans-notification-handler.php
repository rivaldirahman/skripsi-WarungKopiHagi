<?php
// Midtrans Notification Handler

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo "Midtrans Notification Handler Active";
    http_response_code(200);
    exit;
}

include('../stok/koneksi.php');
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';

// Konfigurasi Midtrans
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$serverKey = 'SB-Mid-server-qQ53ze4fG78JBYodw3HAbuWP';
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

file_put_contents("notif-log.txt", "[" . date('Y-m-d H:i:s') . "] HIT!\n", FILE_APPEND);

// Ambil notifikasi dari Midtrans
try {
    $notif = new \Midtrans\Notification();
} catch (\Exception $e) {
    http_response_code(500);
    error_log("NOTIF ERROR: " . $e->getMessage());
    exit;
}

$transaction = strtolower($notif->transaction_status);
$order_id = $notif->order_id ?? '';

if (!$order_id) {
    http_response_code(400);
    error_log("NO ORDER ID");
    exit;
}

// Proses jika pembayaran sukses
if ($transaction === 'settlement' || $transaction === 'capture') {

    $query = mysqli_query($koneksi, "SELECT * FROM transaksi_tmp WHERE order_id = '$order_id'");
    if (!$query || mysqli_num_rows($query) === 0) {
        error_log("GAGAL: Tidak ada data transaksi_tmp untuk order_id = $order_id");
        http_response_code(404);
        exit("Transaksi tidak ditemukan");
    }

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');
    $grandtotal = 0;
    $nowa = '';
    $atasnama = '';
    $email = 'anonim'; // default jika tidak login
    $list_nama_barang = [];
    $items_for_stock = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $nama = mysqli_real_escape_string($koneksi, $row['nama_barang']);
        $email = $row['email'] ?: 'anonim';
        $jumlah = (int)$row['jumlah'];
        $harga_jual = (int)$row['harga_jual'];
        $subtotal = (int)$row['subtotal'];

        $list_nama_barang[] = "$nama (Rp. $harga_jual) x $jumlah = Rp. $subtotal";
        $grandtotal += $subtotal;
        $atasnama = mysqli_real_escape_string($koneksi, $row['atasnama']);
        $nowa = mysqli_real_escape_string($koneksi, $row['nowa']);
        $items_for_stock[] = ['nama' => $nama, 'jumlah' => $jumlah];
    }

    $nama_barang = implode(', ', $list_nama_barang);

    // Insert transaksi akhir
    $sql = "INSERT INTO transaksi (order_id, tanggal, nama_barang, grandtotal, atasnama, nowa, email, status)
            VALUES (
                '$order_id', '$tanggal', '$nama_barang', '$grandtotal', '$atasnama', '$nowa', '$email', 'DIBAYAR'
            )";

    if (!mysqli_query($koneksi, $sql)) {
        error_log("INSERT ERROR ($order_id): " . mysqli_error($koneksi));
        http_response_code(500);
        exit("Gagal insert transaksi");
    }

    // Jika insert berhasil, update stok barang
    foreach ($items_for_stock as $item) {
        $update_stok = mysqli_query($koneksi, "UPDATE barang SET stok = stok - {$item['jumlah']} WHERE nama_barang = '{$item['nama']}'");
        if (!$update_stok) {
            error_log("GAGAL UPDATE STOK untuk {$item['nama']}: " . mysqli_error($koneksi));
        }
    }

    // Hapus data sementara
    mysqli_query($koneksi, "DELETE FROM transaksi_tmp WHERE order_id = '$order_id'");

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'order_id' => $order_id]);
    exit;
}

// Jika bukan pembayaran sukses
http_response_code(200);
header('Content-Type: application/json');
echo json_encode(['status' => 'ignored', 'order_id' => $order_id]);
exit;