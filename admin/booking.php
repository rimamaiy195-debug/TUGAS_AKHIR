<?php
	include '../koneksi.php';
	include 'header.php';

	// Ambil data dari URL (yang dikirim dari halaman paket)
	$nama_paket = isset($_GET['paket']) ? $_GET['paket'] : 'Paket Tidak Diketahui';
	$harga_satuan = isset($_GET['harga']) ? $_GET['harga'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Booking - Rafting Singorojo</title>
	<style>
		body {
			background-color: #f0eada;
			font-family: Arial, sans-serif;
			margin: 0;
		}

		.wrapper {
			max-width: 1200px;
			margin: 20px auto;
			padding: 0 20px;
		}

		.container {
			display: flex;
			gap: 25px;
			align-items: flex-start;
		}

		/* --- BAGIAN KIRI: INFO PAKET --- */
		.left-info {
			flex: 2;
			background: white;
			border-radius: 15px;
			padding: 20px;
			box-shadow: 0 5px 15px rgba(0,0,0,0.1);
		}

		.paket-header {
			background: #2daae1;
			color: white;
			padding: 15px;
			border-radius: 10px;
			font-size: 22px;
			font-weight: bold;
			text-align: center;
			margin-bottom: 15px;
		}

		.gambar-paket {
			width: 100%;
			border-radius: 10px;
			margin-bottom: 15px;
		}

		.detail-box {
			display: flex;
			gap: 15px;
			margin-bottom: 20px;
		}

		.box-kecil {
			flex: 1;
			background: #f9f9f9;
			padding: 15px;
			border-radius: 8px;
			text-align: center;
			font-weight: bold;
			border: 1px solid #ddd;
		}

		.deskripsi {
			text-align: justify;
			line-height: 1.6;
			color: #444;
		}

		/* --- BAGIAN KANAN: FORM PESANAN --- */
		.right-form {
			flex: 1;
			background: white;
			border-radius: 15px;
			padding: 20px;
			box-shadow: 0 5px 15px rgba(0,0,0,0.1);
		}

		.right-form h3 {
			text-align: center;
			margin-top: 0;
			color: #333;
			border-bottom: 2px solid #ddd;
			padding-bottom: 10px;
		}

		.gambar-preview {
			width: 100%;
			border: 2px dashed #ccc;
			border-radius: 8px;
			margin-bottom: 20px;
		}

		.form-group {
			margin-bottom: 12px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.form-group label {
			font-weight: bold;
			color: #555;
		}

		.form-group input,
		.form-group select {
			padding: 6px 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			width: 150px;
		}

		.jumlah-control {
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.jumlah-control button {
			width: 25px;
			height: 25px;
			cursor: pointer;
			background: #eee;
			border: 1px solid #ccc;
			border-radius: 3px;
		}

		.total-harga {
			background: #f0f0f0;
			padding: 10px;
			border-radius: 8px;
			font-size: 18px;
			font-weight: bold;
			text-align: right;
			margin: 15px 0;
		}

		.btn-booking {
			width: 100%;
			padding: 12px;
			background: #28a745;
			color: white;
			border: none;
			border-radius: 8px;
			font-size: 16px;
			font-weight: bold;
			cursor: pointer;
		}
	</style>
</head>
<body>


<div class="wrapper">
	<div class="container">

		<!-- BAGIAN KIRI: INFO PAKET -->
		<div class="left-info">
			<div class="paket-header">PAKET <?php echo strtoupper($nama_paket); ?></div>
			
			<img src="../images/30.jpg" alt="Rafting" class="gambar-paket">

			<div class="detail-box">
				<div class="box-kecil">
					<?php 
						echo number_format($harga_satuan, 0, ',', '.') . " Ribu /pax"; 
					?>
				</div>
				<div class="box-kecil">
					<?php 
						if($nama_paket == "Fun Rafting") echo "Jarak 4 KM (~1-1,5 jam)";
						else if($nama_paket == "Medium") echo "Jarak 12 KM (~2,5-3 jam)";
						else if($nama_paket == "Long Trip") echo "Jarak 15 KM (~3-3,5 jam)";
						else echo "Jarak Tidak Diketahui";
					?>
				</div>
			</div>

			<div class="deskripsi">
				<p>Menikmati serunya arung jeram di sungai Bodri dengan pemandangan alam yang asri dan menantang adrenalin. Cocok untuk liburan keluarga, gathering kantor, atau sekolah.</p>
				<p>Fasilitas lengkap termasuk peralatan safety, guide berpengalaman, makan siang, dan dokumentasi.</p>
			</div>
		</div>

		<!-- BAGIAN KANAN: FORM PEMESANAN -->
		<div class="right-form">
			<h3>FORM PEMESANAN</h3>
			
			<img src="../images/12.jpg" alt="Preview" class="gambar-preview">

			<form method="POST" action="proses_booking.php">
				
				<input type="hidden" name="harga_satuan" value="<?php echo $harga_satuan; ?>">
				<input type="hidden" name="nama_paket" value="<?php echo $nama_paket; ?>">

				<div class="form-group">
					<label>Paket</label>
					<span><strong><?php echo $nama_paket; ?></strong></span>
				</div>

				<div class="form-group">
					<label>Tanggal</label>
					<input type="date" name="tanggal" required>
				</div>

				<div class="form-group">
					<label>Jam</label>
					<select name="jam" required>
						<option value="">-- Pilih Jam --</option>
						<option value="08:00">08:00</option>
						<option value="10:00">10:00</option>
						<option value="13:00">13:00</option>
					</select>
				</div>

				<div class="form-group">
					<label>Jumlah Orang</label>
					<div class="jumlah-control">
						<button type="button" onclick="kurang()">-</button>
						<input type="number" id="jumlah" name="jumlah_orang" value="2" min="1" readonly>
						<button type="button" onclick="tambah()">+</button>
					</div>
				</div>

				<div class="form-group">
					<label>No. Telepon</label>
					<input type="text" name="telepon" placeholder="08xx..." required>
				</div>

				<div class="total-harga">
					Total: Rp <span id="total"><?php echo number_format($harga_satuan * 2, 0, ',', '.'); ?></span>
				</div>

				<button type="submit" class="btn-booking">BOOKING SEKARANG</button>

			</form>
		</div>

	</div>
</div>

<script>
	let harga = <?php echo $harga_satuan; ?>;

	function tambah() {
		let jml = document.getElementById('jumlah');
		jml.value = parseInt(jml.value) + 1;
		hitungTotal();
	}

	function kurang() {
		let jml = document.getElementById('jumlah');
		if(parseInt(jml.value) > 1) {
			jml.value = parseInt(jml.value) - 1;
			hitungTotal();
		}
	}

	function hitungTotal() {
		let jml = document.getElementById('jumlah').value;
		let total = harga * jml;
		document.getElementById('total').innerText = formatRupiah(total);
	}

	function formatRupiah(angka) {
		return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}
</script>

</body>
</html>