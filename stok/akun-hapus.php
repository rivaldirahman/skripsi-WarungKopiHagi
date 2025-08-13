<?php
include ('koneksi.php');

// query delete
$kodeuser = $_GET['kodeuser'];

$sql = "DELETE FROM user WHERE kode_user = '$kodeuser'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
	//redirect ke halaman barang
	//header("location: ?page=barang-tambah");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=akun-data'>");
}else {
	//header("location: ?page=barang-form");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=akun-data'>");
}

?>