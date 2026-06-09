<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user    = (int)$_SESSION['id_user'];
$id_booking = isset($_POST['id_booking']) ? (int)$_POST['id_booking'] : 0;

if ($id_booking <= 0) {
    header("Location: cek_booking.php"); exit;
}

// Pastikan booking milik user ini dan masih status 0 (menunggu)
$stmt = $koneksi->prepare("SELECT status FROM booking WHERE id_booking = ? AND id_user = ?");
$stmt->bind_param("ii", $id_booking, $id_user);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    header("Location: cek_booking.php"); exit;
}

if ($row['status'] != '0') {
    header("Location: booking_tunggu.php?id=$id_booking");
    exit;
}

// Update status jadi 3 (batal)
$stmt = $koneksi->prepare("UPDATE booking SET status = '3' WHERE id_booking = ? AND id_user = ?");
$stmt->bind_param("ii", $id_booking, $id_user);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: booking_tunggu.php?id=$id_booking");
} else {
    $stmt->close();
    header("Location: booking_tunggu.php?id=$id_booking&error=Gagal+membatalkan+booking");
}
exit;
?>