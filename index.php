<?php

session_start();

// ambil koneksi
include "stok/koneksi.php";

// Ambil data dari session jika ada
$valNama = $_SESSION['register_data']['nama'] ?? '';
$valPassword = $_SESSION['register_data']['password'] ?? '';
$valEmail = ''; // kosongkan email kalau error

// Ambil info sukses/gagal
$registerSuccess = $_SESSION['register_success'] ?? false;
$registerError = $_SESSION['register_error'] ?? '';

// Bersihkan flash session
unset($_SESSION['register_data'], $_SESSION['register_success'], $_SESSION['register_error']);

// Ambil data persis seperti get_barang.php → supaya 1 format
$result = mysqli_query($koneksi, "
SELECT
kode_barang AS id,
nama_barang AS name,
harga_jual AS price,
foto_barang AS img,
stok AS stok,
kategori
FROM barang
ORDER BY
CASE
WHEN kategori = 'Makanan' THEN 1
WHEN kategori = 'Minuman' THEN 2
ELSE 3
END,
kode_barang ASC
");


$barang = [];

while ($row = mysqli_fetch_assoc($result)) {
// pastikan harga dan stok dalam bentuk integer (untuk jaga-jaga, karena JS butuh number)
$row['price'] = (int) $row['price'];
$row['stok'] = (int) $row['stok'];

$barang[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Kopi Hagi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Remixicon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- App -->
    <script defer src="src/app.js" async></script>

    <!-- Midtrans -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-MDx4HiFHaD3BWxyn"></script>


    <?php if (isset($_GET['reset']) && $_GET['reset'] === 'password') : ?>
    <script>
    window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('reset-new-password-modal')?.classList.add('show');
    });
    </script>
    <?php endif; ?>

</head>

<body>

    <!-- Navbar Start -->
    <nav class="navbar" x-data>

        <a href="#" class="navbar-logo">Warkop<span>Hagi</span></a>

        <div class="navbar-nav">
            <a href="#beranda">Beranda</a>
            <a href="#about">Tentang Kami</a>
            <a href="#menu">Menu</a>
            <a href="#pembelian">Pembelian</a>
            <a href="#contact">Kontak</a>
        </div>

        <div class="navbar-extra">
            <a href="#" id="shopping-cart-button"><i data-feather="shopping-cart"></i>
                <span class="quantity-badge" x-show="$store.cart?.quantity" x-text="$store.cart?.quantity"></span>
            </a>
            <?php if (isset($_SESSION['login'])): ?>
            <!-- Jika sudah login -->
            <a href="#" id="user-info-button"><i data-feather="user"></i></a>
            <?php else: ?>
            <!-- Jika belum login -->
            <a href="#" id="login-button"><i data-feather="log-in"></i></a>
            <?php endif; ?>

            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>

        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
        <div class="modal-login" id="user-info-modal">
            <div class="login-box">
                <span class="login-close" id="closeUserModal">&times;</span>
                <center><img src="img/user-circle.svg" alt="Avatar Pengguna" class="avatar"></center><br>
                <h2 class="login-title">Halo, <?= htmlspecialchars($_SESSION['nama_pengguna']) ?>
                    <div style="margin-top: 25px; margin-left:auto;">
                        <center><a href="javascript:void(0)" onclick="showRiwayatModal()" class="login-btn">Riwayat
                                Pemesanan</a>
                            <a href="logout.php" class="login-btn" id="signOutBtn" style="background-color:red">Keluar
                                Akun</a>
                        </center>
                    </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Modal Riwayat Pemesanan -->
        <div id="riwayatModal" class="modal-login">
            <div class="modal-content">
                <h2 class="modal-title">Riwayat Pemesanan</h2>
                <div class="modal-table-wrapper">
                    <table class="modal-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Order ID</th>
                                <th>Nama</th>
                                <th>No WA</th>
                                <th>Barang</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="riwayatBody">
                            <!-- Diisi via JS -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button onclick="closeRiwayatModal()" class="modal-close-btn">Tutup</button>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Logout -->
        <div class="modal-login" id="logout-confirmation-modal">
            <div class="login-box">
                <h2 class="login-title">Konfirmasi</h2>
                <p class="login-info" style="text-align: center;">Apakah anda yakin ingin keluar?</p>
                <div style="display: flex; justify-content: space-around; margin-top: 20px;">
                    <button class="login-btn" id="logoutYes" style="width: 40%; background-color:red;">Ya</button>
                    <button class="login-btn" id="logoutNo" style="width: 40%; background-color: gray;">Tidak</button>
                </div>
            </div>
        </div>

        <!-- User Form -->
        <div class="modal-login" id="login-modal">
            <div class="login-box">
                <span class="login-close" id="closeBtn">&times;</span>
                <h2 class="login-title">Log In</h2>
                <form class="login-form" method="POST" action="login.php">
                    <div class="form-group">
                        <label for="emailInput">Email</label>
                        <input id="emailInput" type="email" name="email" placeholder="Masukkan email anda" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordInput">Password</label>
                        <input id="passwordInput" type="password" name="password" placeholder="Masukkan password anda"
                            required>
                        <label class="toggle-password">
                            <input type="checkbox" id="showPassword"> Tampilkan Password
                        </label>
                    </div>
                    <p class="login-info">Belum punya akun? <a href="#" id="openRegister">Daftar</a></p>
                    <p class="login-info"><a href="#" id="openReset">Lupa password?</a></p>
                    <button class="login-btn" type="submit" name="login">Masuk</button>
                </form>
            </div>
        </div>

        <!-- Daftar akun -->
        <div class="modal-login" id="register-modal">
            <div class="login-box">
                <span class="login-close" id="closeRegister">&times;</span>
                <h2 class="login-title">Buat Akun</h2>
                <form class="login-form" method="POST" action="daftarakun.php">
                    <div class="form-group">
                        <label for="registerName">Nama Lengkap</label>
                        <input id="registerName" name="registerName" value="<?= htmlspecialchars($valNama) ?>"
                            type="text" placeholder="Masukkan nama lengkap anda" required>
                    </div>
                    <div class="form-group">
                        <label for="registerEmail">Email</label>
                        <input id="registerEmail" name="registerEmail" value="<?= htmlspecialchars($valEmail) ?>"
                            type="email" placeholder="Masukkan email anda" required>
                    </div>
                    <div class="form-group">
                        <label for="registerPassword">Password</label>
                        <input id="registerPassword" name="registerPassword"
                            value="<?= htmlspecialchars($valPassword) ?>" type="password"
                            placeholder="Masukkan password anda" required>
                        <label class="toggle-password">
                            <input type="checkbox" id="showRegisterPassword"> Tampilkan Password
                        </label>
                    </div>
                    <p class="login-info">Sudah punya akun? <a href="#" id="openLogin">Login disini</a></p>
                    <button class="login-btn" type="submit" name="kirim">Buat Akun</button>
                </form>
            </div>
        </div>

        <!-- Modal Reset Password Baru -->

        <?php
        // Ambil email terakhir yang diketik user
        $resetEmail = $_SESSION['reset_data']['email'] ?? '';
        unset($_SESSION['reset_data']);
        ?>

        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'password'): ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Buka modal reset password
            const resetModal = document.getElementById('reset-new-password-modal');
            if (resetModal) {
                resetModal.classList.add('show');
            }

            // Hilangkan ?reset=password dari URL tanpa reload
            history.replaceState(null, '', 'index.php');
        });
        </script>
        <?php endif; ?>


        <?php if (isset($_SESSION['reset_success'])): ?>
        <script>
        alert('Password berhasil diubah! Silakan login kembali.');
        </script>
        <?php unset($_SESSION['reset_success']); ?>

        <?php elseif (isset($_SESSION['reset_error'])): ?>
        <script>
        alert('<?= $_SESSION['reset_error']; ?>');
        </script>
        <?php unset($_SESSION['reset_error']); ?>
        <?php endif; ?>

        <div class="modal-login" id="reset-new-password-modal">
            <div class="login-box">
                <span class="login-close" id="closeResetNewPassword">&times;</span>
                <h2 class="login-title">Reset Password</h2>
                <form class="login-form" method="POST" action="resetpassword.php">
                    <div class="form-group">
                        <label for="forgotEmail">Email</label>
                        <input id="forgotEmail" type="email" name="email" placeholder="Masukkan email anda"
                            value="<?= htmlspecialchars($resetEmail) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Password Baru</label>
                        <input id="newPassword" name="newPassword" type="password" placeholder="Masukkan password baru"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Konfirmasi Password</label>
                        <input id="confirmPassword" name="confirmPassword" type="password"
                            placeholder="Konfirmasi password baru" required>
                        <label class="toggle-password">
                            <input type="checkbox" id="showResetPassword"> Tampilkan Password
                        </label>
                    </div>
                    <p class="login-info">Login kembali? <a href="#" id="backToLoginFromReset">Klik disini</a></p>
                    <button class="login-btn" type="submit" name="kirim">Reset Password</button>
                </form>
            </div>
        </div>


        <!-- Shopping Cart Start -->
        <div class="shopping-cart">
            <template x-for="(item, index) in $store.cart?.items" x-keys="index">
                <div class="cart-item">
                    <img :src="`img/produk/${item.img}`" :alt="item.name">
                    <div class="item-detail">
                        <h3 x-text="item.name"></h3>
                        <div class="item-price">
                            <span>Rp. </span><span x-text="item.price">
                            </span> &times;
                            <button id="remove" @click="$store.cart.remove(item.id)">&minus;</button>
                            <span x-text="item.quantity"></span>
                            <button id="add" @click="$store.cart.add({...item})">&plus;</button> &equals;
                            <span>Rp. </span><span x-text="item.total"></span>
                        </div>
                    </div>
                </div>
            </template>
            <h4 x-show="!$store.cart?.items?.length" style="margin-top: 1rem;">Keranjang Masih Kosong!</h4>
            <h4 x-show="$store.cart?.items?.length">Total : <span>Rp. </span><span x-text="$store.cart?.total"></span>
            </h4>

            <div class="form-container" x-show="$store.cart?.items?.length">
                <form action="" id="checkoutForm">
                    <!-- Hidden Input Elements -->
                    <input type="hidden" name="items" :value="JSON.stringify($store.cart?.items)">
                    <input type="hidden" name="total" :value="$store.cart?.total">

                    <h5>Data Pelanggan</h5>

                    <label for="nama">
                        <span>Nama </span>
                        <input type="text" name="namapembeli" id="namapembeli" pattern="[A-Za-z\s]+" required>
                    </label>

                    <label for="notelp">
                        <span>Nomor WhatsApp </span>
                        <input type="text" name="phone" id="phone">
                    </label>
                    <button class="checkout-button disabled" type="submit" id="checkout-button"
                        value="checkout">Bayar</button>
                    <label for="informasi-mau-nambah">
                        <span style="color: transparent;">KALAU MAU NAMBAH PESANAN SETELAH TRANSAKSI PESANAN
                            SEBELUMNYA SUDAH SELESAI, SILAHKAN
                            DATANG KE
                            KASIR !</span>
                    </label>

                </form>
            </div>
        </div>
        <!-- Shopping Cart End -->

    </nav>
    <!-- Navbar End -->

    <!-- Hero Section Start -->
    <section class="hero" id="beranda">
        <div class="mask-container">
            <main class="content">
                <h1>Mari Nikmati Secangkir <span>Kopi</span></h1>
                <h2><i>"Tempat Kumpul Sederhana, Penuh Kenangan Istimewa"</i></h2>
            </main>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- About Section Start -->
    <section id="about" class="about">
        <h2><span>Tentang</span> Kami</h2>

        <div class="row">
            <div class="about-img">
                <img src="img/tentang/tentang-kami.jpg" alt="Tentang Kami">
            </div>

            <div class="content">
                <h3>Sekilas Perjalanan Warkop <span style="color: #967259;">Hagi</span></h3>
                <p style="font-size: small; font-weight: bold;">Kisah bermula oleh orang tersayang kami, kedua orang
                    tua, yang mendirikan
                    tempat hangat ini. awal
                    mula, warkop hagi bukanlah tempat untuk berkumpul bersama, melainkan hanya tempat jual-beli sembako.
                    seperti, beras, telur, minyak goreng, ada juga beragam jenis rokok, minuman gelas, dan banyak lagi.
                    Berjalannya waktu, sudah hitungan puluhan tahun, tempat sembako dan apa saja itu berubah menjadi
                    warung kopi. kenapa? dengan mengikuti tren dan budaya masyarakat yang kian berubah, kami memutuskan
                    untuk menjadikan warung kopi. sederhana saja, namun berkesan.</p>
                <p style="font-size: small; font-weight: bold;">Berlokasi di Jalan Duren Tiga Barat 6, Jakarta Selatan,
                    kami menyediakan tempat berkumpul bersama
                    teman ataupun orang tersayang sambil menikmati hidangan cemilan dan kopi hangat yang semakin
                    menghangatkan suasana. Warkop hagi menyediakan tempat meski seadanya, namun dapat menampung pembeli
                    yang ingin bersantai bercerita terlebih dahulu. Boleh juga bagi yang ingin memesan untuk dibawa
                    pulang.</p>
            </div>
        </div>
    </section>
    <!-- About Section End -->

    <!-- Menu Section Start -->
    <section id="menu" class="menu">
        <h2><span>Menu</span> Kami</h2>
        <p style="font-size: small; font-weight: bold;">Dari makanan enak sampai minuman nikmat. </p>

        <!-- Bagian Makanan -->
        <div class="row" style="margin-top: 40px">
            <h3 style="width: 100%; margin-bottom: 30px; margin-left: 150px; font-size:large;">
                Menu Makanan :</h3>
            <?php 
            $qry = mysqli_query($koneksi, "SELECT nama_barang, harga_jual, foto_barang, stok from barang where kategori = 'Makanan' order by kode_barang asc");
            foreach ($qry as $row) :
             ?>
            <div class="menu-card">
                <img src="img/upload/<?= htmlspecialchars($row['foto_barang']); ?>"
                    alt="<?= htmlspecialchars($row['nama_barang']); ?>" class="menu-card-img">
                <h3 class="menu-card-title"><?= $row['nama_barang']; ?></h3>
                <p class="menu-card-price">Rp. <?= number_format($row['harga_jual'], 0, ',', '.'); ?></p>
            </div>
            <?php 
        endforeach;
        ?>
        </div>

        <!-- Bagian Minuman -->
        <div class="row" style="margin-top: 40px">
            <h3 style="width: 100%; margin-bottom: 30px; margin-left: 150px; font-size:large;">
                Menu Minuman :</h3>
            <?php 
        
            $qry = mysqli_query($koneksi, "SELECT nama_barang, harga_jual, foto_barang, stok from barang where kategori = 'Minuman' order by kode_barang asc");
            foreach ($qry as $row) :
             ?>
            <div class="menu-card">
                <img src="img/upload/<?= htmlspecialchars($row['foto_barang']); ?>"
                    alt="<?= htmlspecialchars($row['nama_barang']); ?>" class="menu-card-img">
                <h3 class="menu-card-title"><?= $row['nama_barang']; ?></h3>
                <p class="menu-card-price">Rp. <?= number_format($row['harga_jual'], 0, ',', '.'); ?></p>
            </div>
            <?php 
        endforeach;
        ?>
        </div>

    </section>

    <!-- Menu Section End -->

    <!-- Pembelian Section Start -->
    <section class="pembelian" id="pembelian"
        x-data="pembelian(<?= htmlspecialchars(json_encode($barang), ENT_QUOTES, 'UTF-8') ?>)">
        <h2><span>Menu</span> Pembelian</h2>
        <p style="font-size: small; font-weight: bold;">Langsung saja dipesan!</p>

        <div class="filter-kategori" style="text-align: center; margin-bottom: 2rem;">
            <button @click="selectedKategori = ''" :class="{ 'active': selectedKategori === '' }">Semua</button>

            <button @click="selectedKategori = 'Makanan'"
                :class="{ 'active': selectedKategori === 'Makanan' }">Makanan</button>

            <button @click="selectedKategori = 'Minuman'"
                :class="{ 'active': selectedKategori === 'Minuman' }">Minuman</button>
        </div>

        <div class="row">
            <template x-for="(item, index) in filteredItems" :key="item.id">
                <div class="pembelian-card">
                    <div class="pembelian-icons">
                        <!-- <a href="#" @click.prevent="$store.cart.add({...item})"> -->
                        <a href="#" @click.prevent="item.stok > 0 && $store.cart.add({ ...item })"
                            :class="{'pointer-events-none opacity-50 cursor-not-allowed': item.stok == 0}">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <use href="img/feather-sprite.svg#shopping-cart" />
                            </svg>
                        </a>
                    </div>
                    <div class="pembelian-image">
                        <img :src="`img/upload/${item.img}`" :alt="item.name">
                    </div>
                    <div class="pembelian-content">
                        <h3 style="font-size: large;" x-text="item.name"></h3>
                        <div class="pembelian-stars">
                            <svg width="20" height="20" fill="currentColor" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <use href="img/feather-sprite.svg#star" />
                            </svg>
                            <svg width="20" height="20" fill="currentColor" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <use href="img/feather-sprite.svg#star" />
                            </svg>
                            <svg width="20" height="20" fill="currentColor" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <use href="img/feather-sprite.svg#star" />
                            </svg>
                            <svg width="20" height="20" fill="currentColor" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <use href="img/feather-sprite.svg#star" />
                            </svg>
                            <svg width="20" height="20" fill="currentColor" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <use href="img/feather-sprite.svg#star" />
                            </svg>
                        </div>
                        <div class="pembelian-price">Rp. <span x-text="item.price"></span></div><br>
                        <div class="pembelian-stok">Stok tersisa : <span x-text="item.stok"></span></div>
                    </div>

                </div>
            </template>
        </div>
    </section>
    <!-- Pembelian Section End -->

    <!-- Contact Section Start -->
    <section id="contact" class="contact">
        <h2><span>Kontak</span> Kami</h2>
        <p style="font-size: small; font-weight: bold;">Bagi yang ingin datang dan menghubungi kami
        </p>

        <div class="row">
            <iframe
                src="https://www.google.com/maps/embed?pb=!3m2!1sid!2sid!4v1736843103801!5m2!1sid!2sid!6m8!1m7!1sK8dUKf9Y5oKMaqLONs-nEg!2m2!1d-6.258387745541863!2d106.8288671962374!3f155.97736!4f0!5f0.7820865974627469"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>

            <form id="contactForm" action="kontak.php" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <i data-feather="user"></i>
                    <input type="text" name="namauser" maxlength="20" placeholder="Nama Anda" required>
                </div>
                <div class="input-group">
                    <i data-feather="mail"></i>
                    <input type="text" name="email" maxlength="50" placeholder="Email Anda" required>
                </div>
                <div class="input-group">
                    <i data-feather="message-square"></i>
                    <input type="text" name="pesan" maxlength="255" placeholder="Pesan Anda" required>
                </div>
                <button type="submit" class="btn">Kirim Pesan</button>
            </form>
            <div id="result"></div> <!-- untuk menampilkan status -->
        </div>
    </section>
    <!-- Contact Section End -->

    <!-- Footer start -->
    <footer>
        <div class="socials">
            <a href="mailto:rivaldirahman9@gmail.com" target="_blank" rel="noopener"><i data-feather="mail"></i></a>
            <a href="https://www.linkedin.com/in/rivaldi-rahman/" target="_blank" rel="noopener"><i
                    data-feather="linkedin"></i></a>
        </div>

        <div class="links">
            <a href="#beranda">Beranda</a>
            <a href="#about">Tentang Kami</a>
            <a href="#menu">Menu</a>
            <a href="#pembelian">Pembelian</a>
            <a href="#contact">Kontak</a>
        </div>

        <div class="credit">
            <p>Dibuat dengan ♡ oleh <a href="">Rivaldi Rahman</a> | &copy; 2025</p>
        </div>

    </footer>
    <!-- Footer end -->

    <!-- Modal box konfirmasi contact - Start-->
    <div id="confirmModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:999;">
        <div
            style="background:#111; color:#fff; width:320px; margin:100px auto; padding:20px; border-radius:10px; font-size:18px;">
            <h3 style="margin-top:0;">Konfirmasi</h3>
            <p>Apakah Anda yakin ingin mengirim pesan?</p>
            <div style="text-align:right;">
                <button id="yesBtn" style="margin-right:10px; padding:6px 12px;">Ya</button>
                <button onclick="closeModal()" style="padding:6px 12px;">Tidak</button>
            </div>
        </div>
    </div>

    <!-- Modal Status -->
    <div id="statusModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:999;">
        <div
            style="background:#111; color:#fff; width:320px; margin:100px auto; padding:20px; border-radius:10px; font-size:18px; text-align:center;">
            <h3 style="margin-top:0;">Status</h3>
            <p id="statusMessage">Mengirim pesan...</p>
            <button onclick="closeStatusModal()" style="margin-top:10px; padding:6px 12px;">Tutup</button>
        </div>
    </div>
    <!-- Modal Konfirmasi - End -->

    <!-- Feather Icons JS -->
    <script>
    feather.replace();
    </script>

    <!-- Javascript -->
    <script src="js/script.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($registerSuccess): ?>
        alert("Akun berhasil dibuat!");
        document.getElementById("register-modal").classList.add("show");
        document.getElementById("registerName").value = "";
        document.getElementById("registerEmail").value = "";
        document.getElementById("registerPassword").value = "";
        document.getElementById("showRegisterPassword").checked = false;
        document.getElementById("registerPassword").type = "password";
        <?php elseif ($registerError): ?>
        alert("<?= $registerError ?>");
        document.getElementById("register-modal").classList.add("show");
        document.getElementById("registerName").value = "<?= htmlspecialchars($valNama) ?>";
        document.getElementById("registerEmail").value = "";
        document.getElementById("registerPassword").value = "<?= htmlspecialchars($valPassword) ?>";
        <?php endif; ?>
    });
    </script>


    <script>
    const form = document.getElementById("contactForm");
    const confirmModal = document.getElementById("confirmModal");
    const statusModal = document.getElementById("statusModal");
    const statusMessage = document.getElementById("statusMessage");
    let formData = null;

    form.addEventListener("submit", function(e) {
        e.preventDefault(); // mencegah submit default
        formData = new FormData(form);
        confirmModal.style.display = "block";
    });

    document.getElementById("yesBtn").addEventListener("click", function() {
        confirmModal.style.display = "none";
        fetch("kontak.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.ok ? response.text() : Promise.reject())
            .then(() => {
                statusMessage.innerText = "Pesan berhasil dikirim!";
                statusModal.style.display = "block";
                form.reset();
            })
            .catch(() => {
                statusMessage.innerText = "Terjadi kesalahan saat mengirim pesan.";
                statusModal.style.display = "block";
            });
    });

    function closeModal() {
        confirmModal.style.display = "none";
    }

    function closeStatusModal() {
        statusModal.style.display = "none";
    }
    </script>


    <script>
    function tutupPopupSukses() {
        const popup = document.getElementById('popup-success');
        popup.style.display = 'none';
        location.reload(true);
        history.replaceState(null, '', 'index.php'); // reload penuh
    }
    </script>

    <!-- Popup Success -->
    <div id="popup-success"
        style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#000; color:#fff; border:2px solid #4CAF50; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.3); z-index:9999; width:480px; font-family:sans-serif;">

        <button onclick="tutupPopupSukses()"
            style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:18px; cursor:pointer; color:#aaa;"
            title="Tutup">&times;</button>

        <h2 style="color:#4CAF50; margin-bottom:10px;">✅ Pembayaran Berhasil</h2>
        <p><strong>Order ID:</strong> <span id="order-id-text"></span></p>
        <p><strong>Tanggal:</strong> <span id="popup-tgl"></span></p>
        <p><strong>Atas Nama:</strong> <span id="popup-nama"></span></p>
        <p><strong>No WhatsApp:</strong> <span id="popup-wa"></span></p>
        <table style="width:100%; border-collapse: collapse; margin-top:10px;">
            <thead>
                <tr style="background:#000; color:#fff;">
                    <th style="border:1px solid #ccc; padding:6px;">Barang</th>
                    <th style="border:1px solid #ccc; padding:6px;">Harga</th>
                    <th style="border:1px solid #ccc; padding:6px;">Jumlah</th>
                    <th style="border:1px solid #ccc; padding:6px;">Subtotal</th>
                </tr>
            </thead>
            <tbody id="popup-items"></tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right; font-weight:bold; padding:6px; border:1px solid #ccc;">
                        Total</td>
                    <td style="padding:6px; font-weight:bold; border:1px solid #ccc;" id="popup-total">Rp 0</td>
                </tr>
            </tfoot>
        </table>
        <p style="margin-top:15px; font-size:13px;">Terima kasih atas pembelian Anda di <strong>Warung Kopi
                Hagi</strong>.</p>
    </div>

</body>

</html>