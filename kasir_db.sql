CREATE DATABASE IF NOT EXISTS kasir_db;
USE kasir_db;

CREATE TABLE pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    nama_pengguna VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Kasir') NOT NULL
);

CREATE TABLE barang (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0
);

CREATE TABLE penjualan (
    id_penjualan INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    tanggal_penjualan DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_harga DECIMAL(10,2) NOT NULL,
    metode_pembayaran ENUM('Tunai', 'Kartu', 'QRIS') NOT NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE
);

CREATE TABLE detail_penjualan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_penjualan INT NOT NULL,
    id_barang INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_penjualan) REFERENCES penjualan(id_penjualan) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE
);

CREATE TABLE laporan_penjualan (
    id_laporan INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_laporan DATE DEFAULT CURRENT_DATE,
    total_penjualan DECIMAL(10,2) NOT NULL
);

INSERT INTO pengguna (nama_pengguna, username, password, role) VALUES
('Admin', 'admin', SHA2('admin123', 256), 'Admin'),
('Kasir', 'kasir1', SHA2('kasir123', 256), 'Kasir');

INSERT INTO barang (nama_barang, harga, stok) VALUES
('Pensil', 2000, 50),
('Buku Tulis', 5000, 30),
('Pulpen', 3000, 40),
('Penghapus', 1000, 25);

INSERT INTO penjualan (id_pengguna, total_harga, metode_pembayaran) VALUES
(2, 10000, 'Tunai'); -- Kasir 1 melakukan transaksi

INSERT INTO detail_penjualan (id_penjualan, id_barang, jumlah, subtotal) VALUES
(1, 1, 2, 4000), -- 2 Pensil x 2000
(1, 2, 1, 5000), -- 1 Buku Tulis x 5000
(1, 4, 1, 1000); -- 1 Penghapus x 1000

INSERT INTO laporan_penjualan (tanggal_laporan, total_penjualan) VALUES
('2025-02-14', 10000);