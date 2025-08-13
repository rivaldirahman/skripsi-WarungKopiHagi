<?php
include ('koneksi.php');

// data form


if(isset($_POST['kirim'])) {
	// Ambil data form
    $nama_akun = $_POST['nama_akun'];
	$nama_pengguna = $_POST['nama_pengguna'];
	$kata_sandi= $_POST['kata_sandi'];
	$hak_akses = $_POST['hak_akses'];
	$kodeuser = $_POST['kode_user'];

  // Cek apakah username sudah ada
    $cek_username = mysqli_query($koneksi, "SELECT username FROM user WHERE username = '$nama_pengguna' AND kode_user != '$kodeuser'");
    if (mysqli_num_rows($cek_username) > 0) {
        echo "<script>alert('Tambah akun gagal: Username sudah digunakan'); window.location.href='index.php?page=akun-form-edit&kodeuser=$kodeuser';</script>";
        exit;
    }

// proses simpan
$sql = "UPDATE user SET nama_user = '$nama_akun', username = '$nama_pengguna', password = '$kata_sandi', hak_akses = '$hak_akses' WHERE kode_user = '$kodeuser'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
	//redirect ke halaman barang
	//header("location: ?page=barang-tambah");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=akun-data'>");
}else {
	//header("location: ?page=barang-form");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=akun-form'>");
}
}

// echo $sql;

?>