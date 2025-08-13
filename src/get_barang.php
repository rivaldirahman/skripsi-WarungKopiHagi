<?php
include '../stok/koneksi.php';

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

$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

header('Content-Type: application/json');
echo json_encode($items);
?>