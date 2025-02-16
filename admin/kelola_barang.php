<?php
session_start();
include 'D:/xampp/htdocs/kasir_LSP/database.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit();
}

$query = ("SELECT *, ROW_NUMBER() OVER (ORDER BY id_barang) AS nomor_urut FROM barang");
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
            <li><a href="kelola_barang.php"><i class="fa fa-archive" aria-hidden="true"></i> Kelola Barang</a></li>
            <li><a href="kelola_pengguna.php"><i class="fa fa-user" aria-hidden="true"></i> Kelola Pengguna</a></li>
            <li><a href="laporan_penjualan.php"><i class="fa fa-fax" aria-hidden="true"></i> Laporan Penjualan</a></li>
            <li><a href="../logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
        </ul>
    </div>

    <main>
        <header>
            <h1>Kelola Barang</h1>
        </header>

        <section class="content">
            <a href="tambah_barang.php" class="btn">+ Tambah Barang</a>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nama_barang']; ?></td>
                            <td><?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?= $row['stok']; ?></td>
                            <td>
                                <a href="edit_barang.php?id=<?= $row['id_barang']; ?>" class="btn-edit">Edit</a>
                                <a href="hapus_barang.php?id=<?= $row['id_barang']; ?>" class="btn-hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>