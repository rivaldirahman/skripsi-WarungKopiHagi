<?php
include ('koneksi.php');

// query delete
$no = $_GET['no_pesan'];

$sql = "DELETE FROM kontak WHERE no_pesan = '$no'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
	//redirect ke halaman barang
	//header("location: ?page=barang-tambah");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=kontak'>");
}else {
	//header("location: ?page=barang-form");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=kontak'>");
}

?>