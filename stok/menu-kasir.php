  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
          <span class="brand-text font-weight-light">
              <center>
                  <img src="../img/logo/baru.jpg" alt="logo" style="height:70px; width:170px;">
              </center>
          </span>
      </a>

      <?php
					// memanggil koneksi
					include_once ('koneksi.php');
					
          $username = $_SESSION['username'];
					// menampilkan data
					$sql = "SELECT nama_user FROM user WHERE username = '$username'";
					$query = mysqli_query($koneksi, $sql);
         
					$result = mysqli_fetch_assoc($query);
			?>

      <!-- Sidebar -->
      <div class=" sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                  <img src="dist/img/avatar5.png" class="img-circle elevation-2" alt="Foto Admin"
                      style="height: 50px; width: 50px; margin-left: -10px;">
              </div>
              <div class=" info mt-2 d-flex">
                  <a href="#" class="d-block" style="font-weight: bold;">Kasir <?php echo $result['nama_user']; ?></a>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                  data-accordion="false">
                  <li class="nav-item">
                      <a href="?page=barang-data" class="nav-link">
                          <i class="nav-icon fas fa-copy"></i>
                          <p>
                              Barang
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="?page=transaksi-data" class="nav-link">
                          <i class="nav-icon fas fa-book"></i>
                          <p>
                              Transaksi
                          </p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="?page=kontak" class="nav-link">
                          <i class="nav-icon far fa-envelope"></i>
                          <p>
                              Kontak
                          </p>
                      </a>
                  </li>


              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>