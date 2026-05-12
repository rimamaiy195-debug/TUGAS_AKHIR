<?php
include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rafting Singorojo</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: url('../images/5.jpg') center/cover no-repeat fixed;
      color: white;
      min-height: 100vh;
    }

    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.55);
      z-index: 0;
    }

    body > * {
      position: relative;
      z-index: 1;
    }

    .main-content {
      padding: 50px 30px;
      max-width: 1100px;
      margin: auto;
    }

    .container {
      display: flex;
      gap: 50px;
      align-items: flex-start;
      flex-wrap: wrap;
    }

    .left {
      flex: 1;
      min-width: 280px;
    }

    .right {
      flex: 1;
      min-width: 280px;
      background: rgba(255,255,255,0.1);
      padding: 30px;
      border-radius: 15px;
      backdrop-filter: blur(6px);
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 25px;
    }

    .list {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .item {
      display: flex;
      gap: 15px;
    }

    .icon {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255,255,255,0.2);
    }

    .icon i {
      font-size: 18px;
      color: white;
    }

    .tel i { color: #4CAF50; }
    .ig i { color: #E1306C; }
    .web i { color: #4FC3F7; }
    .loc i { color: #FF5252; }

    .text .label {
      font-size: 0.7rem;
      text-transform: uppercase;
      color: rgba(255,255,255,0.6);
    }

    .text .value {
      font-size: 1rem;
      font-weight: 600;
      margin-top: 3px;
    }

    .text a {
      color: white;
      text-decoration: none;
    }

    .right h2 {
      margin-bottom: 10px;
    }

    .right p {
      margin-bottom: 20px;
      color: rgba(255,255,255,0.8);
    }

    .btn-wa {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 12px 20px;
      background: #25D366;
      color: white;
      font-weight: 600;
      border-radius: 8px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-wa:hover {
      background: #1ebe5d;
      transform: translateY(-2px);
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

    @media(max-width: 768px) {
      .container {
        flex-direction: column;
      }
    }

  </style>
</head>

<body>

  <?php include 'header.php'; ?>

  <div class="main-content">
    <h1>Hubungi Kami</h1>

    <div class="container">

      <div class="left">
        <div class="list">

          <div class="item">
            <div class="icon tel"><i class="fas fa-phone"></i></div>
            <div class="text">
              <div class="label">Telfon</div>
              <div class="value"><a href="tel:083102048865">0831-0204-8865</a></div>
            </div>
          </div>

          <div class="item">
            <div class="icon ig"><i class="fab fa-instagram"></i></div>
            <div class="text">
              <div class="label">Instagram</div>
              <div class="value">
                <a href="#">@wisata_raftingkendal</a><br>
                <a href="#">@bodri_rafting</a>
              </div>
            </div>
          </div>

          <div class="item">
            <div class="icon web"><i class="fas fa-globe"></i></div>
            <div class="text">
              <div class="label">Website</div>
              <div class="value">
                <a href="#">www.raftingsingorojo.com</a>
              </div>
            </div>
          </div>

          <div class="item">
            <div class="icon loc"><i class="fas fa-map-marker-alt"></i></div>
            <div class="text">
              <div class="label">Lokasi</div>
              <div class="value">BODRI Rafting Singorojo</div>
            </div>
          </div>

        </div>
      </div>

      <div class="right">
        <h2>Ayo Rafting!</h2>
        <p>Rasakan serunya arung jeram di Sungai Bodri bersama tim profesional kami.</p>

        <a href="https://wa.me/6283102048865" target="_blank" class="btn-wa">
          <i class="fab fa-whatsapp"></i> Chat via WhatsApp
        </a>
      </div>

    </div>
  </div>

  <a href="https://wa.me/6283102048865" target="_blank" class="wa-float">
    <i class="fab fa-whatsapp"></i>
  </a>

</body>
</html>