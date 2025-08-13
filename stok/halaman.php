<?php
	if ($_GET) {
		switch ($_GET['page']) {
			case '';
			if (!file_exists ("main-page.php")) die ("halaman main page tidak ditemukan");
			include "main-page.php";
			break;
			
			//barang
			case 'barang-data';
			if (!file_exists ("barang-data.php")) die ("halaman main page tidak ditemukan");
			include "barang-data.php";
			break;
			
			case 'barang-form';
			if (!file_exists ("barang-form.php")) die ("halaman main page tidak ditemukan");
			include "barang-form.php";
			break;
			
			case 'barang-form-edit';
			if (!file_exists ("barang-form-edit.php")) die ("halaman main page tidak ditemukan");
			include "barang-form-edit.php";
			break;
			
			case 'barang-hapus';
			if (!file_exists ("barang-hapus.php")) die ("halaman main page tidak ditemukan");
			include "barang-hapus.php";
			break;
			
			case 'transaksi-data';
			if (!file_exists ("transaksi-data.php")) die ("halaman main page tidak ditemukan");
			include "transaksi-data.php";
			break;

			case 'transaksi-hapus';
			if (!file_exists ("transaksi-hapus.php")) die ("halaman main page tidak ditemukan");
			include "transaksi-hapus.php";
			break;
			
			case 'kontak';
			if (!file_exists ("kontak.php")) die ("halaman main page tidak ditemukan");
			include "kontak.php";
			break;
			
			case 'kontak-hapus';
			if (!file_exists ("kontak-hapus.php")) die ("halaman main page tidak ditemukan");
			include "kontak-hapus.php";
			break;

			case 'apiwa';
			if (!file_exists ("apiwa.php")) die ("halaman main page tidak ditemukan");
			include "apiwa.php";
			break;

			//akun
			case 'akun-data';
			if (!file_exists ("akun-data.php")) die ("halaman main page tidak ditemukan");
			include "akun-data.php";
			break;
			
			case 'akun-form';
			if (!file_exists ("akun-form.php")) die ("halaman main page tidak ditemukan");
			include "akun-form.php";
			break;
			
			case 'akun-form-edit';
			if (!file_exists ("akun-form-edit.php")) die ("halaman main page tidak ditemukan");
			include "akun-form-edit.php";
			break;
			
			case 'akun-hapus';
			if (!file_exists ("akun-hapus.php")) die ("halaman main page tidak ditemukan");
			include "akun-hapus.php";
			break;

			case 'index';
			if (!file_exists ("index.php")) die ("halaman main page tidak ditemukan");
			include "index.php";
			break;
		}
	} else {
		if (!file_exists ("main-page.php")) die ("halaman main page tidak ditemukan");
			include "main-page.php";
	}
?>