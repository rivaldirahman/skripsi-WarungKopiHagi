     <!-- Content Header (Page header) -->
     <section class="content-header">
         <div class="container-fluid">
             <div class="row mb-2">
                 <div class="col-sm-6">
                     <h1>Data Transaksi</h1>
                 </div>
             </div>
         </div><!-- /.container-fluid -->
     </section>

     <style>
.table-responsive {
    overflow-x: auto;
}

.table {
    max-width: 100%;
    table-layout: auto;
}

/* Buat semua header tabel rata tengah horizontal & vertikal */
.table thead th {
    text-align: center;
    vertical-align: middle;
}

/* No. Pesanan */
.table th:nth-child(1),
.table td:nth-child(1) {
    width: 60px;
    text-align: center;
}

/* Order ID */
.table th:nth-child(2),
.table td:nth-child(2) {
    width: 120px;
    text-align: center;
}

/* Tanggal Pesanan */
.table th:nth-child(3),
.table td:nth-child(3) {
    min-width: 140px;
    max-width: 160px;
    white-space: nowrap;
    text-align: center;
}

/* Detail Pesanan */
.table th:nth-child(4),
.table th:nth-child(4) {
    text-align: center;
}

.table td:nth-child(4) {
    min-width: 250px;
    max-width: 300px;
    white-space: normal;
    text-align: left;
}

/* Grand Total */
.table th:nth-child(5),
.table td:nth-child(5) {
    width: 100px;
    text-align: center;
    white-space: nowrap;
}

/* Atas Nama */
.table th:nth-child(6),
.table td:nth-child(6) {
    width: 150px;
    text-align: left;
}

/* No WhatsApp */
.table th:nth-child(7),
.table td:nth-child(7) {
    width: 140px;
    white-space: nowrap;
    text-align: center;
}

/* Email Pemesan */
.table th:nth-child(8),
.table td:nth-child(8) {
    min-width: 200px;
    white-space: normal;
    text-align: left;
}

/* Status Pembayaran */
.table th:nth-child(9),
.table td:nth-child(9) {
    width: 150px;
    text-align: center;
}

