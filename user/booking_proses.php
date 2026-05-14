<?php
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php"); exit;
}

$id_user  = (int)$_SESSION['id_user'];
$id_paket = isset($_POST['id_paket']) ? (int)$_POST['id_paket'] : 0;
$tanggal  = isset($_POST['tanggal'])  ? trim($_POST['tanggal']) : '';
$jam      = isset($_POST['jam'])      ? trim($_POST['jam'])     : '';
$jumlah   = isset($_POST['jumlah'])   ? (int)$_POST['jumlah']  : 1;
$harga    = isset($_POST['harga'])    ? (int)$_POST['harga']   : 0;

// Validasi
$errors = [];
if ($id_paket <= 0)  $errors[] = "Paket tidak valid.";
if (empty($tanggal)) $errors[] = "Tanggal wajib diisi.";
if (empty($jam))     $errors[] = "Jam wajib diisi.";
if ($jumlah < 1)     $errors[] = "Jumlah orang minimal 1.";
if (!empty($tanggal) && strtotime($tanggal) < strtotime(date('Y-m-d')))
    $errors[] = "Tanggal tidak boleh di masa lalu.";

if (!empty($errors)) {
    $pesan = urlencode(implode(' | ', $errors));
    header("Location: booking.php?id_paket=$id_paket&error=$pesan"); exit;
}

// Cek kapasitas paket
$stmt = $koneksi->prepare("SELECT kapasitas FROM paket WHERE id_paket = ?");
$stmt->bind_param("i", $id_paket);
$stmt->execute();
$paket = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$paket) { header("Location: paket.php"); exit; }

if ($jumlah > $paket['kapasitas']) {
    $pesan = urlencode("Jumlah orang melebihi kapasitas (" . $paket['kapasitas'] . " orang).");
    header("Location: booking.php?id_paket=$id_paket&error=$pesan"); exit;
}

$total_harga = $harga * $jumlah;

// Simpan ke tabel jadwal
$stmt = $koneksi->prepare("INSERT INTO jadwal (tanggal, jam, jumlah) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $tanggal, $jam, $jumlah);
if (!$stmt->execute()) {
    $pesan = urlencode("Gagal menyimpan jadwal.");
    header("Location: booking.php?id_paket=$id_paket&error=$pesan"); exit;
}
$id_jadwal = $stmt->insert_id;
$stmt->close();

// Simpan ke tabel booking, status awal = pending
$status = 'pending';
$stmt = $koneksi->prepare("INSERT INTO booking (id_user, id_paket, id_jadwal, total_harga, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiis", $id_user, $id_paket, $id_jadwal, $total_harga, $status);

if ($stmt->execute()) {
    $id_booking = $stmt->insert_id;
    $stmt->close();
    // Redirect ke halaman tunggu konfirmasi
    header("Location: booking_tunggu.php?id=$id_booking"); exit;
} else {
    $stmt->close();
    $koneksi->query("DELETE FROM jadwal WHERE id_jadwal = $id_jadwal");
    $pesan = urlencode("Gagal menyimpan booking, silakan coba lagi.");
    header("Location: booking.php?id_paket=$id_paket&error=$pesan"); exit;
}
?>