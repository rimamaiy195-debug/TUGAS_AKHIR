<?php
include 'koneksi.php';

if(isset($_POST['register'])){

    $nama     = mysqli_real_escape_string($koneksi,$_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi,$_POST['email']);
    $no_hp    = mysqli_real_escape_string($koneksi,$_POST['no_hp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $akses = 2; 

    $cek = mysqli_query($koneksi,"SELECT * FROM user WHERE email='$email'");

    if(mysqli_num_rows($cek) > 0){
        echo "<script>
            alert('Email sudah terdaftar!');
            window.location='register.php';
        </script>";
        exit;
    }

    $query = mysqli_query($koneksi,"
        INSERT INTO user (nama,email,no_hp,alamat,password,akses)
        VALUES ('$nama','$email','$no_hp','$alamat,'$password','$akses')
    ");

    if($query){
        echo "<script>
            alert('Register berhasil! Silakan login');
            window.location='index.php';
        </script>";
    }else{
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>