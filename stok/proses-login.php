<?php

//aktivasi session
session_start();

//koneksi database
include "koneksi.php";

//mengambil data form
$username = $_POST['username'];
$password = $_POST['password'];

//membaca tabel user filter username dan password
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username' AND password = '$password' ");

//proses menjumlah isi hasil query
$check = mysqli_num_rows($query);

//menampilkan data hak akses
$login = mysqli_fetch_assoc($query);

//cek apakah user dan password ditemukan ?
if ($check > 0) {
	$kode_user = $login['kode_user'];

	//jika data ditemukan / nilai lebih dari 0
	if ($login['hak_akses'] == "admin") {
		//membaca data admin
		$_SESSION['kode_user'] = $kode_user;
		$_SESSION['username'] = $username;
		$_SESSION['hak_akses'] = $login['hak_akses'];
		header("location: index.php");
	} else if ($login['hak_akses'] == "kasir") {
		$_SESSION['kode_user'] = $kode_user;
		$_SESSION['username'] = $username;
		$_SESSION['hak_akses'] = $login['hak_akses'];
		header("location: index.php");
	}

	echo $check;
} else {
header("location: login.php");
}	


?>