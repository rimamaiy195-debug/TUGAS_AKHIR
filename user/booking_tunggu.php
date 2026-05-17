<?php
session_start();
include '../koneksi.php';
include '../header.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_booking = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_booking <= 0) { header("Location: ../paket.php"); exit; }

$sql = "SELECT 
            b.id_booking, b.total_harga, b.status,
            u.nama, u.no_hp,
            p.nama_paket, p.harga,
            j.tanggal, j.jam, j.jumlah
        FROM booking b
        JOIN user u   ON b.id_user   = u.id_user
        JOIN paket p  ON b.id_paket  = p.id_paket
        JOIN jadwal j ON b.id_jadwal = j.id_jadwal
        WHERE b.id_booking = ? AND b.id_user = ?";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("ii", $id_booking, $_SESSION['id_user']);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) { header("Location: ../paket.php"); exit; }

// Kalau sudah dikonfirmasi → langsung ke invoice
if ($data['status'] === 'konfirmasi' || $data['status'] === 'selesai') {
    header("Location: invoice.php?id=$id_booking"); exit;
}

$dibatalkan = ($data['status'] === 'batal');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menunggu Konfirmasi - Rafting Singorojo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0eada; font-family: 'Poppins', sans-serif; }

    .wrapper { max-width: 620px; margin: 40px auto; padding: 0 20px 60px; }

    .card { background: white; border-radius: 18px; box-shadow: 0 6px 28px rgba(0,0,0,0.1); overflow: hidden; }

    .card-top {
      background: <?= $dibatalkan ? '#e74c3c' : '#2daae1' ?>;
      padding: 30px 28px; text-align: center; color: white;
    }

    .icon-wrap {
      width: 80px; height: 80px; border-radius: 50%;
      background: rgba(255,255,255,0.2);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 16px;
      <?= !$dibatalkan ? 'animation: pulse 1.8s infinite;' : '' ?>
    }

    @keyframes pulse {
      0%   { box-shadow: 0 0 0 0 rgba(255,255,255,0.5); }
      70%  { box-shadow: 0 0 0 18px rgba(255,255,255,0); }
      100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
    }

    .icon-wrap svg { width: 40px; height: 40px; stroke: white; fill: none; stroke-width: 2; }

    .card-top h2 { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
    .card-top p  { font-size: 13px; opacity: 0.9; line-height: 1.5; }

    .no-booking {
      background: rgba(255,255,255,0.18); display: inline-block;
      padding: 4px 16px; border-radius: 20px;
      font-size: 13px; font-weight: 600; margin-top: 10px;
    }

    .card-body { padding: 24px 28px; }

    .section-label {
      font-size: 11px; font-weight: 700; color: #aaa;
      letter-spacing: 1px; text-transform: uppercase;
      margin-bottom: 10px; margin-top: 20px;
    }
    .section-label:first-child { margin-top: 0; }

    .detail-row {
      display: flex; justify-content: space-between;
      padding: 9px 0; border-bottom: 1px solid #f2f2f2;
      font-size: 13.5px; color: #444;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-row .label { color: #888; font-weight: 500; }
    .detail-row .value { font-weight: 600; color: #222; text-align: right; }

    .total-box {
      background: #f0f9ff; border: 1.5px solid #bee3f8;
      border-radius: 10px; padding: 14px 18px;
      display: flex; justify-content: space-between; align-items: center;
      margin-top: 16px;
    }
    .total-box .total-label { font-size: 14px; font-weight: 700; color: #333; }
    .total-box .total-nominal { font-size: 20px; font-weight: 700; color: #2daae1; }

    .info-box {
      background: #fffbea; border: 1px solid #f6e05e;
      border-radius: 10px; padding: 12px 16px;
      font-size: 12.5px; color: #744210; margin-top: 20px; line-height: 1.6;
    }
    .batal-box {
      background: #fff5f5; border: 1px solid #feb2b2;
      border-radius: 10px; padding: 12px 16px;
      font-size: 12.5px; color: #742a2a; margin-top: 20px; line-height: 1.6;
    }

    .btn-wrap { display: flex; gap: 10px; margin-top: 22px; }
    .btn {
      flex: 1; padding: 12px; border-radius: 25px; border: none;
      font-size: 13px; font-weight: 700; cursor: pointer;
      font-family: 'Poppins', sans-serif; text-align: center;
      text-decoration: none; display: block; transition: opacity 0.2s;
    }
    .btn:hover { opacity: 0.85; }
    .btn-home    { background: #2daae1; color: white; }
    .btn-refresh { background: #f0f0f0; color: #555; }

    .refresh-note { text-align: center; font-size: 12px; color: #aaa; margin-top: 14px; }
    .refresh-note span { font-weight: 600; color: #2daae1; }
  </style>
  <?php if (!$dibatalkan): ?>
  <script>
    let countdown = 15;
    function tick() {
      document.getElementById('cd').textContent = countdown;
      if (countdown <= 0) window.location.reload();
      countdown--;
      setTimeout(tick, 1000);
    }
    window.onload = tick;
  </script>
  <?php endif; ?>
</head>
<body>

<div class="wrapper">
  <div class="card">

    <div class="card-top">
      <div class="icon-wrap">
        <?php if ($dibatalkan): ?>
          <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        <?php else: ?>
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <?php endif; ?>
      </div>
      <?php if ($dibatalkan): ?>
        <h2>Booking Dibatalkan</h2>
        <p>Maaf, booking Anda telah dibatalkan.<br>Silakan hubungi kami untuk informasi lebih lanjut.</p>
      <?php else: ?>
        <h2>Menunggu Konfirmasi</h2>
        <p>Booking Anda sudah masuk!<br>Kami akan segera mengkonfirmasi pesanan Anda.</p>
      <?php endif; ?>
      <div class="no-booking">#<?= str_pad($data['id_booking'], 5, '0', STR_PAD_LEFT) ?></div>
    </div>

    <div class="card-body">

      <div class="section-label">Data Pemesan</div>
      <div class="detail-row">
        <span class="label">Nama</span>
        <span class="value"><?= htmlspecialchars($data['nama']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">No. HP</span>
        <span class="value"><?= htmlspecialchars($data['no_hp']) ?></span>
      </div>

      <div class="section-label">Detail Booking</div>
      <div class="detail-row">
        <span class="label">Paket</span>
        <span class="value"><?= htmlspecialchars($data['nama_paket']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Tanggal</span>
        <span class="value"><?= date('d F Y', strtotime($data['tanggal'])) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Jam</span>
        <span class="value"><?= date('H:i', strtotime($data['jam'])) ?> WIB</span>
      </div>
      <div class="detail-row">
        <span class="label">Jumlah Peserta</span>
        <span class="value"><?= $data['jumlah'] ?> orang</span>
      </div>
      <div class="detail-row">
        <span class="label">Harga / pax</span>
        <span class="value">Rp <?= number_format($data['harga'], 0, ',', '.') ?></span>
      </div>

      <div class="total-box">
        <span class="total-label">Total Harga</span>
        <span class="total-nominal">Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></span>
      </div>

      <?php if ($dibatalkan): ?>
        <div class="batal-box">✕ Booking ini telah dibatalkan. Hubungi kami untuk informasi lebih lanjut.</div>
      <?php else: ?>
        <div class="info-box">⏳ Halaman ini otomatis cek status setiap 15 detik. Setelah admin mengkonfirmasi, Anda langsung diarahkan ke invoice.</div>
      <?php endif; ?>

      <div class="btn-wrap">
        <a href="../index.php" class="btn btn-home">← Kembali ke Home</a>
        <?php if (!$dibatalkan): ?>
          <a href="booking_tunggu.php?id=<?= $id_booking ?>" class="btn btn-refresh">↻ Refresh</a>
        <?php endif; ?>
      </div>

      <?php if (!$dibatalkan): ?>
        <div class="refresh-note">Otomatis cek ulang dalam <span id="cd">15</span> detik...</div>
      <?php endif; ?>

    </div>
  </div>
</div>
</body>
</html>