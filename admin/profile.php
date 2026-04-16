<?php
include '../koneksi.php';
include'header.php'; 
 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Rafting Singorojo</title>

	<style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #eee;
    }

    .container {
      width: 90%;
      margin: 20px auto;
      background: #d9a066;
      border-radius: 20px;
      padding: 20px;
    }

    /* Content */
    .content {
      display: flex;
      gap: 20px;
    }

    .left {
      flex: 2;
    }

    .right {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    h2 {
      margin-top: 0;
    }

    .card {
      margin-top: 20px;
      padding: 15px;
      background: #e6b37a;
      border-radius: 10px;
      width: fit-content;
    }

    .images img {
      width: 100%;
      border-radius: 10px;
    }

    .images {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
  </style>

</head>
<body>

	<div class="content">
		<div class="left">
			<h2>Rafting Singorojo / Arung Jeram Sungai Bodri Singorojo</h2>
			<p>
				Rafting adalah kegiatan rekreasi luar ruangan yang menggunakan rakit tiup untuk mengarungi sungai atau badan air lainnya. Hal ini sering dilakukan di perairan berarus deras atau perairan dengan tingkat kekasaran yang berbeda. Menghadapi risiko seringkali menjadi bagian dari pengalaman tersebut.
			</p>

			<p>
				Aktivitas ini sebagai olahraga petualangan telah menjadi populer sejak tahun 1950-an, atau bahkan lebih awal, berkembang dari individu yang mendayung rakit sepanjang 3 meter (9,8 kaki) hingga 4,3 meter (14 kaki) dengan dayung ganda atau kayuh menjadi rakit multi-orang yang digerakkan oleh dayung tunggal dan dikemudikan oleh seseorang di buritan, atau dengan menggunakan kayuh .
			</p>

			<div class="card">
				<strong>Rafting Singorojo</strong><br>
				⭐ 5 (6 ulasan)<br>
				📍 Kec. Singorojo, Kendal, Jawa Tengah
			</div>
		</div>

		<div class="right">
			<div class="images">
				<img src="../images/5.jpg">
				<img src="../images/45.jpg">
				<img src="../images/47.jpg">
			</div>
		</div>
	</div>

</body>
</html>