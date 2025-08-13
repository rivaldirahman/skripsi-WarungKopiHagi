<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>TAMBAH AKUN</h1>
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
                            <h3 class="card-title">DATA AKUN</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <?php
                        include ('koneksi.php');
                        $sql = "SELECT * FROM user";
                        $query = mysqli_query($koneksi, $sql);
                        $result = mysqli_fetch_array($query);
                        ?>
                        <form action="akun-simpan.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Akun</label>
                                    <input name="nama_akun" type="text" class="form-control" id="exampleInputEmail1"
                                        placeholder="Masukan Nama Akun" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nama Pengguna</label>
                                    <input name="nama_pengguna" type="text" class="form-control"
                                        id="exampleInputPassword1" placeholder="Masukan Nama Pengguna" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Kata Sandi</label>
                                    <input name="kata_sandi" type="text" class="form-control" id="exampleInputPassword1"
                                        placeholder="Masukan Kata Sandi" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Hak Akses</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" value="kasir"
                                        disabled>
                                    <input name="hak_akses" type="hidden" class="form-control"
                                        id="exampleInputPassword1" value="kasir">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" name="kirim" class="btn btn-primary">Kirim</button>
                                <a href="?page=akun-data" class="btn btn-info">Kembali</a>
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