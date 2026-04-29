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
			max-width: 1500px; /* Dikecilkan biar tidak terlalu lebar */
			margin: auto;
			padding: 20px;
		}

		.container-content {
			display: flex;
			gap: 25px;
			align-items: flex-start;
		}

		/* --- BAGIAN KIRI: KEGIATAN & FASILITAS --- */
		.left-section {
			flex: 5;
			background: white;
			padding: 25px;
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
			margin-bottom: 20px;
			display: inline-block;
		}

		/* Layout Grid Kegiatan */
		.grid-kegiatan {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 20px;
			margin-bottom: 25px;
		}

		.item {
			text-align: center;
		}

		.item img {
			width: 100%;
			height: 160px; /* Tinggi gambar disesuaikan */
			object-fit: cover;
			border-radius: 10px;
			margin-bottom: 10px;
			transition: 0.3s;
		}

		.item img:hover {
			transform: scale(1.03);
		}

		.item h4 {
			margin: 5px 0;
			font-size: 16px;
			color: #333;
		}

		.item p.desc {
			font-size: 13px;
			color: #666;
			line-height: 1.4;
			margin: 0;
			text-align: justify;
		}

		/* Box Fasilitas */
		.fasilitas-box {
			background: #fafafa;
			border-radius: 12px;
			padding: 15px 20px;
			border: 1px solid #ddd;
		}

		.fasilitas-box ul {
			list-style: none;
			padding: 0;
			columns: 2; /* Buat jadi 2 kolom biar hemat tempat */
		}

		.fasilitas-box li {
			margin-bottom: 8px;
			font-size: 14px;
		}

		/* --- BAGIAN KANAN: PILIHAN PAKET --- */
		.right-section {
			flex: 1;
			display: flex;
			flex-direction: column;
			gap: 12px; /* Jarak antar kotak paket */
		}

		.paket-card {
			background: white;
			border-radius: 12px;
			overflow: hidden;
			box-shadow: 0 4px 10px rgba(0,0,0,0.1);
			cursor: pointer;
			transition: 0.3s;
			border: 2px solid transparent; /* Tambah border transparan */
		}

		.paket-card:hover {
			transform: translateY(-3px);
		}

		.paket-card.active {
			border-color: #2daae1;
			transform: scale(1.02);
		}

		.paket-title {
    background: #2daae1;
    color: white;
    padding: 15px; /* Dikecilkan */
    text-align: center;
    font-weight: bold;
    font-size: 14px; /* Dikecilkan */
		}

		.paket-title small {
			display: block;
			font-size: 11px;
			font-weight: normal;
			opacity: 0.9;
		}

		.paket-harga {
    padding: 8px; /* Dikecilkan */
    font-size: 16px; /* Dikecilkan */
    text-align: center;
    font-weight: bold;
    background: #f9f9f9;
    color: #333; 
		}

		.btn-next {
			background: #2daae1;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 20px;
			cursor: pointer;
			margin-top: 10px;
			margin-left: auto;
			font-weight: bold;
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
						<img src="../images/30.jpg" alt="Rafting Bodri">
						<h4>Rafting Bodri</h4>
						<p class="desc">Rafting atau arung jeram adalah aktivitas olahraga dan wisata petualangan yang menggunakan perahu karet untuk mengarungi jeram sungai berarus deras. Kegiatan ini membutuhkan kerja sama tim, dayung, serta peralatan keselamatan (helm, pelampung) di bawah panduan pemandu berpengalaman untuk menavigasi rintangan seperti bebatuan.</p>
					</div>

					<div class="item">
						<img src="../images/Canyoning.jpg" alt="Canyoning">
						<h4>Canyoning</h4>
						<p class="desc">Canyoning (atau canyoneering) adalah olahraga ekstrem penjelajahan ngarai, sungai, atau air terjun yang menggabungkan teknik hiking, berenang, melompat, meluncur, dan rappelling (turun tebing dengan tali). Aktivitas ini fokus menyusuri aliran air di celah batuan curam, sering kali menuruni air terjun secara teknis.</p>
					</div>

					<div class="item">
						<img src="../images/Outbound.jpg" alt="Outbound">
						<h4>Outbound</h4>
						<p class="desc">Outbound adalah kegiatan pelatihan atau rekreasi luar ruangan (outdoor) yang menggunakan metode experiential learning (belajar dari pengalaman) melalui permainan, simulasi, dan tantangan. Tujuannya meningkatkan keterampilan individu dan tim seperti team building, kepemimpinan, komunikasi, serta kerjasama. Outbound berfokus pada pengembangan diri, mental, dan fisik.</p>
					</div>

					<div class="item">
						<img src="../images/Trekking.jpg" alt="Trekking">
						<h4>Trekking</h4>
						<p class="desc">Trekking adalah aktivitas berjalan kaki jarak jauh di alam bebas (pegunungan, hutan, pedesaan) yang menantang dan memakan waktu beberapa hari. Berbeda dengan hiking, trekking lebih intensif, menuntut fisik prima, dan seringkali melalui jalur terpencil atau tidak terjalur. Ini adalah cara menjelajahi alam, budaya, dan medan berat untuk tujuan rekreasi.</p>
					</div>

					<div class="item">
						<img src="../images/Jeep.jpg" alt="Jeep Wisata">
						<h4>Jeep Wisata</h4>
						<p class="desc">Jeep wisata adalah aktivitas petualangan menggunakan kendaraan Jeep (seringkali terbuka) untuk menjelajahi medan ekstrem, terjal, atau berpasir, seperti kawasan lava tour, hutan, atau perbukitan. Wisata ini menawarkan sensasi off-road ringan, pemandangan alam memukau, dan pengalaman seru di destinasi seperti Merapi, Kaliurang, Bromo, atau Kalibiru.</p>
					</div>
				</div>

				<div class="fasilitas-box">
					<div class="section-title">FASILITAS</div>
					<ul>
						<li>➢ Kelapa muda & Welcome drink</li>
						<li>➢ P3K standar & Asuransi</li>
						<li>➢ Peralatan Rafting lengkap</li>
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
						<small>Jarak 4 KM (~1 - 1,5 jam)</small>
					</div>
					<div class="paket-harga">135 Ribu /pax</div>
				</div>

				<div class="paket-card" onclick="pilihPaket('Medium', 175000)">
					<div class="paket-title">
						PAKET MEDIUM
						<small>Jarak 12 KM (~2,5 - 3 jam)</small>
					</div>
					<div class="paket-harga">175 Ribu /pax</div>
				</div>

				<div class="paket-card" onclick="pilihPaket('Long Trip', 210000)">
					<div class="paket-title">
						PAKET LONG TRIP
						<small>Jarak 15 KM (~3 - 3,5 jam)</small>
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
				alert("Pilih paket dulu ya!");
				return;
			}

			window.location.href =
				"booking.php?paket=" + paketDipilih.nama +
				"&harga=" + paketDipilih.harga;
		}
	</script>

</body>
</html>