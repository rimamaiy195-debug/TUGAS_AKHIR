<?php
include '../koneksi.php';
include 'header.php';

$paket = $_GET['paket'] ?? 'Fun Rafting';
$harga = $_GET['harga'] ?? 135000;

if(isset($_POST['submit'])) {

	$jumlah  = $_POST['jumlah'];
	$tanggal = $_POST['tanggal'];
	$jam     = $_POST['jam'];
	$no_hp   = $_POST['no_hp'];

	$total = $harga * $jumlah;

	mysqli_query($koneksi, "INSERT INTO booking 
	(paket, harga, jumlah, tanggal, jam, no_hp, total) 
	VALUES 
	('$paket','$harga','$jumlah','$tanggal','$jam','$no_hp','$total')");

	header("Location: konfirmasi.php?paket=$paket&total=$total");
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking</title>

<style>
body {
	background: #f0eada;
	font-family: Arial;
}

.wrapper {
	max-width: 1200px;
	margin: auto;
	padding: 20px;
}

/* CONTAINER */
.container {
	display: flex;
	gap: 20px;
}

/* KIRI */
.left {
	flex: 2;
	background: white;
	padding: 20px;
	border-radius: 10px;
}

.header-paket {
	background: #2daae1;
	color: white;
	padding: 10px;
	font-weight: bold;
	border-radius: 5px;
	margin-bottom: 10px;
}

.img-main img {
	width: 100%;
	border-radius: 5px;
}

.info-box {
	display: flex;
	gap: 10px;
	margin: 10px 0;
}

.box {
	background: #f3f3f3;
	padding: 10px;
	border-radius: 5px;
	text-align: center;
	flex: 1;
}

.desc {
	font-size: 14px;
	line-height: 1.6;
	text-align: justify;
}

/* KANAN */
.right {
	flex: 1;
	background: white;
	padding: 15px;
	border-radius: 10px;
}

.right img {
	width: 100%;
	border-radius: 5px;
	margin-bottom: 10px;
}

.form-group {
	display: flex;
	justify-content: space-between;
	margin-bottom: 10px;
	font-size: 14px;
}

.form-group input {
	border: none;
	border-bottom: 1px solid #ccc;
	text-align: right;
}

.qty {
	display: flex;
	gap: 5px;
}

.qty button {
	width: 25px;
	cursor: pointer;
}

.total {
	display: flex;
	justify-content: space-between;
	margin-top: 15px;
	font-weight: bold;
}

.btn {
	width: 100%;
	background: green;
	color: white;
	padding: 10px;
	border: none;
	margin-top: 10px;
	border-radius: 20px;
	cursor: pointer;
}
</style>

</head>
<body>

<div class="wrapper">

<div class="container">

<!-- KIRI -->
<div class="left">

	<div class="header-paket">
		PAKET <?= strtoupper($paket); ?>
	</div>

	<div class="img-main">
		<img src="../images/1.jpg">
	</div>

	<div class="info-box">
		<div class="box">Rp <?= number_format($harga); ?><br>/pax</div>
		<div class="box">Jarak 4 KM<br>(±1-1.5 jam)</div>
	</div>

	<div class="desc">
		Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
		Nikmati pengalaman rafting seru bersama tim profesional 
		dengan keamanan dan fasilitas terbaik.
	</div>

</div>

<!-- KANAN -->
<div class="right">

<form method="POST">

	<img src="../images/1.jpg">

	<div class="form-group">
		<span>Paket</span>
		<span><?= $paket ?></span>
	</div>

	<div class="form-group">
		<span>Tanggal</span>
		<input type="date" name="tanggal" required>
	</div>

	<div class="form-group">
		<span>Jam</span>
		<input type="time" name="jam" required>
	</div>

	<div class="form-group">
		<span>Orang</span>
		<div class="qty">
			<button type="button" onclick="kurang()">-</button>
			<input type="text" id="jumlah" name="jumlah" value="2">
			<button type="button" onclick="tambah()">+</button>
		</div>
	</div>

	<div class="form-group">
		<span>No HP</span>
		<input type="text" name="no_hp" required>
	</div>

	<div class="form-group">
		<span>Harga</span>
		<span>Rp <?= number_format($harga); ?></span>
	</div>

	<div class="form-group">
		<span>Jumlah</span>
		<span id="jumlahText">2</span>
	</div>

	<div class="total">
		<span>TOTAL</span>
		<span id="total">Rp <?= number_format($harga * 2); ?></span>
	</div>

	<button class="btn" name="submit">BOOKING</button>

</form>

</div>

</div>
</div>

<script>
let harga = <?= $harga ?>;
let jumlah = 2;

function update() {
	document.getElementById('jumlah').value = jumlah;
	document.getElementById('jumlahText').innerText = jumlah;
	document.getElementById('total').innerText =
		'Rp ' + (harga * jumlah).toLocaleString();
}

function tambah() {
	jumlah++;
	update();
}

function kurang() {
	if(jumlah > 1){
		jumlah--;
		update();
	}
}
</script>

</body>
</html>