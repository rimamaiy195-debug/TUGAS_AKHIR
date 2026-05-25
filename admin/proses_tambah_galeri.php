<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kategori = $_POST['kategori'];
    $foto     = $_FILES['foto'];

    if (empty($foto['name'])) {
        header('Location: tambah_galeri.php?status=gagal');
        exit;
    }

    $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp', 'JPG', 'JPEG', 'PNG'];
    $ekstensi = pathinfo($foto['name'], PATHINFO_EXTENSION);
    if (!in_array($ekstensi, $ekstensiDiizinkan)) {
        header('Location: tambah_galeri.php?status=gagal');
        exit;
    }

    if ($foto['size'] > 2 * 1024 * 1024) {
        header('Location: tambah_galeri.php?status=gagal');
        exit;
    }

    $namaFile = time() . '_' . uniqid() . '.' . strtolower($ekstensi);

    $folderTujuan = '../images/';

    if (!is_dir($folderTujuan)) {
        mkdir($folderTujuan, 0755, true);
    }

    if (move_uploaded_file($foto['tmp_name'], $folderTujuan . $namaFile)) {

        $sql = "INSERT INTO galeri (kategori, foto) VALUES ('$kategori', '$namaFile')";
        
        if (mysqli_query($koneksi, $sql)) {
            header('Location: tambah_galeri.php?status=sukses');
        } else {
            header('Location: tambah_galeri.php?status=gagal');
        }

    } else {
        header('Location: tambah_galeri.php?status=gagal');
    }

} else {
    header('Location: tambah_galeri.php');
}

exit;
?>