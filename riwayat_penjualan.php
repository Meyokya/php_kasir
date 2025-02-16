<?php
session_start();
include '../kasir_LSP/database.php';

// Pastikan user login sebagai kasir
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Kasir') {
    header("Location: index.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data penjualan yang dilakukan oleh kasir ini
$query = "
    SELECT p.id_penjualan, p.total_harga, p.metode_pembayaran, p.tanggal_penjualan
    FROM penjualan p
    WHERE p.id_pengguna = '$id_pengguna'
    ORDER BY p.tanggal_penjualan DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penjualan</title>
    <link rel="stylesheet" href="../kasir_LSP/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
    <h2>Riwayat Penjualan</h2>

    <!-- Tombol Cetak -->
    <button onclick="window.print()"><i class="fa fa-print" aria-hidden="true"></i> Cetak Riwayat</button>

    <table>
        <thead>
            <tr>
                <th>ID Penjualan</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($penjualan = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $penjualan['id_penjualan']; ?></td>
                        <td><?= $penjualan['tanggal_penjualan']; ?></td>
                        <td>Rp<?= number_format($penjualan['total_harga'], 0, ',', '.'); ?></td>
                        <td><?= $penjualan['metode_pembayaran']; ?></td>
                        <td>
                            <button class="detail-btn" onclick="toggleDetail(<?= $penjualan['id_penjualan']; ?>)">Lihat Detail</button>
                        </td>
                    </tr>
                    <!-- Detail Penjualan -->
                    <tr id="detail-<?= $penjualan['id_penjualan']; ?>" class="detail-row">
                        <td colspan="5">
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $id_penjualan = $penjualan['id_penjualan'];
                                    $query_detail = "
                                        SELECT dp.id_barang, b.nama_barang, dp.jumlah, dp.subtotal
                                        FROM detail_penjualan dp
                                        JOIN barang b ON dp.id_barang = b.id_barang
                                        WHERE dp.id_penjualan = '$id_penjualan'
                                    ";
                                    $detail_result = mysqli_query($conn, $query_detail);
                                    while ($detail = mysqli_fetch_assoc($detail_result)) :
                                    ?>
                                        <tr>
                                            <td><?= $detail['nama_barang']; ?></td>
                                            <td><?= $detail['jumlah']; ?></td>
                                            <td>Rp<?= number_format($detail['subtotal'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="empty-message">Belum ada transaksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function toggleDetail(id) {
        var detailRow = document.getElementById("detail-" + id);
        if (detailRow.style.display === "none" || detailRow.style.display === "") {
            detailRow.style.display = "table-row";
        } else {
            detailRow.style.display = "none";
        }
    }
</script>

</body>
</html>
