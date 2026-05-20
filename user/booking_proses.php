<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user    = (int)$_SESSION['id_user'];
$id_paket   = isset($_POST['id_paket'])   ? (int)$_POST['id_paket']   : 0;
$nama_paket = isset($_POST['nama_paket']) ? trim($_POST['nama_paket']) : '';
$tanggal    = isset($_POST['tanggal'])    ? trim($_POST['tanggal'])    : '';
$jam        = isset($_POST['jam'])        ? trim($_POST['jam'])        : '';
$jumlah     = isset($_POST['jumlah'])     ? (int)$_POST['jumlah']     : 1;
$harga      = isset($_POST['harga'])      ? (int)$_POST['harga']      : 0;
$no_telp    = isset($_POST['no_telp'])    ? trim($_POST['no_telp'])   : '';

if ($id_paket === 0 && !empty($nama_paket)) {
    $stmt = $koneksi prepare("SELECT * FROM paket WHERE nama_paket = ? LIMIT 1");
    $stmt bind_param("s", $nama_paket);
    $stmt execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt close();
    if ($row) {
        $id_paket  = $row['id_paket'];
        $harga     = $row['harga'];
        $kapasitas = $row['kapasitas'];
    }
} else {
    $stmt = $koneksi prepare("SELECT kapasitas, harga FROM paket WHERE id_paket = ?");
    $stmt bind_param("i", $id_paket);
    $stmt execute();
    $row = $stmt get_result() fetch_assoc();
    $stmt close();
    $kapasitas = $row ? $row['kapasitas'] : 99;
    if ($row) $harga = $row['harga'];
}

$errors = [];
if ($id_paket <= 0)  $errors[] = "Paket tidak valid.";
if (empty($tanggal)) $errors[] = "Tanggal wajib diisi.";
if (empty($jam))     $errors[] = "Jam wajib diisi.";
if ($jumlah < 1)     $errors[] = "Jumlah orang minimal 1.";
if (empty($no_telp)) $errors[] = "No. Telepon wajib diisi.";
if (!empty($tanggal) && strtotime($tanggal) < strtotime(date('Y-m-d')))
    $errors[] = "Tanggal tidak boleh di masa lalu.";
if (isset($kapasitas) && $jumlah > $kapasitas)
    $errors[] = "Jumlah orang melebihi kapasitas ({$kapasitas} orang).";

if (!empty($errors)) {
    $pesan = urlencode(implode(' | ', $errors));
    header("Location: booking.php?paket=" . urlencode($nama_paket) . "&harga=$harga&error=$pesan");
    exit;
}

$total_harga = $harga * $jumlah;

$stmt = $koneksi prepare("INSERT INTO jadwal (tanggal, jam, jumlah) VALUES (?, ?, ?)");
$stmt bind_param("ssi", $tanggal, $jam, $jumlah);
if (!$stmt execute()) {
    $pesan = urlencode("Gagal menyimpan jadwal, coba lagi.");
    header("Location: booking.php?paket=" . urlencode($nama_paket) . "&harga=$harga&error=$pesan");
    exit;
}
$id_jadwal = $stmt->insert_id;
$stmt close();

if (!empty($no_telp)) {
    $upd = $koneksi prepare("UPDATE user SET no_hp = ? WHERE id_user = ?");
    $upd bind_param("si", $no_telp, $id_user);
    $upd execute();
    $upd close();
}

$status = 'pending';
$stmt = $koneksi prepare("INSERT INTO booking (id_user, id_paket, id_jadwal, total_harga, status) VALUES (?, ?, ?, ?, ?)");
$stmt bind_param("iiiis", $id_user, $id_paket, $id_jadwal, $total_harga, $status);

if ($stmt execute()) {
    $id_booking = $stmt insert_id;
    $stmt close();
    header("Location: booking_tunggu.php?id=$id_booking");
    exit;
} else {
    $stmt close();
    $koneksi query("DELETE FROM jadwal WHERE id_jadwal = $id_jadwal");
    $pesan = urlencode("Gagal menyimpan booking, coba lagi.");
    header("Location: booking.php?paket=" . urlencode($nama_paket) . "&harga=$harga&error=$pesan");
    exit;
}
?>