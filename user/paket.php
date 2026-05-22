<?php
  include '../koneksi.php';
  include 'header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Paket - Rafting Singorojo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background-color: #f0eada;
      font-family: 'Poppins', Arial, sans-serif;
    }

    .wrapper {
      max-width: 1500px;
      margin: 28px auto;
      padding: 0 20px;
    }

    .container-content {
      display: flex;
      gap: 22px;
      align-items: flex-start;
    }

    .left-section {
      flex: 5;
      background: white;
      padding: 24px 22px;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.09);
    }

    .section-title {
      background: #c87941;
      color: white;
      padding: 7px 18px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 13px;
      margin-bottom: 18px;
      display: inline-block;
      letter-spacing: 0.3px;
    }

    .grid-kegiatan {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      margin-bottom: 22px;
    }

    .item {
      display: flex;
      flex-direction: column;
    }

    .item-label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      font-weight: 600;
      color: #333;
      margin-bottom: 6px;
    }

    .item-label input[type="checkbox"] {
      accent-color: #2daae1;
      width: 15px;
      height: 15px;
      cursor: pointer;
    }

    .item img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 10px;
      transition: transform 0.3s;
      display: block;
    }

    .item img:hover {
      transform: scale(1.03);
    }

    .deskripsi {
      font-size: 12px;
      color: #666;
      line-height: 1.4;
      margin-top: 5px;
      text-align: justify;
    }

    .fasilitas-box {
      background: #fafafa;
      border-radius: 12px;
      padding: 14px 18px;
      border: 1px solid #e8e8e8;
    }

    .fasilitas-box ul {
      list-style: none;
      padding: 0;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 6px 20px;
      margin-top: 4px;
    }

    .fasilitas-box li {
      font-size: 13px;
      color: #444;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .fasilitas-box li::before {
      content: '➢';
      color: #c87941;
      font-size: 12px;
      flex-shrink: 0;
    }

    .right-section {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .paket-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: transform 0.2s, border 0.2s;
      border: 2.5px solid transparent;
    }

    .paket-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(45,170,225,0.2);
    }

    .paket-card.active {
      border-color: #2daae1;
      transform: scale(1.02);
    }

    .paket-title {
      background: #2daae1;
      color: white;
      padding: 10px 12px;
      text-align: center;
      font-weight: 700;
      font-size: 15px;
      letter-spacing: 0.5px;
      line-height: 1.3;
    }

    .paket-title small {
      display: block;
      font-size: 11px;
      font-weight: 400;
      opacity: 0.92;
      margin-top: 2px;
      font-style: italic;
    }

    .paket-harga {
      padding: 14px 12px;
      text-align: center;
      font-size: 26px;
      font-weight: 700;
      color: #222;
      background: #f9f9f9;
      line-height: 1.2;
    }

    .paket-harga span {
      display: block;
      font-size: 14px;
      color: #555;
      font-weight: 500;
    }

    .btn-next {
      background: #2daae1;
      color: white;
      border: none;
      padding: 10px 24px;
      border-radius: 25px;
      cursor: pointer;
      margin-top: 4px;
      margin-left: auto;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: background 0.2s, transform 0.2s;
      font-family: 'Poppins', sans-serif;
    }

    .btn-next:hover {
      background: #1b8fc2;
      transform: translateX(2px);
    }

    @media (max-width: 768px) {
      .container-content { flex-direction: column; }
      .grid-kegiatan { grid-template-columns: 1fr; }
      .fasilitas-box ul { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <div class="wrapper">
    <div class="container-content">

      <div class="left-section">

        <div class="section-title">DAFTAR KEGIATAN OUTDOOR LAIN</div>

        <div class="grid-kegiatan">

          <div class="item">
            <label class="item-label">
              <input type="checkbox" checked> Rafting Bodri
            </label>
            <img src="../images/1.jpg" alt="Rafting Bodri">
            <p class="deskripsi">Rafting atau arung jeram adalah aktivitas petualangan mengarungi sungai berarus deras menggunakan perahu karet, kayak, atau kano. Olahraga tim ini menuntut kerja sama untuk melewati rintangan seperti jeram, batu, dan arus, sering kali sebagai sarana rekreasi yang memacu adrenalin.</p>
          </div>

          <div class="item">
            <label class="item-label">
              <input type="checkbox" checked> Canyoning
            </label>
            <img src="../images/canyoning.jpg" alt="Canyoning">
            <p class="deskripsi">Canyoning (atau canyoneering) adalah olahraga petualangan ekstrem menyusuri ngarai, lembah sungai, atau air terjun dengan teknik gabungan seperti rappelling (turun tali), melompat, meluncur, berenang, dan hiking. Aktivitas ini menjelajahi jalur air alami yang terjal dari hulu ke hilir.</p>
          </div>

          <div class="item">
            <label class="item-label">
              <input type="checkbox" checked> Outbound
            </label>
            <img src="../images/Outbound.jpg" alt="Outbound">
            <p class="deskripsi">Outbound adalah metode pembelajaran dan pelatihan di luar ruangan (outdoor) yang menggabungkan permainan edukatif, simulasi fisik, dan mental untuk pengembangan diri atau tim. Tujuannya meliputi peningkatan kerjasama tim (team building), kepemimpinan, komunikasi, kreativitas, serta pemecahan masalah dengan suasana menyenangkan.</p>
          </div>

          <div class="item">
            <label class="item-label">
              <input type="checkbox" checked> Trekking
            </label>
            <img src="../images/Trekking.jpg" alt="Trekking">
            <p class="deskripsi">Trekking adalah aktivitas berjalan kaki jarak jauh di alam terbuka (gunung, hutan, pedesaan) yang berlangsung selama beberapa hari. Berbeda dengan hiking, trekking lebih menantang, melintasi medan terpencil yang belum terjamah, dan membutuhkan persiapan fisik serta mental yang matang untuk petualangan yang intens.</p>
          </div>

          <div class="item">
            <label class="item-label">
              <input type="checkbox" checked> Jeep Wisata
            </label>
            <img src="../images/Jeep.jpg" alt="Jeep Wisata">
            <p class="deskripsi">Jeep wisata adalah paket tur petualangan menggunakan kendaraan Jeep \(4\times4\) untuk menjelajahi medan ekstrem atau kawasan wisata alam yang sulit dijangkau kendaraan biasa. Aktivitas ini menggabungkan sensasi off-road, pemandangan alam, dan swafoto di spot unik, populer di lokasi seperti Merapi, Tebing Breksi, dan dieng.</p>
          </div>

        </div>

        <div class="fasilitas-box">
          <div class="section-title">FASILITAS</div>
          <ul>
            <li>Kelapa muda</li>
            <li>Welcome drink</li>
            <li>P3K standar</li>
            <li>Asuransi</li>
            <li>Peralatan Rafting lengkap</li>
            <li>Guide/pemandu</li>
            <li>Transportasi lokal PP</li>
            <li>Makan 1X</li>
          </ul>
        </div>

      </div>

      <div class="right-section">

        <div class="paket-card" onclick="pilihPaket(this, 'PAKET FUN RAFTING', 135000)">
          <div class="paket-title">
            PAKET FUN RAFTING
            <small>Jarak 4 KM (~1 - 1,5 jam)</small>
          </div>
          <div class="paket-harga">135 Ribu <span>/pax</span></div>
        </div>

        <div class="paket-card" onclick="pilihPaket(this, 'PAKET MEDIUM', 175000)">
          <div class="paket-title">
            PAKET MEDIUM
            <small>Jarak 12 km (~2,5 - 3 jam)</small>
          </div>
          <div class="paket-harga">175 Ribu <span>/pax</span></div>
        </div>

        <div class="paket-card" onclick="pilihPaket(this, 'PAKET LONG TRIP', 210000)">
          <div class="paket-title">
            PAKET LONG TRIP
            <small>Jarak 15 km (~3 - 3,5 jam)</small>
          </div>
          <div class="paket-harga">210 Ribu <span>/pax</span></div>
        </div>

        <button class="btn-next" onclick="lanjut()">Next ➤</button>

      </div>

    </div>
  </div>

  <script>
    let paketDipilih = null;

    function pilihPaket(el, nama, harga) {
      paketDipilih = { nama, harga };
      document.querySelectorAll('.paket-card').forEach(c => c.classList.remove('active'));
      el.classList.add('active');
    }

    function lanjut() {
      if (!paketDipilih) {
        alert("Pilih paket dulu ya!");
        return;
      }
      window.location.href =
        "booking.php?paket=" + encodeURIComponent(paketDipilih.nama) +
        "&harga=" + paketDipilih.harga;
    }
  </script>

</body>
</html>