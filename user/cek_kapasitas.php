<?php
session_start()
include '../koneksi.php';

$tanggal = isset($_GET['tanggal']) ? trim($_GET['tanggal']) : '';

if (empty($tanggal)) {
    echo json_encode(['penuh' => false]); exit;
}

$sql = "SELECT COALESCE(SUM(j.jumlah), 0) as total
        FROM booking b
        JOIN jadwal j ON b.id_jadwal = j.id_jadwal
        WHERE j.tanggal = ? AND b.status IN ('0','1','2')";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $tanggal);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

$penuh = ($row['total'] >= 50);
echo json_encode(['penuh' => $penuh, 'total' => (int)$row['total']]);
?>