    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Barang</h1>
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
                        <a href="?page=barang-form" class="btn btn-block btn-primary">TAMBAH DATA</a>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" id="searchBarang" class="form-control"
                                        placeholder="Cari Barang...">
                                </div>
                                <div class="col-md-6 text-right">
                                    <select id="filterKategori" class="form-control"
                                        style="width: 200px; display: inline-block;">
                                        <option value="">Filter Kategori: Semua</option>
                                        <option value="makanan">Makanan</option>
                                        <option value="minuman">Minuman</option>
                                    </select>
                                </div>
                            </div>

                            <table id="tabel_barangdata" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Kategori</th>
                                        <th>Foto Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Harga Beli (Rp)</th>
                                        <th>Harga Jual (Rp)</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
					
					// menampilkan data barang
					$sql = "SELECT * FROM barang";
					$query = mysqli_query($koneksi, $sql);
         
					while($result = mysqli_fetch_array($query)) {
				  ?>
                                    <tr>
                                        <td><?php echo $result['kode_barang']; ?></td>
                                        <td><?php echo $result['kategori']; ?></td>
                                        <td><img src="../img/upload/<?php echo $result['foto_barang']; ?>" width="100px"
                                                height="100px" </td>
                                        <td><?php echo $result['nama_barang']; ?></td>
                                        <td><?php echo $result['harga_beli']; ?></td>
                                        <td><?php echo $result['harga_jual']; ?></td>
                                        <td><?php echo $result['stok']; ?></td>
                                        <td style="width: 130px;"> <a
                                                href="?page=barang-form-edit&kode=<?php echo $result['kode_barang']; ?>">UBAH
                                            </a> | <a
                                                href="?page=barang-hapus&kode=<?php echo $result['kode_barang']; ?>">
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

    <script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchBarang");
    const kategoriFilter = document.getElementById("filterKategori");
    const table = document.getElementById("tabel_barangdata");
    const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    function filterBarangTable() {
        const keyword = searchInput.value.toLowerCase();
        const kategori = kategoriFilter.value.toLowerCase();

        Array.from(rows).forEach(row => {
            const cells = row.getElementsByTagName("td");

            const kategoriText = cells[1].innerText.toLowerCase().trim(); // kolom kategori = index 1

            let matchSearch = false;
            let matchKategori = false;

            // Cek pencarian untuk semua kolom kecuali kategori (kolom 1)
            for (let i = 0; i < cells.length - 1; i++) {
                if (i !== 1 && cells[i].innerText.toLowerCase().includes(keyword)) {
                    matchSearch = true;
                    break;
                }
            }

            // Cek kecocokan kategori
            if (kategori === "" || kategoriText === kategori) {
                matchKategori = true;
            }

            // Tampilkan jika dua-duanya cocok
            row.style.display = (matchSearch && matchKategori) ? "" : "none";
        });
    }

    searchInput.addEventListener("keyup", filterBarangTable);
    kategoriFilter.addEventListener("change", filterBarangTable);
});
    </script>