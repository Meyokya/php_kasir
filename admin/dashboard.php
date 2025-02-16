<?php
session_start();
include 'D:/xampp/htdocs/kasir_LSP/database.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit();
}

$query_barang = mysqli_query($conn, "SELECT COUNT(*) AS total_barang FROM barang");
$data_barang = mysqli_fetch_assoc($query_barang);

$query_penjualan = mysqli_query($conn, "SELECT COUNT(*) AS total_penjualan FROM penjualan");
$data_penjualan = mysqli_fetch_assoc($query_penjualan);

$query_pengguna = mysqli_query($conn, "SELECT COUNT(*) AS total_pengguna FROM pengguna");
$data_pengguna = mysqli_fetch_assoc($query_pengguna);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <!-- Sidebar -->
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
        <header>
            <h1>Selamat Datang <?= $_SESSION['nama_pengguna']; ?>!</h1>
        </header>
        <section class="stats">
            <div class="card">
                <h3>Total Barang</h3>
                <p><?= $data_barang['total_barang']; ?> item</p>
            </div>
            <div class="card">
                <h3>Total Penjualan</h3>
                <p><?= $data_penjualan['total_penjualan']; ?> item</p>
            </div>
            <div class="card">
                <h3>Total Pengguna</h3>
                <p><?= $data_pengguna['total_pengguna']; ?> item</p>
            </div>
        </section>
    </main>

</body>

</html>