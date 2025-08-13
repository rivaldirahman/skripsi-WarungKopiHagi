<?php
include ('koneksi.php');

// query delete
$orderid = $_GET['orderid'];

$sql = "DELETE FROM transaksi WHERE order_id = '$orderid'";
$query = mysqli_query($koneksi, $sql);

echo $sql;

if ($query) {
	//redirect ke halaman barang
	//header("location: ?page=barang-tambah");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=transaksi-data'>");
}else {
	//header("location: ?page=barang-form");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=transaksi-data'>");
}

?>