.status-dibayar {
    background-color: #28a745;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}

.status-selesai {
    background-color: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}
     </style>


     <!-- Main content -->
     <section class="content">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-12">
                     <!-- /.card -->

                     <div class="card">
                         <!-- /.card-header -->
                         <div class="card-body">

                             <div class="row mb-3 align-items-end">
                                 <div class="col-md-4">
                                     <input type="text" id="searchInput" class="form-control"
                                         placeholder="Cari Transaksi...">
                                 </div>
                                 <div class="col-md-4">
                                     <select id="statusFilter" class="form-control">
                                         <option value="">Filter Status: Semua</option>
                                         <option value="DIBAYAR">Dibayar</option>
                                         <option value="SELESAI">Selesai</option>
                                     </select>
                                 </div>
                                 <div class="col-md-4">
                                     <input type="text" id="customDateFilter" class="form-control"
                                         placeholder="Pilih Rentang Tanggal..." autocomplete="off">
                                 </div>
                             </div>

                             <div class="table-responsive">
                                 <table id="tabel_transaksi" name="tabel_transaksi"
                                     class="table table-bordered table-striped">
                                     <thead>
                                         <tr>
                                             <th>No. Pesanan</th>
                                             <th>Order ID</th>
                                             <th>Tanggal Pesanan</th>
                                             <th>Detail Pesanan</th>
                                             <th>Grand Total Pesanan</th>
                                             <th>Atas Nama</th>
                                             <th>No WhatsApp Pemesan</th>
                                             <th>Email Pemesan</th>
                                             <th>Status Pembayaran</th>
                                         </tr>
                                     </thead>
                                     <tbody>

                                         <?php
					// menampilkan data barang
					$sql = "SELECT * FROM transaksi ORDER BY id DESC";
					$query = mysqli_query($koneksi, $sql);
         
					while($result = mysqli_fetch_array($query)) {
				  ?>
                                         <tr>
                                             <td><?php echo $result['id']; ?></td>
                                             <td><?php echo $result['order_id']; ?></td>
                                             <td><?= date('d-m-Y H:i:s', strtotime($result['tanggal'])) ?></td>
                                             <td><?php echo $result['nama_barang']; ?></td>
                                             <td>Rp. <?php echo $result['grandtotal']; ?></td>
                                             <td><?php echo $result['atasnama']; ?></td>
                                             <td><?php echo $result['nowa']; ?></td>
                                             <td><?php echo $result['email']; ?></td>
                                             <td>
                                                 <?php
                                                    $status = strtoupper($result['status']);
                                                    $statusClass = '';

                                                    if ($status === 'DIBAYAR') {
                                                        $statusClass = 'status-dibayar';
                                                    } elseif ($status === 'SELESAI') {
                                                        $statusClass = 'status-selesai';
                                                    } 
                                                    ?>
                                                 <span class="<?= $statusClass; ?>"><?= $status; ?></span>
                                             </td>

                                             <td> <?php if ($status === 'DIBAYAR'): ?>
                                                 <a href="?page=apiwa&orderid=<?= $result['order_id']; ?>">KIRIM
                                                     KONFIRMASI</a> |
                                                 <?php endif; ?> <a
                                                     href="?page=transaksi-hapus&orderid=<?php echo $result['order_id']; ?>">
                                                     HAPUS</a>
                                             </td>
                                         </tr>
                                         <?php } ?>
                                     </tbody>
                                     <tfoot>
                                     </tfoot>
                                 </table>
                             </div>
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
    const $filter = $('#customDateFilter');
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    const table = document.getElementById("tabel_transaksi");
    const rows = table.querySelectorAll("tbody tr");

    $filter.daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' - ',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'
            ],
            firstDay: 1
        }
    });

    $filter.on('apply.daterangepicker', function(ev, picker) {
        const start = picker.startDate.format('DD-MM-YYYY');
        const end = picker.endDate.format('DD-MM-YYYY');
        $(this).val(`${start} - ${end}`);
        filterTable();
    });

    $filter.on('cancel.daterangepicker', function() {
        $(this).val('');
        filterTable();
    });

    function parseDate(dateStr) {
        const parts = dateStr.split(" ");
        const dateParts = parts[0].split("-");
        const timeParts = (parts[1] || "00:00:00").split(":");
        return new Date(
            parseInt(dateParts[2]), parseInt(dateParts[1]) - 1, parseInt(dateParts[0]),
            parseInt(timeParts[0]), parseInt(timeParts[1]), parseInt(timeParts[2])
        );
    }

    function filterTable() {
        const keyword = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const dateRange = $filter.val();

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const tanggal = cells[2].innerText.trim();
            const status = cells[8].innerText.trim().toUpperCase();
            const rowDate = parseDate(tanggal);

            let matchDate = true;
            if (dateRange && dateRange.includes(" - ")) {
                const [start, end] = dateRange.split(" - ");
                const startDate = parseDate(start);
                const endDate = parseDate(end);
                const rowDateOnly = new Date(rowDate.getFullYear(), rowDate.getMonth(), rowDate
                    .getDate());
                matchDate = rowDateOnly >= startDate && rowDateOnly <= endDate;
            }

            const matchStatus = !statusValue || status === statusValue;
            const matchKeyword = [...cells].slice(0, -1).some((cell, i) =>
                i !== 2 && i !== 8 && cell.innerText.toLowerCase().includes(keyword)
            );

            row.style.display = (matchDate && matchStatus && matchKeyword) ? "" : "none";
        });
    }

    searchInput.addEventListener("input", filterTable);
    statusFilter.addEventListener("change", filterTable);
    $filter.on("change", filterTable);
});
     </script>