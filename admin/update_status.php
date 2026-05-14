<?php
include '../koneksi.php';

$id     = (int)$_POST['id_booking'];
$status = (int)$_POST['status'];

if (!in_array($status, [0, 1, 2, 3])) {
    http_response_code(400);
    echo 'Status tidak valid';
    exit;
}

$query = mysqli_query($koneksi, "UPDATE booking SET status=$status WHERE id_booking=$id");

if ($query) {
    echo 'ok';
} else {
    http_response_code(500);
    echo 'Gagal: ' . mysqli_error($koneksi);
}