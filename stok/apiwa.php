<?php

include 'koneksi.php';

//token device
$token = "3EbBZrxzaJfmQkd2UnA5";


//send message function
function Kirimfonnte($token, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $data["target"],
            'message' => $data["message"],
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $token
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response; //log response fonnte
}

// ambil order id dari GET
$orderid = $_GET['orderid'] ?? '';

if ($orderid == '') {
    die('Order ID tidak valid');
}

//get user data from database
$res = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE order_id = '$orderid'");
$row = mysqli_fetch_assoc($res);

if ($row['status'] === 'SELESAI') {
    echo "Gagal : Transaksi sudah selesai !";
    echo "<meta http-equiv='refresh' content='5;url=index.php?page=transaksi-data'>";
    exit;
}

if (!$row) {
    die('Data tidak ditemukan untuk Order ID tersebut');
}

$data = [
    "target" => $row["nowa"],
    "message" => "PESANAN WARKOP HAGI SIAP DIAMBIL :)\n\n" .
    "Halo, " . $row["atasnama"] . "\n" .
    "Order id : " . $row["order_id"] . "\n" .
    "Tanggal : " . date('d-m-Y H:i:s', strtotime($row["tanggal"])) . ", \n\n" .
    "Dengan Pembelian : \n" . $row["nama_barang"] . "\n\n" .
    "Total Harga : " . $row["grandtotal"] . "\n\n" .
    "Terimakasih ♡"
];

//send message
Kirimfonnte($token, $data);

// UBAH STATUS
$sql = "UPDATE transaksi SET status = 'SELESAI' WHERE order_id = '$orderid'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
	//redirect ke halaman barang
	//header("location: ?page=barang-tambah");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=transaksi-data'>");
}else {
	//header("location: ?page=barang-form");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=transaksi-data'>");
}