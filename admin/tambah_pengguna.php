<?php
session_start();
include 'D:/xampp/htdocs/kasir_LSP/database.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = mysqli_escape_string($conn, $_POST['nama_pengguna']);
    $username = mysqli_escape_string($conn, $_POST['username']);
    $password = hash('sha256', $_POST['password']);
    $role = mysqli_escape_string($conn, $_POST['role']);

    $query = "INSERT INTO pengguna (nama_pengguna, username, password, role) VALUES ('$nama_pengguna', '$username', '$password', '$role')";

    if (mysqli_query($conn, $query)) {
        header('Location: kelola_penggunaphp.?success=Pengguna berhasil ditambahkan!');
        exit();
    } else {
        $error = "Gagal menambahkan pengguna: " . mysqli_error($error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="form-container">
        <div class="form-card">
            <h2>Tambah Pengguna</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form action="" method="POST">
                <label>Nama Pengguna:</label>
                <input type="text" name="nama_pengguna" id="nama_barang" required>

                <label>Username:</label>
                <input type="text" name="username" id="username" required>

                <label>Password:</label>
                <input type="password" name="password" id="password" required>

                <label>Role:</label>
                <select name="role" required>
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