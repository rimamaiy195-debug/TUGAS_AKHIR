<?php
session_start();

if($_SESSION['akses'] != "1"){
    header("location:../login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rafting Singorojo</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background-color: #f0f0f0;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    background-color: #ffffff;
}

.logo h2 {
    color: #333;
    line-height: 1.2;
    font-weight: normal;
}

.nav-menu {
    display: flex;
    list-style: none;
    align-items: center;
    gap: 25px;
}

.nav-menu li a {
    text-decoration: none;
    color: #333;
    font-size: 18px;
    padding-bottom: 5px;
}

.nav-menu li a.active {
    color: #d4a373;
    border-bottom: 2px solid #d4a373;
}

.search-box {
    display: flex;
    align-items: center;
    background-color: #e9e9e9;
    border-radius: 25px;
    padding: 8px 15px;
}

.search-box input {
    border: none;
    background: transparent;
    outline: none;
}
</style>
</head>

<body>

<nav class="navbar">
    <div class="logo">
        <h2>Rafting<br>Singorojo</h2>
    </div>

    <ul class="nav-menu">
        <li><a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a></li>
        <li><a href="profile.php" class="<?= ($current_page == 'profile.php') ? 'active' : '' ?>">Profile</a></li>
        <li><a href="galeri.php" class="<?= ($current_page == 'galeri.php') ? 'active' : '' ?>">Galeri</a></li>
        <li><a href="paket.php" class="<?= ($current_page == 'paket.php') ? 'active' : '' ?>">Paket</a></li>
        <li><a href="booking.php" class="<?= ($current_page == 'booking.php') ? 'active' : '' ?>">Booking</a></li>
        <li><a href="contact.php" class="<?= ($current_page == 'contact.php') ? 'active' : '' ?>">Contact</a></li>

        <li class="search-box">
            <input type="text" placeholder="Cari">
        </li>
    </ul>
</nav>