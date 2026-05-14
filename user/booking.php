<?php
	include '../koneksi.php';
	include 'header.php';

	// Ambil data paket dari URL
	$paket = isset($_GET['paket']) ? $_GET['paket'] : '';
	$harga = isset($_GET['harga']) ? (int)$_GET['harga'] : 0;

	// Data paket lengkap
	$data_paket = [
		'Fun Rafting' => [
			'jarak'   => 'Jarak 4 km (~+1- 1,5 jam)',
			'harga'   => 135000,
			'img'     => '../images/1.jpg',
			'img2'    => '../images/2.jpg',
		],
		'Medium' => [
			'jarak'   => 'Jarak 12 km (~2,5 - 3 jam)',
			'harga'   => 175000,
			'img'     => '../images/1.jpg',
			'img2'    => '../images/2.jpg',
		],
		'Long Trip' => [
			'jarak'   => 'Jarak 15 km (~3 - 3,5 jam)',
			'harga'   => 210000,
			'img'     => '../images/1.jpg',
			'img2'    => '../images/2.jpg',
		],
	];

	$info = isset($data_paket[$paket]) ? $data_paket[$paket] : $data_paket['Fun Rafting'];
	$harga_final = $info['harga'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking - Rafting Singorojo</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background-color: #f0eada;
      font-family: 'Poppins', Arial, sans-serif;
    }

    .wrapper {
      max-width: 1200px;
      margin: 28px auto;
      padding: 0 20px;
    }

    .container-content {
      display: flex;
      gap: 22px;
      align-items: flex-start;
    }

    /* ===== KIRI ===== */
    .left-section {
      flex: 2;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    /* Banner paket */
    .paket-banner {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-template-rows: auto auto;
      gap: 12px;
    }

    .paket-banner-main {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      grid-row: 1 / 3;
    }

    .paket-banner-main img {
      width: 100%;
      height: 260px;
      object-fit: cover;
      display: block;
    }

    .paket-label {
      position: absolute;
      top: 0; left: 0;
      background: #2daae1;
      color: white;
      font-weight: 700;
      font-size: 18px;
      padding: 14px 18px;
      width: 100%;
    }

    .paket-banner-img2 {
      border-radius: 12px;
      overflow: hidden;
    }

    .paket-banner-img2 img {
      width: 100%;
      height: 130px;
      object-fit: cover;
      display: block;
    }

    .paket-info-boxes {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .info-box {
      background: white;
      border-radius: 10px;
      border: 1.5px solid #ddd;
      text-align: center;
      padding: 14px 10px;
      font-size: 14px;
      font-weight: 600;
      color: #333;
      line-height: 1.4;
    }

    /* Deskripsi */
    .deskripsi-box {
      background: white;
      border-radius: 14px;
      padding: 22px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    .deskripsi-box p {
      font-size: 13.5px;
      color: #555;
      line-height: 1.7;
      margin-bottom: 14px;
      text-align: justify;
    }

    .deskripsi-box p:last-child { margin-bottom: 0; }

    /* ===== KANAN ===== */
    .right-section {
      flex: 1;
      background: white;
      border-radius: 14px;
      padding: 18px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    .form-title {
      font-weight: 700;
      font-size: 15px;
      color: #333;
      text-align: center;
      margin-bottom: 14px;
      letter-spacing: 0.3px;
    }

    .form-img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 16px;
    }

    /* Tabel form */
    .form-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
    }

    .form-table tr td {
      padding: 6px 4px;
      font-size: 13px;
      color: #444;
      vertical-align: middle;
    }

    .form-table tr td:first-child {
      font-weight: 600;
      white-space: nowrap;
      width: 100px;
    }

    .form-table tr td:nth-child(2) {
      width: 14px;
      color: #888;
    }

    .form-table input[type="date"],
    .form-table input[type="time"],
    .form-table input[type="tel"] {
      border: 1.5px solid #ddd;
      border-radius: 6px;
      padding: 5px 8px;
      font-size: 13px;
      width: 100%;
      font-family: 'Poppins', sans-serif;
      outline: none;
      transition: border 0.2s;
    }

    .form-table input:focus {
      border-color: #2daae1;
    }

    /* Counter orang */
    .counter {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .counter button {
      width: 26px;
      height: 26px;
      border-radius: 5px;
      border: 1.5px solid #ccc;
      background: #f5f5f5;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      line-height: 1;
      transition: background 0.2s;
    }

    .counter button:hover { background: #2daae1; color: white; border-color: #2daae1; }

    .counter span {
      font-weight: 700;
      font-size: 15px;
      min-width: 20px;
      text-align: center;
    }

    /* Divider */
    .divider {
      border: none;
      border-top: 1.5px solid #eee;
      margin: 10px 0;
    }

    /* Total harga */
    .total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: 700;
      font-size: 14px;
      color: #222;
      margin-bottom: 14px;
    }

    .total-row .total-nominal {
      color: #2daae1;
      font-size: 15px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      font-size: 12.5px;
      color: #666;
      margin-bottom: 5px;
    }

    /* Tombol booking */
    .btn-booking {
      background: #3a7d44;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 25px;
      width: 100%;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      letter-spacing: 1px;
      font-family: 'Poppins', sans-serif;
      transition: background 0.2s, transform 0.2s;
    }

    .btn-booking:hover {
      background: #2d6235;
      transform: translateY(-1px);
    }

    @media (max-width: 768px) {
      .container-content { flex-direction: column; }
      .paket-banner { grid-template-columns: 1fr; }
      .paket-banner-main { grid-row: auto; }
    }
  </style>
</head>
<body>

<div class="wrapper">
  <div class="container-content">

    <!-- KIRI -->
    <div class="left-section">

      <!-- Banner & Info Paket -->
      <div class="paket-banner">
        <div class="paket-banner-main">
          <div class="paket-label">PAKET <?= strtoupper(htmlspecialchars($paket)) ?></div>
          <img src="<?= $info['img'] ?>" alt="Rafting">
        </div>

        <div class="paket-banner-img2">
          <img src="<?= $info['img2'] ?>" alt="Rafting 2">
        </div>

        <div class="paket-info-boxes">
          <div class="info-box">
            <?= number_format($info['harga'], 0, ',', '.') ?> Ribu<br>/pax
          </div>
          <div class="info-box">
            <?= htmlspecialchars($info['jarak']) ?>
          </div>
        </div>
      </div>

      <!-- Deskripsi -->
      <div class="deskripsi-box">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
      </div>

    </div>

    <!-- KANAN: FORM PEMESANAN -->
    <div class="right-section">
      <div class="form-title">FROM PEMESANAN</div>

      <img src="<?= $info['img2'] ?>" alt="Preview" class="form-img">

      <form action="booking_proses.php" method="POST">
        <input type="hidden" name="paket" value="<?= htmlspecialchars($paket) ?>">
        <input type="hidden" name="harga_satuan" value="<?= $harga_final ?>">

        <table class="form-table">
          <tr>
            <td>Paket</td>
            <td>:</td>
            <td><strong><?= htmlspecialchars($paket) ?></strong></td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><input type="date" name="tanggal" required min="<?= date('Y-m-d') ?>"></td>
          </tr>
          <tr>
            <td>Jam</td>
            <td>:</td>
            <td><input type="time" name="jam" required></td>
          </tr>
          <tr>
            <td>Orang</td>
            <td>:</td>
            <td>
              <div class="counter">
                <button type="button" onclick="kurang()">-</button>
                <span id="jumlah">2</span>
                <button type="button" onclick="tambah()">+</button>
              </div>
              <input type="hidden" name="jumlah_orang" id="input_jumlah" value="2">
            </td>
          </tr>
          <tr>
            <td>No. Telepon</td>
            <td>:</td>
            <td><input type="tel" name="no_telepon" placeholder="08xxxxxxxxxx" required></td>
          </tr>
        </table>

        <hr class="divider">

        <div class="info-row">
          <span>Harga Satuan</span>
          <span>: Rp <?= number_format($harga_final, 0, ',', '.') ?></span>
        </div>
        <div class="info-row">
          <span>Jumlah Orang</span>
          <span>: <span id="show_orang">2</span> orang</span>
        </div>

        <hr class="divider">

        <div class="total-row">
          <span>TOTAL HARGA :</span>
          <span class="total-nominal" id="show_total">Rp <?= number_format($harga_final * 2, 0, ',', '.') ?></span>
        </div>

        <button type="submit" class="btn-booking">BOOKING</button>
      </form>
    </div>

  </div>
</div>

<script>
  let jumlah = 2;
  const harga = <?= $harga_final ?>;

  function update() {
    document.getElementById('jumlah').textContent = jumlah;
    document.getElementById('input_jumlah').value = jumlah;
    document.getElementById('show_orang').textContent = jumlah;
    const total = jumlah * harga;
    document.getElementById('show_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
  }

  function tambah() {
    jumlah++;
    update();
  }

  function kurang() {
    if (jumlah > 1) { jumlah--; update(); }
  }
</script>

</body>
</html>