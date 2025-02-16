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
$query = "SELECT * FROM barang WHERE id_barang = '$id_barang'";
$result = mysqli_query($conn, $query);
$barang = mysqli_fetch_assoc($result);

if (!$barang) {
    header("Location: kelola_barang.php?error=Barang tidak ditemukan");
    exit();
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);

    $query_update = "UPDATE barang SET nama_barang = '$nama_barang', harga = '$harga', stok = '$stok' WHERE id_barang = '$id_barang'";

    if (mysqli_query($conn, $query_update)) {
        header("Location: kelola_barang.php?success=Barang berhasil diperbarui");
        exit();
    } else {
        $error = "Gagal mengupdate barang: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="form-container">
        <div class="form-card">
            <h2>Edit Barang</h2>
            <?php if (isset($error)) {
                echo "<p class='error'>$error</p>";
            } ?>
            <form action="" method="POST">
                <label for="nama_barang">Nama Barang:</label>
                <input type="text" name="nama_barang" id="nama_barang" value="<?= $barang['nama_barang']; ?>" required>

                <label for="harga">Harga:</label>
                <input type="number" name="harga" id="harga" value="<?= $barang['harga']; ?>" required>

                <label for="stok">Stok:</label>
                <input type="number" name="stok" id="stok" value="<?= $barang['stok']; ?>" required>

                <button type="submit">Simpan Perubahan</button>
                <a href="kelola_barang.php" class="kembali-btn">Kembali</a>
            </form>
        </div>
    </div>
</body>

</html>