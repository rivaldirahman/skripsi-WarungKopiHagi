<?php
include ('koneksi.php');

//data form

if(isset($_POST['kirim'])) {
	// Ambil data form

	$nama_akun = $_POST['nama_akun'];
	$nama_pengguna = $_POST['nama_pengguna'];
	$kata_sandi= $_POST['kata_sandi'];
	$hak_akses = $_POST['hak_akses'];

 // Cek apakah username sudah ada
    $cek_username = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$nama_pengguna'");
    if (mysqli_num_rows($cek_username) > 0) {
        echo "<script>alert('Tambah akun gagal: Username sudah digunakan'); window.location.href='index.php?page=akun-form';</script>";
        exit;
    }


//proses simpan
$sql = "INSERT INTO user (kode_user, nama_user, username, password, hak_akses) VALUES ('', '$nama_akun', '$nama_pengguna', '$kata_sandi', '$hak_akses')";
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

?>