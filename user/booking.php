<?php
include '../koneksi.php';
include 'header.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user    = (int)$_SESSION['id_user'];
$nama_paket = isset($_GET['paket']) ? trim($_GET['paket']) : '';
$harga_url  = isset($_GET['harga']) ? (int)$_GET['harga'] : 0;

if (empty($nama_paket)) {
    header("Location: ../paket.php"); exit;
}

// Cari data paket dari DB berdasarkan nama
$stmt = $koneksi->prepare("SELECT * FROM paket WHERE nama_paket = ? LIMIT 1");
$stmt->bind_param("s", $nama_paket);
$stmt->execute();
$paket = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Kalau tidak ketemu di DB, pakai data dari URL
if (!$paket) {
    $paket = [
        'id_paket'   => 0,
        'nama_paket' => $nama_paket,
        'harga'      => $harga_url,
        'kapasitas'  => 20,
        'deskripsi'  => 'Istilah arung jeram berasal dari kata whitewater rafting atau rafting yang jika diterjemahkan bebas ke dalam bahasa Inggris berarti mengarungi sungai menggunakan perahu dengan mengandalkan keterampilan mendayung. Menurut Federasi Arung Jeram Internasional (IRF), definisi arung jeram atau white water rafting adalah "aktivitas manusia dalam mengarungi sungai dengan mengandalkan keterampilan dan kekuatan fisik untuk mendayung perahu yang terbuat dari bahan lunak yang secara umum diterima sebagai aktivitas sosial, komersial, dan olahraga". Meskipun pada awal perkembangannya di Indonesia istilah arung jeram memiliki beberapa nama, namun dalam standar kompetensi ini terminologi "white water rafting" digunakan sebagai istilah untuk merujuk pada "aktivitas mengarungi sungai menggunakan perahu karet atau kendaraan serupa lainnya dengan awak dua orang atau lebih yang mengandalkan kekuatan mendayung".

          pengertian arung jeram dalam kompetensi ini adalah :

          Berdasarkan mediannya ; Dilakukan di sungai yang berarus,
          Berdasarkan sarananya ; Menggunakan perahu berbahan dasar karet (inflatable),
          Berdasarkan tenaga yang digunakan ; Mengandalkan kekuatan dan kemampuan fisik dalam
          mendayung, baik dayung tunggal, dayung ganda maupun oars.
          Berdasarkan jumlah awaknya ; Berawak dua orang atau lebih dimana salah seorang
          diantaranya bertindak sebagai pengemudi,
          Berdasarkan batasan-batasan diatas, maka kompetensi kepemanduan arung jeram secara spesifik ditujukan bagi kegiatan pemanduan wisata arung jeram sebagaimana terminologi arung jeram diatas.',
    ];
}

// Ambil data user
$stmt = $koneksi->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$error = isset($_GET['error']) ? $_GET['error'] : '';

