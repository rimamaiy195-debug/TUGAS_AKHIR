<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rafting Singorojo</title>
    
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f0f0;
            padding: 0;
        }

        .container {
            background-color: white;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #ffffff;
            width: 100%;
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
        }

        .nav-menu li a.active {
            color: #d4a373; 
        }

        .search-box {
            display: flex;
            align-items: center;
            background-color: #e9e9e9;
            border-radius: 25px;
            padding: 8px 15px;
        }

        .search-box i {
            color: #888;
            margin-right: 8px;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 16px;
            color: #333;
        }

        .search-box input::placeholder {
            color: #888;
        }

        
    </style>

</head>
<body>

    <?php
        session_start();

        if($_SESSION['akses'] != "1"){
           header("location:../login.php");
           exit();
        }

    ?>

<div class="container">

    <nav class="navbar">
        <div class="logo">
            <h2>Rafting<br>Singorojo</h2>
        </div>
        <ul class="nav-menu">
            <li><a href="home.php" class="active">Home</a></li>
            <li><a href="profile.php">Pofile</a></li> 
            <li><a href="galeri.php">Galeri</a></li>
            <li><a href="paket.php">Paket</a></li>
            <li><a href="booking.php">Booking</a></li>
            <li><a href="contact.php">Contact</a></li>
            
            <li class="search-box">
                <i class="fa fa-search"></i>
                <input type="text" placeholder="Cari">
            </li>
        </ul>
    </nav>

</div>

</body>
</html>