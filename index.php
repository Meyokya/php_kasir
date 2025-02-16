<?php
session_start();
include 'D:/xampp/htdocs/kasir_LSP/database.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = hash('sha256', $_POST['password']);

    $query = "SELECT * FROM pengguna WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['id_pengguna'] = $user['id_pengguna'];
        $_SESSION['nama_pengguna'] = $user['nama_pengguna'];
        $_SESSION['role'] = $user['role']; // Pastikan ada kolom role di database

        if ($user['role'] == 'Admin') {
            header("Location: ../kasir_LSP/admin/dashboard.php");
        } else {
            header("Location: dashboard_kasir.php");
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../kasir_LSP/style.css">
</head>

<body>
    <div class="form-container">
        <div class="form-card">
            <h2>Login</h2>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <form action="index.php" method="POST">
                <label>Username:</label>
                <input type="text" name="username" required>
                <label>Password:</label>
                <input type="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>