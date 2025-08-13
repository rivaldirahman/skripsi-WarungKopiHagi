    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Akun</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- /.card -->

                    <div class="card">
                        <a href="?page=akun-form" class="btn btn-block btn-primary">TAMBAH DATA</a>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tabel_akundata" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th>Nama Pengguna</th>
                                        <th>Kata Sandi</th>
                                        <th>Hak Akses</th>
                                        <th>Masuk Sebagai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
					
					// menampilkan data barang
					$sql = "SELECT * FROM user WHERE hak_akses != 'admin'";
					$query = mysqli_query($koneksi, $sql);
         
					while($result = mysqli_fetch_array($query)) {
				  ?>
                                    <tr>
                                        <td><?php echo $result['nama_user']; ?></td>
                                        <td><?php echo $result['username']; ?></td>
                                        <td><?php echo $result['password']; ?></td>
                                        <td><?php echo $result['hak_akses']; ?></td>
                                        <td><a
                                                href="masuk-sebagai.php?kodeuser=<?php echo $result['kode_user']; ?>">MASUK</a>
                                        </td>
                                        <td> <a
                                                href="?page=akun-form-edit&kodeuser=<?php echo $result['kode_user']; ?>">UBAH
                                            </a> | <a
                                                href="?page=akun-hapus&kodeuser=<?php echo $result['kode_user']; ?>">
                                                HAPUS </a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->