<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id']; 

    $query = mysqli_query($koneksi, "SELECT foto FROM galeri WHERE id = $id");
    $row   = mysqli_fetch_assoc($query);

    if ($row) {
        $filePath = '../images/' . $row['foto'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        mysqli_query($koneksi, "DELETE FROM galeri WHERE id = $id");
    }
}

header('Location: galeri.php');
exit;
?>