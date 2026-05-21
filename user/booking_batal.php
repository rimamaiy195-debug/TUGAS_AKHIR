<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user    = (int)$_SESSION['id_user'];
$id_booking = isset($_POST['id_booking'])  ? (int)$_POST['id_booking']        : 0;
$alasan = '';

if ($id_booking <= 0) {
if ($id_booking <= 0 || empty($alasan)) {
    header("Location: booking_tunggu.php?id=$id_booking&error=Alasan+wajib+diisi");
    exit;
}

// Pastikan booking milik user ini dan masih pending
$stmt = $koneksi->prepare("SELECT status FROM booking WHERE id_booking = ? AND id_user = ?");
$stmt->bind_param("ii", $id_booking, $id_user);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    header("Location: ../index.php"); exit;
}

if ($row['status'] !== 'pending') {
    header("Location: booking_tunggu.php?id=$id_booking&error=Booking+tidak+bisa+dibatalkan");
    exit;
}

// Update status jadi batal + simpan alasan
$stmt = $koneksi->prepare("UPDATE booking SET status = 'batal', alasan_batal = ? WHERE id_booking = ? AND id_user = ?");
$stmt->bind_param("sii", $alasan, $id_booking, $id_user);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: booking_tunggu.php?id=$id_booking");
} else {
    $stmt->close();
    header("Location: booking_tunggu.php?id=$id_booking&error=Gagal+membatalkan+booking");
}
exit;
?>