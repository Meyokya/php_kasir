<?php
session_start();
include 'D:/xampp/htdocs/kasir_LSP/database.php';

// Pastikan user login sebagai Admin
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit();
}

// Ambil filter tanggal (jika ada)
$tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';

$query = "SELECT id_penjualan, total_harga, metode_pembayaran, tanggal_penjualan 
          FROM penjualan";

if ($tanggal_mulai && $tanggal_selesai) {
    $query .= " WHERE tanggal_penjualan BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
}

$query .= " ORDER BY tanggal_penjualan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fa fa-home" aria-hidden="true"></i>  Dashboard</a></li>
            <li><a href="kelola_barang.php"><i class="fa fa-archive" aria-hidden="true"></i>  Kelola Barang</a></li>
            <li><a href="kelola_pengguna.php"><i class="fa fa-user" aria-hidden="true"></i>  Kelola Pengguna</a></li>
            <li><a href="laporan_penjualan.php"><i class="fa fa-fax" aria-hidden="true"></i>  Laporan Penjualan</a></li>
            <li><a href="../logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>  Logout</a></li>
        </ul>
    </div>
<main>
<header>Laporan Penjualan</header>
<div class="container">

    <!-- Filter Tanggal -->
    <form method="GET">
        <label for="tanggal_mulai">Dari Tanggal:</label>
        <input type="date" name="tanggal_mulai" value="<?= $tanggal_mulai ?>" required>

        <label for="tanggal_selesai">Sampai Tanggal:</label>
        <input type="date" name="tanggal_selesai" value="<?= $tanggal_selesai ?>" required>

        <button type="submit">Filter</button>
    </form>

    <!-- Tombol Cetak -->
    <a class="btn" href="" onclick="window.print()"><i class="fa fa-print" aria-hidden="true"></i> Cetak Laporan</a>

    <table border="1">
        <tr>
            <th>ID Penjualan</th>
            <th>Tanggal</th>
            <th>Kasir</th> <!-- Tetap ditampilkan sebagai 'Kasir' -->
            <th>Total Harga</th>
            <th>Metode Pembayaran</th>
            <th>Detail</th>
        </tr>
        <?php if (mysqli_num_rows($result) > 0) : ?>
            <?php while ($penjualan = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $penjualan['id_penjualan']; ?></td>
                    <td><?= $penjualan['tanggal_penjualan']; ?></td>
                    <td>Kasir</td> <!-- Langsung tampilkan "Kasir" -->
                    <td>Rp<?= number_format($penjualan['total_harga'], 0, ',', '.'); ?></td>
                    <td><?= $penjualan['metode_pembayaran']; ?></td>
                    <td>
                        <button onclick="toggleDetail(<?= $penjualan['id_penjualan']; ?>)">Lihat Detail</button>
                    </td>
                </tr>
                <!-- Detail Penjualan (disembunyikan dulu) -->
                <tr id="detail-<?= $penjualan['id_penjualan']; ?>" style="display: none;">
                    <td colspan="6">
                        <table border="1" width="100%">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
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
                        </table>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr><td colspan="6">Belum ada transaksi.</td></tr>
        <?php endif; ?>
    </table>
</div>
</main>

<script>
    function toggleDetail(id) {
        var detailRow = document.getElementById("detail-" + id);
        if (detailRow.style.display === "none") {
            detailRow.style.display = "table-row";
        } else {
            detailRow.style.display = "none";
        }
    }
</script>

</body>
</html>
