<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>TAMBAH BARANG</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">DATA BARANG</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <?php
                        include ('koneksi.php');

                        // Ambil kode_barang terakhir
                        $sql = "SELECT MAX(kode_barang) AS kode_terakhir, foto_barang FROM barang";
                        $query = mysqli_query($koneksi, $sql);
                        $result = mysqli_fetch_array($query);

                        // Ekstrak angka dari kode terakhir
                        if ($result['kode_terakhir']) {
                            // Misalnya BRG-12 → ambil 12
                            $angka = (int) str_replace("BRG-", "", $result['kode_terakhir']);
                            $angka++;
                        } else {
                            $angka = 1; // Jika belum ada data
                        }

                        // Buat kode baru
                        $kode_baru = "BRG-" . str_pad($angka, 3, "0", STR_PAD_LEFT);
                        ?>

                        <form action="barang-simpan.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kode Barang</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"
                                        value="<?php echo $kode_baru; ?>" disabled>
                                    <input name="kode_barang" type="hidden" class="form-control"
                                        id="exampleInputPassword1" value="<?php echo $kode_baru; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kategori</label>
                                    <select name="kategori" class="form-control" id="kategori" required>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Minuman">Minuman</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Foto Barang</label>
                                    <input type="hidden" class="form-control" name="foto_manual"
                                        value="<?php echo $result['foto_barang']; ?>">
                                    <input type="file" class="form-control" name="foto_barang" required>
                                    <input type="hidden" name="foto_lama" value="<?php echo $result['foto_barang']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Barang</label>
                                    <input name="nama_barang" type="text" class="form-control" id="exampleInputEmail1"
                                        placeholder="Masukan Nama Barang" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Harga Beli</label>
                                    <input name="harga_beli" type="number" class="form-control"
                                        id="exampleInputPassword1" placeholder="Masukan Harga Beli" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Harga Jual</label>
                                    <input name="harga_jual" type="number" class="form-control"
                                        id="exampleInputPassword1" placeholder="Masukan Harga Jual" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Stok</label>
                                    <input name="stok" type="number" class="form-control" id="exampleInputPassword1"
                                        placeholder="Masukan Stok" required>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" name="kirim" class="btn btn-primary">Kirim</button>
                                <a href="?page=barang-data" class="btn btn-info">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>