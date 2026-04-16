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

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background-color: rgba(255, 255, 255, 0.8);
            position: absolute;
            width: 100%;
            top: 0;
            z-index: 999;
        }

        .logo h2 {
            color: #333;
            line-height: 1.2;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
        }

        .hero {
            background-image: url('images/6.jpg'); 
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: flex-end;
            padding: 50px;
        }

        .hero-text {
            color: white;
            font-size: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .btn {
            margin-top: 20px;
            padding: 10px 25px;
            background-color: rgba(255,255,255,0.3);
            color: white;
            border: 1px solid white;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: white;
            color: #333;
        }
    </style>

</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <h2>Rafting<br>Singorojo</h2>
        </div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Galeri</a></li>
            <li><a href="#">Paket</a></li>
            <li><a href="#">Booking</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>

    <!-- BANNER / ISI KONTEN -->
    <section class="hero">
        <div class="hero-text">
            <p>Kawasan Wisata Bodri Rafting. Desa Singorojo, Kecamatan Singorojo (51382),<br>
            Kendal, Jawa Tengah, Indonesia. Contact: 0831-0204-8865.</p>
            <button class="btn">Baca lebih tentang ini</button>
        </div>
    </section>

</body>
</html>