$jarak_map = [
    'Fun Rafting' => 'Jarak 4 KM (~1 - 1,5 jam)',
    'Medium'      => 'Jarak 12 km (~2,5 - 3 jam)',
    'Long Trip'   => 'Jarak 15 km (~3 - 3,5 jam)',
];
$jarak = $jarak_map[$nama_paket] ?? 'Hubungi kami';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking - Rafting Singorojo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background-color: #f0eada; font-family: 'Poppins', Arial, sans-serif; }

    .wrapper { max-width: 1600px; margin: 30px auto; padding: 0 20px; }
    .container-content { display: flex; gap: 22px; align-items: flex-start; }

    /* KIRI */
    .left-section { flex: 2; display: flex; flex-direction: column; gap: 16px; }

    .paket-banner { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    .paket-banner-main {
      position: relative; border-radius: 12px; overflow: hidden; grid-row: 1 / 3;
    }
    .paket-banner-main img { width: 100%; height: 260px; object-fit: cover; display: block; }

    .paket-label {
      position: absolute; top: 0; left: 0;
      background: #2daae1; color: white;
      font-weight: 700; font-size: 18px;
      padding: 14px 18px; width: 100%;
    }

    .paket-banner-img2 { border-radius: 12px; overflow: hidden; }
    .paket-banner-img2 img { width: 100%; height: 180px; object-fit: cover; display: block; }

    .paket-info-boxes { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    .info-box {
      background: white; border-radius: 10px; border: 1.5px solid #ddd;
      text-align: center; padding: 14px 10px;
      font-size: 14px; font-weight: 600; color: #333; line-height: 1.4;
    }

    .deskripsi-box {
      background: white; border-radius: 14px; padding: 22px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }
    .deskripsi-box p {
      font-size: 13.5px; color: #555; line-height: 1.7;
      margin-bottom: 14px; text-align: justify;
    }
    .deskripsi-box p:last-child { margin-bottom: 0; }

    /* KANAN */
    .right-section {
      flex: 1; background: white; border-radius: 14px; padding: 18px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    .form-title {
      font-weight: 700; font-size: 15px; color: #333;
      text-align: center; margin-bottom: 14px;
    }

    .form-img {
      width: 100%; height: 150px; object-fit: cover;
      border-radius: 10px; margin-bottom: 16px;
    }

    .alert-error {
      background: #ffe5e5; border: 1px solid #f5c6c6; color: #c0392b;
      border-radius: 8px; padding: 8px 12px; font-size: 12px; margin-bottom: 12px;
    }

    .form-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    .form-table tr td { padding: 6px 4px; font-size: 13px; color: #444; vertical-align: middle; }
    .form-table tr td:first-child { font-weight: 600; white-space: nowrap; width: 100px; }
    .form-table tr td:nth-child(2) { width: 14px; color: #888; }

    .form-table input[type="date"],
    .form-table input[type="time"],
    .form-table input[type="tel"] {
      border: 1.5px solid #ddd; border-radius: 6px; padding: 5px 8px;
      font-size: 13px; width: 100%; font-family: 'Poppins', sans-serif; outline: none;
      transition: border 0.2s;
    }
    .form-table input:focus { border-color: #2daae1; }

    .counter { display: flex; align-items: center; gap: 10px; }
    .counter button {
      width: 26px; height: 26px; border-radius: 5px;
      border: 1.5px solid #ccc; background: #f5f5f5;
      font-size: 15px; font-weight: 700; cursor: pointer; line-height: 1;
      transition: background 0.2s;
    }
    .counter button:hover { background: #2daae1; color: white; border-color: #2daae1; }
    .counter span { font-weight: 700; font-size: 15px; min-width: 20px; text-align: center; }

    .divider { border: none; border-top: 1.5px solid #eee; margin: 10px 0; }

    .info-row { display: flex; justify-content: space-between; font-size: 12.5px; color: #666; margin-bottom: 5px; }

    .total-row {
      display: flex; justify-content: space-between; align-items: center;
      font-weight: 700; font-size: 14px; color: #222; margin-bottom: 14px;
    }
    .total-row .total-nominal { color: #2daae1; font-size: 15px; }

    .btn-booking {
      background: #3a7d44; color: white; border: none; padding: 12px;
      border-radius: 25px; width: 100%; font-weight: 700; font-size: 14px;
      cursor: pointer; letter-spacing: 1px; font-family: 'Poppins', sans-serif;
      transition: background 0.2s, transform 0.2s;
    }
    .btn-booking:hover { background: #2d6235; transform: translateY(-1px); }

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
      <div class="paket-banner">
        <div class="paket-banner-main">
          <div class="paket-label">PAKET <?= strtoupper(htmlspecialchars($paket['nama_paket'])) ?></div>
          <img src="../images/1.jpg" alt="Rafting">
        </div>
        <div class="paket-banner-img2">
          <img src="../images/7.jpg" alt="Rafting 2">
        </div>
        <div class="paket-info-boxes">
          <div class="info-box">Rp <?= number_format($paket['harga'], 0, ',', '.') ?><br>/pax</div>
          <div class="info-box"><?= $jarak ?></div>
        </div>
      </div>

      <div class="deskripsi-box">
        <p><?= nl2br(htmlspecialchars($paket['deskripsi'])) ?></p>
      </div>
    </div>

    <!-- KANAN -->
    <div class="right-section">
      <div class="form-title">FORM PEMESANAN</div>
      <img src="../images/6.jpg" alt="Preview" class="form-img">

      <?php if ($error): ?>
        <div class="alert-error">⚠ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="booking_proses.php" method="POST">
        <input type="hidden" name="id_paket"   value="<?= $paket['id_paket'] ?>">
        <input type="hidden" name="id_user"    value="<?= $id_user ?>">
        <input type="hidden" name="harga"      value="<?= $paket['harga'] ?>">
        <input type="hidden" name="nama_paket" value="<?= htmlspecialchars($paket['nama_paket']) ?>">

        <table class="form-table">
          <tr>
            <td>Paket</td><td>:</td>
            <td><strong><?= htmlspecialchars($paket['nama_paket']) ?></strong></td>
          </tr>
          <tr>
            <td>Nama</td><td>:</td>
            <td><strong><?= htmlspecialchars($user['nama']) ?></strong></td>
          </tr>
          <tr>
            <td>Tanggal</td><td>:</td>
            <td><input type="date" name="tanggal" required min="<?= date('Y-m-d') ?>"></td>
          </tr>
          <tr>
            <td>Jam</td><td>:</td>
            <td><input type="time" name="jam" required></td>
          </tr>
          <tr>
            <td>Orang</td><td>:</td>
            <td>
              <div class="counter">
                <button type="button" onclick="kurang()">-</button>
                <span id="jumlah">1</span>
                <button type="button" onclick="tambah()">+</button>
              </div>
              <input type="hidden" name="jumlah" id="input_jumlah" value="1">
            </td>
          </tr>
          <tr>
            <td>No. Telepon</td><td>:</td>
            <td><input type="tel" name="no_telp" placeholder="08xxxxxxxxxx"
                       value="<?= htmlspecialchars($user['no_hp']) ?>" required></td>
          </tr>
        </table>

        <hr class="divider">

        <div class="info-row">
          <span>Harga Satuan</span>
          <span>Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
        </div>
        <div class="info-row">
          <span>Jumlah Orang</span>
          <span><span id="show_orang">1</span> orang</span>
        </div>

        <hr class="divider">

        <div class="total-row">
          <span>TOTAL HARGA :</span>
          <span class="total-nominal" id="show_total">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
        </div>

        <button type="submit" class="btn-booking">BOOKING</button>
      </form>
    </div>

  </div>
</div>

<script>
  let jumlah = 1;
  const kapasitas = <?= (int)$paket['kapasitas'] ?>;
  const harga = <?= (int)$paket['harga'] ?>;

  function update() {
    document.getElementById('jumlah').textContent = jumlah;
    document.getElementById('input_jumlah').value = jumlah;
    document.getElementById('show_orang').textContent = jumlah;
    document.getElementById('show_total').textContent =
      'Rp ' + (jumlah * harga).toLocaleString('id-ID');
  }

  function tambah() {
    if (jumlah < kapasitas) { jumlah++; update(); }
    else alert('Maksimal ' + kapasitas + ' orang untuk paket ini!');
  }

  function kurang() {
    if (jumlah > 1) { jumlah--; update(); }
  }
</script>
</body>
</html>