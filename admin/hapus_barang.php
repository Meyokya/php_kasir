<?php
session_start();
include 'D:/xampp/htdocs/kasir_LSP/database.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit();
}

// Pastikan ada ID barang yang dikirim
if (!isset($_GET['id'])) {
    header("Location: kelola_barang.php?error=ID barang tidak ditemukan");
    exit();
}

$id_barang = $_GET['id'];
$query = "DELETE FROM barang WHERE id_barang = '$id_barang'";

if (mysqli_query($conn, $query)) {
    header("Location: kelola_barang.php?success=Barang berhasil dihapus");
    exit();
} else {
    header("Location: kelola_barang.php?error=Gagal menghapus barang");
    exit();
}
?>
