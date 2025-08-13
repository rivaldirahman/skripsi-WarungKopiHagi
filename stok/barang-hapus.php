<?php
include ('koneksi.php');

// query delete
$kode = $_GET['kode'];

$sql = "DELETE FROM barang WHERE kode_barang = '$kode'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
	//redirect ke halaman barang
	//header("location: ?page=barang-tambah");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=barang-data'>");
}else {
	//header("location: ?page=barang-form");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=barang-form'>");
}

?>