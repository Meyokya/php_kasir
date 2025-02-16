<?php
session_start();
include 'D:/xampp/htdocs/kasir_LSP/database.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit();
}

// Pastikan ada ID barang yang dikirim
if (!isset($_GET['id'])) {
    header("Location: kelola_pengguna.php?error=ID pengguna tidak ditemukan");
    exit();
}

$id_pengguna = $_GET['id'];
$query = "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'";
$result = mysqli_query($conn, $query);
$pengguna = mysqli_fetch_assoc($result);

if (!$pengguna) {
    header("Location: kelola_pengguna.php?error=Pengguna tidak ditemukan");
    exit();
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = mysqli_escape_string($conn, $_POST['nama_pengguna']);
    $username = mysqli_escape_string($conn, $_POST['username']);
    $password = hash('sha256', $_POST['password']);
    $role = mysqli_escape_string($conn, $_POST['role']);

    $query = "UPDATE pengguna SET nama_pengguna = '$nama_pengguna', username = '$username', password = '$password' WHERE id_barang = '$id_barang'";
    
    if (mysqli_query($conn, $query)) {
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
            <h2>Edit Pengguna</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form action="" method="POST">
                <label>Nama Pengguna:</label>
                <input type="text" name="nama_pengguna" id="nama_pengguna" value="<?= $pengguna['nama_pengguna']?>" required>

                <label>Username:</label>
                <input type="text" name="username" id="username" value="<?= $pengguna['username']?>" required>

                <label>Password:</label>
                <input type="password" name="password" id="password" value="<?= $pengguna['password']?>" required>

                <label>Role:</label>
                <select name="role" value="<?= $pengguna['role']?>"required>
                    <option value="Admin">Admin</option>
                    <option value="Kasir">Kasir</option>
                </select>

                <button type="submit">Tambah</button>
                <a href="kelola_pengguna.php" class="kembali-btn">Kembali</a>
            </form>
        </div>
    </div>
</body>
</html>
