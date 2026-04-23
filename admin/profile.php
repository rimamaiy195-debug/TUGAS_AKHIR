<?php 
include '../koneksi.php';
include 'header.php'
 ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rafting Singorojo – Profil</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"/>
  <style>
    * { 
      margin: 0; 
      padding: 0; 
      box-sizing: border-box; 
    }

    body {
      font-family: 'Nunito', sans-serif;
      background: #F5EDD8;
      min-height: 100vh;
      padding: 0;
      margin: 0;
    }

    .profile-card {
      background: #C8922A;
      margin: 0;           
      border-radius: 0;
      padding: 36px 60px;
      width: 100%;
      max-width: 100%;     
      min-height: calc(100vh - 80px); 
      display: grid;
      grid-template-columns: 1fr 280px;
      gap: 40px;
      animation: fadeUp 0.6s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .left h1 {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      font-weight: 900;
      color: #1a1a1a;
      line-height: 1.35;
      margin-bottom: 16px;
    }

    .left p {
      font-size: 13.5px;
      color: #2a1a00;
      line-height: 1.8;
      margin-bottom: 13px;
    }

    .left p a {
      color: #2a1a00;
      text-decoration: underline;
      text-underline-offset: 2px;
    }

    .rating-card {
      margin-top: 18px;
      background: rgba(255,255,255,0.2);
      border-radius: 10px;
      padding: 13px 16px;
      display: inline-block;
    }

    .rating-card .biz { font-weight: 700; font-size: 13px; color: #1a1a1a; margin-bottom: 4px; }
    .stars { color: #FFD700; font-size: 14px; letter-spacing: 1px; }
    .score { font-size: 13px; font-weight: 600; color: #1a1a1a; }
    .loc { font-size: 12px; color: #3a2800; margin-top: 5px; }

    .photo-grid {
      display: flex;
      flex-direction: column;
      gap: 10px;
      background: transparent;
    }

    .photo {
      width: 100%;
      height: 160px;
      border-radius: 10px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.22);
      overflow: hidden;
      background: rgba(0, 0, 0, 0.15);
      border: 3px solid rgba(255, 255, 255, 0.2);
    }

    .photo-wrapper {
      background: rgba(139, 90, 20, 0.4);
      border-radius: 14px;
      padding: 8px;
    }

    .photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      border-radius: 10px;
      display: block;
    }

    .photo:hover { transform: scale(1.04); }

    .p1 { background: linear-gradient(135deg, #0e4d7a, #1a8fc1); }
    .p2 { background: linear-gradient(135deg, #1a5c2a, #27a147); }
    .p3 { background: linear-gradient(135deg, #1a3a6b, #2a5faa); }

    @media (max-width: 560px) {
      .profile-card {
        grid-template-columns: 1fr;
        padding: 24px 20px;
      }
      .photo-grid { 
        flex-direction: row; 
      }
      .photo { height: 90px; font-size: 24px; }
    }
  </style>
</head>
<body>

<div class="profile-card">
  <div class="left">
    <h2>Rafting Singorojo / Arung Jeram<br>Sungai Bodri Singorojo</h2>
    <br><br>
    <p>
      Rafting adalah <a href="#">kegiatan rekreasi luar ruangan</a> yang menggunakan <a href="#">rakit</a> tiup untuk mengarungi <a href="#">sungai</a> atau badan air lainnya. Hal ini sering dilakukan di <a href="#">perairan berarus deras</a> atau perairan dengan tingkat kekasaran yang berbeda. Menghadapi risiko seringkali menjadi bagian dari pengalaman tersebut.
    </p>
    <p>
      Aktivitas ini sebagai <a href="#">olahraga petualangan</a> telah menjadi populer sejak tahun 1950-an, atau bahkan lebih awal, berkembang dari individu yang mendayung rakit sepanjang 3 meter (9,8 kaki) hingga 4,3 meter (14 kaki) dengan <a href="#">dayung</a> ganda atau kayuh menjadi rakit multi-orang yang digerakkan oleh dayung tunggal dan dikemudikan oleh seseorang di buritan, atau dengan menggunakan <a href="#">kayuh</a>.
    </p>
    <p>
      Tubing dan Rafting di Sungai Bodri Desa Singorojo. Singorojo, salah satu desa yang terletak di wilayah kecamatan singorojo kabupaten kendal dimana wilayahnya berada di sebelah selatan Kabupaten Kendal, desa tersebut berbatasan di sebelah Utara dengan Kecamatan Kaliwungu Selatan dan Kecamatan Ampel, Sebelah Timur berbatasan dengan desa Cacaban dan desa Kalirejo, sedangkan sebelah selatan dengan Desa Banyuringin dan Kabupaten Temanggung serta sebelah barat dengan kecamatan Patean.
    </p>
    <p>
       Wilayah Singorojo diberikan alam yang indah serta air yg melimpah namun karena penambangan liar dan exploitasi sungai berlebihan mengakibatkan sungai yang tadinya indah menjadi pendangkalan serta ikan endemik wader ikon desa berkurang. Melihat hal demikian pemuda Singorojo prihatin membuat sebuah organisasi Pokdarwis yg di ketuai Nuris Nur Sahid bersama pemuda Karang Taruna “Samudra” serta masyarakat memanfaatkan sungai yang awalnya di tambang batunya di jadikan wahana wisata extrem yaitu " Tubing & Rafting" untuk meningkatkan perekonomian masyarakat sekitar serta menjaga ekosistem sungai.
    </p>
    <p>
        RUTE : Lokasi Lembah Singorojo ini berada di antara perkebunan Karet persil serta perkebunan Plantera Fruit berjarak sekitar 38 Kilometer dari kota Semarang. Jika anda dari arah Boja, maka arahkan kendaraan anda menuju arah barat langsung menuju desa Singorojo, di desa itu nanti akan banyak terdapat papan penunjuk arah menuju sungai untuk tubing dan rafting, atau jika anda akan reservasi untuk grup atau komunitas yang ingin menikmati segarnya petualangan tubing dan rafting silahkan hubungi Tomi Tubing Singorojo di 0831-0204-8865
    </p>

    <div class="rating-card">
      <div class="biz">Rafting Singorojo</div>
      <span class="stars">★★★★★</span>
      <span class="score"> 5 (6 ulasan)</span>
      <div class="loc">📍 Kec. Singorojo, Kendal, Jawa Tengah</div>
    </div>
  </div>

 
  <div class="photo-grid">
    <div class="photo-wrapper">
      <div class="photo"><img src="../images/4.jpg"></div>
    </div>
    <div class="photo-wrapper">
      <div class="photo"><img src="../images/45.jpg"></div>
    </div>
    <div class="photo-wrapper">
      <div class="photo"><img src="../images/47.jpg"></div>
    </div>
  </div>
</div>

</body>
</html>