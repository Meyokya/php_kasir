<?php
session_start();
include '../kasir_LSP/database.php';

// Pastikan user login sebagai kasir
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Kasir') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="../kasir_LSP/style1.css">
</head>
<body>

<div class="container">
    <h2>Dashboard Kasir</h2>
    <p>Selamat datang, Kasir!</p>

    <div class="menu">
        <a href="penjualan.php">Penjualan</a>
        <a href="riwayat_penjualan.php">Riwayat Penjualan</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

</body>
</html>
