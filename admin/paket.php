<?php
	include '../koneksi.php';
	include 'header.php'; 
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Paket - Rafting Singorojo</title>

	<style>
		body {
			background-color: #f0eada;
			font-family: Arial, sans-serif;
			margin: 0;
		}

		.wrapper {
			max-width: 1500px;
			margin: auto;
			padding: 20px;
		}

		.container-content {
			display: flex;
			gap: 20px;
		}

		/* KIRI */
		.left-section {
			flex: 2;
			background: white;
			padding: 20px;
			border-radius: 15px;
			box-shadow: 0 5px 15px rgba(0,0,0,0.1);
		}

		.section-title {
			background: #d4a373;
			color: white;
			padding: 8px 18px;
			border-radius: 8px;
			font-weight: bold;
			font-size: 14px;
			margin-bottom: 15px;
			display: inline-block;
		}

		.grid-kegiatan {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 15px;
		}

		.item img {
			width: 100%;
			height: 140px;
			object-fit: cover;
			border-radius: 10px;
			margin-top: 5px;
			transition: 0.3s;
		}

		.item img:hover {
			transform: scale(1.05);
		}

		/* FASILITAS */
		.fasilitas-box {
			margin-top: 20px;
			background: #fafafa;
			border-radius: 12px;
			padding: 15px;
			border: 1px solid #ddd;
		}

		.fasilitas-box ul {
			list-style: none;
			padding: 0;
		}

		.fasilitas-box li {
			margin-bottom: 5px;
		}

		/* KANAN */
		.right-section {
			flex: 1;
			display: flex;
			flex-direction: column;
			gap: 15px;
		}

		.paket-card {
			background: white;
			border-radius: 15px;
			overflow: hidden;
			box-shadow: 0 8px 20px rgba(0,0,0,0.1);
			cursor: pointer;
			transition: 0.3s;
		}

		.paket-card:hover {
			transform: translateY(-5px);
		}

		.paket-card.active {
			border: 2px solid #2daae1;
			transform: scale(1.05);
		}

		.paket-title {
			background: #2daae1;
			color: white;
			padding: 12px;
			text-align: center;
			font-weight: bold;
		}

		.paket-title small {
			display: block;
			font-size: 12px;
		}

		.paket-harga {
			padding: 20px;
			font-size: 22px;
			text-align: center;
			font-weight: bold;
			background: #f9f9f9;
		}

		.btn-next {
			background: #2daae1;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 20px;
			cursor: pointer;
			margin-left: auto;
		}
	</style>
</head>

<body>

	<div class="wrapper">
		<div class="container-content">

			<!-- KIRI -->
			<div class="left-section">

				<div class="section-title">DAFTAR KEGIATAN OUTDOOR LAIN</div>

				<div class="grid-kegiatan">
					<div class="item">
						<p>☑ Rafting Bodri</p>
						<img src="../images/1.jpg">
					</div>

					<div class="item">
						<p>☑ Canyoning</p>
						<img src="../images/2.jpg">
					</div>

					<div class="item">
						<p>☑ Outbound</p>
						<img src="../images/3.jpg">
					</div>

					<div class="item">
						<p>☑ Trecking</p>
						<img src="../images/4.jpg">
					</div>

					<div class="item">
						<p>☑ Jeep wisata</p>
						<img src="../images/5.jpg">
					</div>
				</div>

				<div class="fasilitas-box">
					<div class="section-title">FASILITAS</div>
					<ul>
						<li>➢ Kelapa muda</li>
						<li>➢ Welcome drink</li>
						<li>➢ P3K standar</li>
						<li>➢ Asuransi</li>
						<li>➢ Peralatan Rafting</li>
						<li>➢ Guide/pemandu</li>
						<li>➢ Transportasi lokal PP</li>
						<li>➢ Makan 1X</li>
					</ul>
				</div>

			</div>

			<!-- KANAN -->
			<div class="right-section">

				<div class="paket-card" onclick="pilihPaket('Fun Rafting', 135000)">
					<div class="paket-title">
						PAKET FUN RAFTING
						<small>Jarak 4 KM</small>
					</div>
					<div class="paket-harga">135 Ribu /pax</div>
				</div>

				<div class="paket-card" onclick="pilihPaket('Medium', 175000)">
					<div class="paket-title">
						PAKET MEDIUM
						<small>Jarak 12 KM</small>
					</div>
					<div class="paket-harga">175 Ribu /pax</div>
				</div>

				<div class="paket-card" onclick="pilihPaket('Long Trip', 210000)">
					<div class="paket-title">
						PAKET LONG TRIP
						<small>Jarak 15 KM</small>
					</div>
					<div class="paket-harga">210 Ribu /pax</div>
				</div>

				<button class="btn-next" onclick="lanjut()">Next ➤</button>

			</div>

		</div>
	</div>

	<script>
		let paketDipilih = null;

		function pilihPaket(nama, harga) {
			paketDipilih = { nama, harga };

			document.querySelectorAll('.paket-card').forEach(el => {
				el.classList.remove('active');
			});

			event.currentTarget.classList.add('active');
		}

		function lanjut() {
			if (!paketDipilih) {
				alert("Pilih paket dulu!");
				return;
			}

			window.location.href =
				"booking.php?paket=" + paketDipilih.nama +
				"&harga=" + paketDipilih.harga;
		}
	</script>

</body>
</html>