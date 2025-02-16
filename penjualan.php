<?php
session_start();
include '../kasir_LSP/database.php';

// Pastikan user login sebagai kasir
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'Kasir') {
    header("Location: index.php");
    exit();
}

// Tambahkan barang ke keranjang
if (isset($_POST['tambah_keranjang'])) {
    $id_barang = mysqli_real_escape_string($conn, $_POST['id_barang']);
    $jumlah = (int) $_POST['jumlah'];

    if ($jumlah > 0) {
        $query = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang = '$id_barang'");
        $barang = mysqli_fetch_assoc($query);

        if ($barang && $barang['stok'] >= $jumlah) { // Cek stok cukup
            $nama_barang = $barang['nama_barang'];
            $harga = $barang['harga'];
            $subtotal = $harga * $jumlah;

            $_SESSION['keranjang'][$id_barang] = [
                'id_barang' => $id_barang,
                'nama_barang' => $nama_barang,
                'harga' => $harga,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal
            ];
        } else {
            echo "<script>alert('Stok tidak mencukupi!');</script>";
        }
    }
}

// Hapus barang dari keranjang
if (isset($_POST['hapus_keranjang'])) {
    $id_barang = $_POST['id_barang'];
    unset($_SESSION['keranjang'][$id_barang]);
    header("Location: penjualan.php");
    exit();
}

// Checkout & simpan ke database
if (isset($_POST['checkout'])) {
    $id_pengguna = $_SESSION['id_pengguna'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $total_harga = !empty($_SESSION['keranjang']) ? array_sum(array_column($_SESSION['keranjang'], 'subtotal')) : 0;

    // Simpan transaksi ke tabel penjualan
    mysqli_query($conn, "INSERT INTO penjualan (id_pengguna, total_harga, metode_pembayaran) 
                         VALUES ('$id_pengguna', '$total_harga', '$metode_pembayaran')");
    $id_penjualan = mysqli_insert_id($conn);

    // Simpan detail barang & kurangi stok
    foreach ($_SESSION['keranjang'] as $id_barang => $item) {
        $jumlah = $item['jumlah'];
        $subtotal = $item['subtotal'];

        mysqli_query($conn, "INSERT INTO detail_penjualan (id_penjualan, id_barang, jumlah, subtotal) 
                             VALUES ('$id_penjualan', '$id_barang', '$jumlah', '$subtotal')");

        mysqli_query($conn, "UPDATE barang SET stok = stok - $jumlah WHERE id_barang = '$id_barang'");
    }

    // Cek apakah laporan hari ini sudah ada
    $tanggal_hari_ini = date('Y-m-d');
    $cek_laporan = mysqli_query($conn, "SELECT * FROM laporan_penjualan WHERE tanggal_laporan = '$tanggal_hari_ini'");
    $laporan = mysqli_fetch_assoc($cek_laporan);

    if ($laporan) {
        mysqli_query($conn, "UPDATE laporan_penjualan 
                             SET total_penjualan = total_penjualan + $total_harga 
                             WHERE tanggal_laporan = '$tanggal_hari_ini'");
    } else {
        mysqli_query($conn, "INSERT INTO laporan_penjualan (tanggal_laporan, total_penjualan) 
                             VALUES ('$tanggal_hari_ini', '$total_harga')");
    }

    // Kosongkan keranjang setelah checkout
    unset($_SESSION['keranjang']);

    // Redirect ke halaman riwayat penjualan
    header("Location: riwayat_penjualan.php");
    exit();
}

// Ambil daftar barang
$query_barang = mysqli_query($conn, "SELECT * FROM barang");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan</title>
    <link rel="stylesheet" href="../kasir_LSP/style1.css">
</head>
<body>

<div class="container">
    <h2>Transaksi Penjualan</h2>

    <!-- Form Tambah Barang ke Keranjang -->
    <div class="card-form">
        <form method="post">
            <label for="id_barang">Pilih Barang:</label>
            <select name="id_barang" required>
                <option value="">-- Pilih Barang --</option>
                <?php while ($barang = mysqli_fetch_assoc($query_barang)) : ?>
                    <option value="<?= $barang['id_barang']; ?>">
                        <?= htmlspecialchars($barang['nama_barang']); ?> - Rp<?= number_format($barang['harga'], 0, ',', '.'); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="jumlah">Jumlah:</label>
            <input type="number" name="jumlah" required min="1">

            <button type="submit" name="tambah_keranjang">Tambah ke Keranjang</button>
        </form>
    </div>

    <!-- Tampilkan Isi Keranjang -->
    <h3>Keranjang</h3>
    <table class="keranjang">
        <tr>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
        <?php if (!empty($_SESSION['keranjang'])) : ?>
            <?php foreach ($_SESSION['keranjang'] as $id_barang => $item) : ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama_barang']); ?></td>
                    <td>Rp<?= number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td><?= $item['jumlah']; ?></td>
                    <td>Rp<?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id_barang" value="<?= $id_barang; ?>">
                            <button type="submit" name="hapus_keranjang">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr><td colspan="5">Keranjang masih kosong</td></tr>
        <?php endif; ?>
    </table>

    <!-- Total Harga -->
    <p class="total-harga">Total Harga: Rp<?= !empty($_SESSION['keranjang']) ? number_format(array_sum(array_column($_SESSION['keranjang'], 'subtotal')), 0, ',', '.') : '0'; ?></p>

    <!-- Checkout -->
    <form method="post">
        <label for="metode_pembayaran">Metode Pembayaran:</label>
        <select name="metode_pembayaran" required>
            <option value="Tunai">Tunai</option>
            <option value="Kartu">Kartu</option>
            <option value="QRIS">QRIS</option>
        </select>
        <button type="submit" class="checkout" name="checkout" <?= empty($_SESSION['keranjang']) ? 'disabled' : ''; ?>>Checkout</button>
    </form>
</div>

</body>
</html>
