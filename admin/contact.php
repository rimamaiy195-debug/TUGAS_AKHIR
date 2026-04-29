<?php
include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact – Rafting Singorojo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Poppins', sans-serif;
      background-image: url('../images/5.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
      color: #fff;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.55);
      z-index: 0;
    }

    body > * { position: relative; z-index: 1; }

    /* PADDING PINDAH KE SINI */
    .main-content {
      padding: 40px 20px;
    }

    h1 {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 32px;
      color: #fff;
    }

    .list {
      display: flex;
      flex-direction: column;
      gap: 20px;
      max-width: 420px;
    }

    .item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
    }

    .icon {
      width: 44px;
      height: 44px;
      background: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      backdrop-filter: blur(4px);
    }

    .icon svg {
      width: 20px;
      height: 20px;
      fill: #000000;
    }

    .text .label {
      font-size: 0.7rem;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.6);
      margin-bottom: 2px;
    }

    .text .value {
      font-size: 0.95rem;
      font-weight: 600;
      color: #fff;
      line-height: 1.5;
    }

    .text a {
      color: #fff;
      text-decoration: none;
    }
  </style>
</head>

<body>

  <!-- HEADER DIPINDAH KE SINI -->
  <?php include 'header.php'; ?>

  <div class="main-content">
    <h1>Hubungi Kami</h1>

    <div class="list">

      <div class="item">
        <div class="icon">
          <svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C11 21 3 13 3 5c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
        </div>
        <div class="text">
          <div class="label">Telfon</div>
          <div class="value"><a href="tel:083102048865">0831-0204-8865</a></div>
        </div>
      </div>

      <div class="item">
        <div class="icon">
          <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/></svg>
        </div>
        <div class="text">
          <div class="label">Instagram</div>
          <div class="value">
            <a href="https://instagram.com/wisata_raftingkendal" target="_blank">@wisata_raftingkendal</a><br>
            <a href="https://instagram.com/bodri_rafting" target="_blank">@bodri_rafting</a>
          </div>
        </div>
      </div>

      <div class="item">
        <div class="icon">
          <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        </div>
        <div class="text">
          <div class="label">Website</div>
          <div class="value"><a href="https://www.raftingsingorojo.com" target="_blank">www.raftingsingorojo.com</a></div>
        </div>
      </div>

      <div class="item">
        <div class="icon">
          <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
        </div>
        <div class="text">
          <div class="label">Lokasi</div>
          <div class="value">BODRI Rafting Singorojo</div>
        </div>
      </div>

    </div>
  </div>

</body>
</html>