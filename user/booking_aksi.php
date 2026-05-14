<?php
include '../koneksi.php';

// Hanya admin yang boleh akses
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php"); exit;
}

// Cek akses admin
$id_user = (int)$_SESSION['id_user'];
$stmt = $koneksi->prepare("SELECT akses FROM user WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$usr = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$usr || $usr['akses'] !== 'admin') {
    header("Location: index.php"); exit;
}

$aksi = isset($_POST['aksi']) ? trim($_POST['aksi']) : (isset($_GET['aksi']) ? trim($_GET['aksi']) : '');
$id   = isset($_POST['id'])   ? (int)$_POST['id']   : (isset($_GET['id'])   ? (int)$_GET['id']   : 0);

if ($id <= 0 || empty($aksi)) {
    header("Location: admin/booking.php?error=Parameter+tidak+valid"); exit;
}

switch ($aksi) {

    case 'hapus':
        // Hapus juga jadwal terkait
        $row = $koneksi->query("SELECT id_jadwal FROM booking WHERE id_booking = $id")->fetch_assoc();
        $stmt = $koneksi->prepare("DELETE FROM booking WHERE id_booking = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->close();
            if ($row) $koneksi->query("DELETE FROM jadwal WHERE id_jadwal = " . (int)$row['id_jadwal']);
            header("Location: admin/booking.php?sukses=Booking+berhasil+dihapus");
        } else {
            $stmt->close();
            header("Location: admin/booking.php?error=Gagal+menghapus");
        }
        exit;

    case 'konfirmasi':
        $stmt = $koneksi->prepare("UPDATE booking SET status = 'konfirmasi' WHERE id_booking = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute(); $stmt->close();
        header("Location: admin/booking.php?" . ($ok ? "sukses=Booking+dikonfirmasi" : "error=Gagal+konfirmasi"));
        exit;

    case 'selesai':
        $stmt = $koneksi->prepare("UPDATE booking SET status = 'selesai' WHERE id_booking = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute(); $stmt->close();
        header("Location: admin/booking.php?" . ($ok ? "sukses=Booking+diselesaikan" : "error=Gagal+update"));
        exit;

    case 'batal':
        $stmt = $koneksi->prepare("UPDATE booking SET status = 'batal' WHERE id_booking = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute(); $stmt->close();
        header("Location: admin/booking.php?" . ($ok ? "sukses=Booking+dibatalkan" : "error=Gagal+membatalkan"));
        exit;

    case 'update_status':
        $status_baru = isset($_POST['status']) ? trim($_POST['status']) : '';
        $allowed = ['pending', 'konfirmasi', 'selesai', 'batal'];
        if (!in_array($status_baru, $allowed)) {
            header("Location: admin/booking.php?error=Status+tidak+valid"); exit;
        }
        $stmt = $koneksi->prepare("UPDATE booking SET status = ? WHERE id_booking = ?");
        $stmt->bind_param("si", $status_baru, $id);
        $ok = $stmt->execute(); $stmt->close();
        header("Location: admin/booking.php?" . ($ok ? "sukses=Status+diupdate" : "error=Gagal+update+status"));
        exit;

    default:
        header("Location: admin/booking.php?error=Aksi+tidak+dikenal");
        exit;
}
?>