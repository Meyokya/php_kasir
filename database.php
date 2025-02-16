<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "kasir_db";
$port = "3307";

$conn = mysqli_connect($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} 
?>