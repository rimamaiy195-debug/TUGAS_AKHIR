<?php
session_start();
include 'koneksi.php';

$email = $_POST['email'];
$password = md5($_POST['password']);

$data = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email' AND password='$password'");

$cek = mysqli_num_rows($data);

if($cek > 0){
    $d = mysqli_fetch_assoc($data);
    $_SESSION['email'] = $email;
    $_SESSION['akses'] = "login";
    $_SESSION['id_user'] = $d['id_user'];
    $_SESSION['nama'] = $d['nama'];
    $_SESSION['akses'] = $d['akses'];

    if($d['akses'] == "1"){
        header("location:admin/index.php");
        exit();
    } 
    else if($d['akses'] == "2"){
        header("location:user/index.php");
        exit();
    }

}
?>
