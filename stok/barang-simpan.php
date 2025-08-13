<?php
include ('koneksi.php');

//data form

if(isset($_POST['kirim'])) {
	// Ambil data form
	$foto_manual = trim($_POST['foto_manual']); // input manual dari user
	$foto_barang = $_FILES['foto_barang']['name'];
	$source = $_FILES['foto_barang']['tmp_name'];
	$folder = '../img/upload/';
	
	$kode_barang = $_POST['kode_barang'];
	$kategori = $_POST['kategori'];
	$nama_barang = $_POST['nama_barang'];
	$harga_beli = $_POST['harga_beli'];
	$harga_jual= $_POST['harga_jual'];
	$stok = $_POST['stok'];

// Cek apakah user upload foto baru
if ($foto_barang != '') {
    move_uploaded_file($source, $folder . $foto_barang);
} else if ($foto_manual != '') {
  // Jika user isi manual path ke file di dalam /img/
    $foto_barang = $foto_manual;
} else {
	// Kalau tidak upload atau input manual, pakai foto lama
    $foto_barang = $foto_lama; 
}

 // Cek apakah nama barang sudah ada
    $cek_nama = mysqli_query($koneksi, "SELECT * FROM barang WHERE nama_barang = '$nama_barang'");
    if (mysqli_num_rows($cek_nama) > 0) {
        echo "<script>alert('Tambah barang gagal: Nama Barang sudah digunakan'); window.location.href='index.php?page=barang-form';</script>";
        exit;
    }

//proses simpan
$sql = "INSERT INTO barang (kode_barang, kategori, nama_barang, harga_beli, harga_jual, stok, foto_barang) VALUES ('$kode_barang', '$kategori', '$nama_barang', '$harga_beli', '$harga_jual', '$stok', '$foto_barang')";
$query = mysqli_query($koneksi, $sql);

if ($query) {
	//redirect ke halaman barang
	//header("location: ?page=barang-tambah");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=barang-data'>");
}else {
	//header("location: ?page=barang-form");
	echo ("<meta http-equiv='refresh' content='0;url=index.php?page=barang-form'>");
}
}

?>