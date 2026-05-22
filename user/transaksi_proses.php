<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user    = (int)$_SESSION['id_user'];
$id_booking = isset($_POST['id_booking'])  ? (int)$_POST['id_booking']  : 0;
$total      = isset($_POST['total_bayar']) ? (int)$_POST['total_bayar'] : 0;
$metode     = isset($_POST['metode'])      ? trim($_POST['metode'])      : '';

if ($id_booking <= 0 || empty($metode)) {
    header("Location: cek_booking.php"); exit;
}

// Ambil detail metode & nama pengirim sesuai metode
$detail = '';
$nama_pengirim = '';

if ($metode === 'transfer') {
    $detail        = isset($_POST['detail_metode_transfer'])  ? trim($_POST['detail_metode_transfer'])  : '';
    $nama_pengirim = isset($_POST['nama_pengirim_transfer'])  ? trim($_POST['nama_pengirim_transfer'])  : '';
} elseif ($metode === 'ewallet') {
    $detail        = isset($_POST['detail_metode_ewallet'])   ? trim($_POST['detail_metode_ewallet'])   : '';
    $nama_pengirim = isset($_POST['nama_pengirim_ewallet'])   ? trim($_POST['nama_pengirim_ewallet'])   : '';
} elseif ($metode === 'kartu') {
    $detail        = isset($_POST['detail_metode_kartu'])     ? trim($_POST['detail_metode_kartu'])     : '';
    $nama_pengirim = isset($_POST['nama_pengirim_kartu'])     ? trim($_POST['nama_pengirim_kartu'])     : '';
}

// Pastikan booking milik user ini dan statusnya konfirmasi
$stmt = $koneksi->prepare("SELECT status FROM booking WHERE id_booking = ? AND id_user = ?");
$stmt->bind_param("ii", $id_booking, $id_user);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row || $row['status'] !== 'konfirmasi') {
    header("Location: cek_booking.php"); exit;
}

// Simpan transaksi
$status_trx = 'sukses';
$stmt = $koneksi->prepare("INSERT INTO transaksi (id_booking, metode, detail_metode, nama_pengirim, total_bayar, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssas", $id_booking, $metode, $detail, $nama_pengirim, $total, $status_trx);

// Perbaiki bind - total adalah int
$stmt->close();
$stmt = $koneksi->prepare("INSERT INTO transaksi (id_booking, metode, detail_metode, nama_pengirim, total_bayar, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $id_booking, $metode, $detail, $nama_pengirim, $total, $status_trx);

if ($stmt->execute()) {
    $stmt->close();
    // Update status booking jadi selesai
    $upd = $koneksi->prepare("UPDATE booking SET status = 'selesai' WHERE id_booking = ?");
    $upd->bind_param("i", $id_booking);
    $upd->execute();
    $upd->close();
    header("Location: transaksi.php?id=$id_booking&bayar=sukses");
} else {
    $stmt->close();
    header("Location: transaksi.php?id=$id_booking&error=Gagal+menyimpan+transaksi");
}
exit;
?>