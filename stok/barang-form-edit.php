<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>UBAH DATA BARANG</h1>
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
			  
			  // menampilkan data barang
			  $kode = $_GET['kode'];
			  $sql = "SELECT * FROM barang WHERE kode_barang = '$kode'";
			  $query = mysqli_query($koneksi, $sql);
			  $result = mysqli_fetch_array($query);
			  
			  ?>
                        <form action="barang-edit.php" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kode Barang</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"
                                        value="<?php echo $result['kode_barang']; ?>" disabled>
                                    <input name="kode_barang" type="hidden" class="form-control" id="exampleInputEmail1"
                                        value="<?php echo $result['kode_barang']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kategori</label>
                                    <select name="kategori" class="form-control" id="kategori" required>
                                        <option value="Makanan"
                                            <?= $result['kategori'] === 'Makanan' ? 'selected' : '' ?>>Makanan</option>
                                        <option value="Minuman"
                                            <?= $result['kategori'] === 'Minuman' ? 'selected' : '' ?>>Minuman</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div><label for="exampleInputEmail1">Foto Barang</label></div>
                                    <div><input type="hidden" class="form-control" name="foto_manual"
                                            value="<?php echo $result['foto_barang']; ?>"></div>
                                    <!-- Input untuk upload dari komputer -->
                                    <div><input name="foto_barang" type="file"></div>
                                    <!-- Preview -->
                                    <div><br><img src="../img/upload/<?php echo $result['foto_barang'];?>" width="120px"
                                            height="120px"></div>
                                    <!-- Hidden untuk simpan foto lama -->
                                    <input type="hidden" name="foto_lama" value="<?php echo $result['foto_barang']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Barang</label>
                                    <input name="nama_barang" type="text" class="form-control" id="exampleInputEmail1"
                                        value="<?php echo $result['nama_barang']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Harga Beli</label>
                                    <input name="harga_beli" type="number" class="form-control"
                                        id="exampleInputPassword1" value="<?php echo $result['harga_beli']; ?>"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Harga Jual</label>
                                    <input name="harga_jual" type="number" class="form-control"
                                        id="exampleInputPassword1" value="<?php echo $result['harga_jual']; ?>"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Stok</label>
                                    <input name="stok" type="number" class="form-control" id="exampleInputPassword1"
                                        value="<?php echo $result['stok']; ?>" required>
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