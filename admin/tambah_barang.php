<?php
session_start();
include 'D:/xampp/htdocs/kasir_LSP/database.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);

    $query = "INSERT INTO barang (nama_barang, harga, stok) VALUES ('$nama_barang', '$harga', '$stok')";

    if (mysqli_query($conn, $query)) {
        header("Location: kelola_barang.php?success=Barang berhasil ditambahkan");
        exit();
    } else {
        $error = "Gagal menambahkan barang: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="form-container">
        <div class="form-card">
            <h2>Tambah Barang</h2>
            <?php if (isset($error)) {
                echo "<p class='error'>$error</p>";
            } ?>
            <form action="" method="POST">
                <label for="nama_barang">Nama Barang:</label>
                <input type="text" name="nama_barang" id="nama_barang" required>

                <label for="harga">Harga:</label>
                <input type="number" name="harga" id="harga" required>

                <label for="stok">Stok:</label>
                <input type="number" name="stok" id="stok" required>

                <button type="submit">Tambah</button>
                <a href="kelola_barang.php" class="kembali-btn">Kembali</a>
            </form>
        </div>
    </div>
</body>

</html>