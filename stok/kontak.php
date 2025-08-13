<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Data Kontak</h1>
            </div>
        </div>
    </div>
</section>

<style>
.table-responsive {
    overflow-x: auto;
}

.table {
    max-width: 100%;
    table-layout: auto;
}

.table thead th {
    text-align: center;
    vertical-align: middle;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}

.badge-warning {
    background-color: #ffc107;
    color: black;
}

.badge-success {
    background-color: #28a745;
    color: white;
}
</style>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-3 align-items-end">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari Kontak...">
                            </div>
                            <div class="col-md-4">
                                <select id="statusFilter" class="form-control">
                                    <option value="">Filter Status: Semua</option>
                                    <option value="Sudah Dibalas">Sudah Dibalas</option>
                                    <option value="Belum Dibalas">Belum Dibalas</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="customDateFilter" class="form-control"
                                    placeholder="Pilih Rentang Tanggal..." autocomplete="off">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tabel_kontak" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Pesan</th>
                                        <th>Nama Pengguna</th>
                                        <th>Email</th>
                                        <th>Pesan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM kontak ORDER BY tanggal DESC";
                                    $query = mysqli_query($koneksi, $sql);
                                    while($result = mysqli_fetch_array($query)) {
                                        $statusText = $result['sudah_dibalas'] == 1 ? 'Sudah Dibalas' : 'Belum Dibalas';
                                        $statusClass = $result['sudah_dibalas'] == 1 ? 'badge-success' : 'badge-warning';
                                    ?>
                                    <tr>
                                        <td><?= date('d-m-Y H:i:s', strtotime($result['tanggal'])) ?></td>
                                        <td><?= $result['no_pesan']; ?></td>
                                        <td><?= $result['namauser']; ?></td>
                                        <td><?= $result['email']; ?></td>
                                        <td><?= $result['pesan']; ?></td>
                                        <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                        <td>
                                            <?php if ($result['sudah_dibalas'] == 0): ?>
                                            <a href="kontak-balas.php?no_pesan=<?= $result['no_pesan']; ?>"
                                                target="_blank">KIRIM BALASAN</a> |
                                            <?php endif; ?>
                                            <a href="?page=kontak-hapus&no_pesan=<?= $result['no_pesan']; ?>">HAPUS</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Filter Script -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    const $filter = $('#customDateFilter');
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    const table = document.getElementById("tabel_kontak");
    const rows = table.querySelectorAll("tbody tr");

    // Init daterangepicker
    $filter.daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' - ',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
            firstDay: 1
        }
    });

    $filter.on('apply.daterangepicker', function(ev, picker) {
        const start = picker.startDate.format('DD-MM-YYYY');
        const end = picker.endDate.format('DD-MM-YYYY');
        $(this).val(`${start} - ${end}`);
        filterTable(); // langsung jalankan filter
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
        const statusValue = statusFilter.value.toLowerCase();
        const dateRange = $filter.val();

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const tanggal = cells[0].innerText.trim();
            const status = cells[5].innerText.trim().toLowerCase();
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
            const matchKeyword = [...cells].slice(1, -1).some(cell =>
                cell.innerText.toLowerCase().includes(keyword)
            );

            row.style.display = (matchDate && matchStatus && matchKeyword) ? "" : "none";
        });
    }

    searchInput.addEventListener("input", filterTable);
    statusFilter.addEventListener("change", filterTable);
    $filter.on("change", filterTable);
});
</script>