<?php
include '../koneksi.php';
include 'header.php'; 
 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Rafting Singorojo</title>

	<style>
		.hero {
            background-image: url('../images/6.jpg'); 
            background-size: cover;
            background-position: center;
            height: 85vh;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 50px;
            position: relative;
        }
        .hero::before {
             content: "";
             position: absolute;
             top: 0;
             left: 0;
             width: 100%;
             height: 100%;
             background-color: rgba(0, 0, 0, 0.3); 
             z-index: 1;
         }

        .hero-text {
             color: #ffffff;
             font-size: 18px; 
             font-weight: bold; 
             text-shadow: 2px 2px 8px rgba(0,0,0,1); 
             line-height: 1.6;
             position: relative;
             z-index: 2;
             margin-left: 20px;
             margin-top: -80px; 
        }

        .btn {
            margin-top: 25px;
            padding: 12px 30px;
            background-color: rgba(255,255,255,0.3);
            color: white;
            border: 1px solid white;
            border-radius: 25px;
            font-size: 18px;
            cursor: pointer;
            text-shadow: none;
        }

        .wa-float {
          position: fixed;
          bottom: 20px;
          right: 20px;
          background: #25D366;
          color: white;
          width: 55px;
          height: 55px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 24px;
          text-decoration: none;
          box-shadow: 0 5px 15px rgba(0,0,0,0.3);
          z-index: 999;
          transition: 0.3s;
        }

        .wa-float:hover {
          transform: scale(1.1);
          background: #1ebe5d;
        }
	</style>
</head>
<body>

    <section class="hero">
        <div class="hero-text">
            <p>Kawasan Wisata Bodri Rafting. Desa Singorojo, <br>Kecamatan Singorojo (51382),<br>
            Kendal, Jawa Tengah, Indonesia. Contact:<br>
            0831-0204-8865.</p>

            <a href="profile.php">
            <button class="btn">Baca lebih tentang ini</button>

        </div>
    </section>

</div>

    <a href="https://wa.me/6283102048865" target="_blank" class="wa-float">
    <i class="fab fa-whatsapp"></i>
  </a>

</body>
</html>