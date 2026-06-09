<?php
include '../koneksi.php';
include 'header.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_booking = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_booking <= 0) { header("Location: paket.php"); exit; }

$sql = "SELECT 
            b.id_booking, 
            b.total_harga, 
            b.status,
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

if (!$data) { header("Location: paket.php"); exit; }

// Kalau sudah dikonfirmasi → redirect ke invoice
// Kalau sudah dikonfirmasi → redirect ke invoice
if ($data['status'] == '1' || $data['status'] == '2') {
    header("Location: invoice.php?id=$id_booking"); exit;
}

$dibatalkan = ($data['status'] == '3');

$dibatalkan  = ($data['status'] === 'batal');
$baru_konfirm = isset($_GET['notif']) && $_GET['notif'] === 'konfirmasi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Status Booking - Rafting Singorojo</title>
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

    .alasan-box {
      background: #fff5f5; border-left: 4px solid #e74c3c;
      border-radius: 6px; padding: 10px 14px;
      font-size: 12.5px; color: #742a2a; margin-top: 10px; line-height: 1.6;
    }

    /* Tombol */
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
    .btn-batal   { background: #e74c3c; color: white; flex: 0.8; }

    .refresh-note { text-align: center; font-size: 12px; color: #aaa; margin-top: 14px; }
    .refresh-note span { font-weight: 600; color: #2daae1; }

    /* ===== POPUP BATAL ===== */
    .overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,0.5); z-index: 100;
      align-items: center; justify-content: center;
    }
    .overlay.show { display: flex; }

    .popup {
      background: white; border-radius: 16px; padding: 28px 24px;
      width: 90%; max-width: 420px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
      animation: popIn 0.2s ease;
    }

    @keyframes popIn {
      from { transform: scale(0.9); opacity: 0; }
      to   { transform: scale(1);   opacity: 1; }
    }

    .popup h3 { font-size: 16px; font-weight: 700; color: #333; margin-bottom: 8px; }
    .popup p  { font-size: 13px; color: #666; margin-bottom: 16px; line-height: 1.5; }

    .popup textarea {
      width: 100%; border: 1.5px solid #ddd; border-radius: 8px;
      padding: 10px 12px; font-size: 13px; font-family: 'Poppins', sans-serif;
      resize: none; height: 90px; outline: none; transition: border 0.2s;
    }
    .popup textarea:focus { border-color: #e74c3c; }

    .popup-err { color: #e74c3c; font-size: 12px; margin-top: 4px; display: none; }

    .popup-btns { display: flex; gap: 10px; margin-top: 16px; }
    .popup-btns button {
      flex: 1; padding: 11px; border-radius: 25px; border: none;
      font-size: 13px; font-weight: 700; cursor: pointer;
      font-family: 'Poppins', sans-serif; transition: opacity 0.2s;
    }
    .popup-btns button:hover { opacity: 0.85; }
    .btn-cancel-no  { background: #f0f0f0; color: #555; }
    .btn-cancel-yes { background: #e74c3c; color: white; }

    /* ===== NOTIFIKASI KONFIRMASI ===== */
    .notif-bar {
      background: #d4edda; color: #155724;
      border: 1px solid #c3e6cb; border-radius: 10px;
      padding: 14px 18px; margin-bottom: 16px;
      display: flex; align-items: center; gap: 10px;
      font-size: 13px; font-weight: 600;
    }
    .notif-bar svg { width: 20px; height: 20px; stroke: #155724; fill: none; stroke-width: 2.5; flex-shrink: 0; }
  </style>

  <?php if (!$dibatalkan): ?>
  <script>
    let countdown = 15;
    function tick() {
      const el = document.getElementById('cd');
      if (el) el.textContent = countdown;
      if (countdown <= 0) window.location.reload();
      countdown--;
      setTimeout(tick, 1000);
    }
    window.onload = tick;
  </script>
  <?php endif; ?>
</head>
<body>

<!-- POPUP BATAL -->
<div class="overlay" id="popupBatal">
  <div class="popup">
    <h3>Batalkan Booking?</h3>
    <p>Apakah Anda yakin ingin membatalkan booking ini? Tindakan ini tidak dapat dikembalikan.</p>
    <form action="booking_batal.php" method="POST">
      <input type="hidden" name="id_booking" value="<?= $id_booking ?>">
      <div class="popup-btns">
        <button type="button" class="btn-cancel-no" onclick="tutupPopup()">Tidak, Kembali</button>
        <button type="submit" class="btn-cancel-yes">Ya, Batalkan</button>
      </div>
    </form>
  </div>
</div>

<div class="wrapper">
  <div class="card">

    <!-- TOP -->
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
        <p>Booking Anda telah dibatalkan.<br>Silakan hubungi kami untuk informasi lebih lanjut.</p>
      <?php else: ?>
        <h2>Menunggu Konfirmasi</h2>
        <p>Booking Anda sudah masuk!<br>Kami akan segera mengkonfirmasi pesanan Anda.</p>
      <?php endif; ?>
      <div class="no-booking">#<?= str_pad($data['id_booking'], 5, '0', STR_PAD_LEFT) ?></div>
    </div>

    <!-- BODY -->
    <div class="card-body">

      <?php if ($baru_konfirm): ?>
      <div class="notif-bar">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        Selamat! Booking Anda telah dikonfirmasi. Silakan lihat invoice Anda.
      </div>
      <?php endif; ?>

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
        <div class="batal-box">✕ Booking ini telah dibatalkan.</div>
        <?php if (!empty($data['alasan_batal'])): ?>
          <div class="alasan-box">
            <strong>Alasan:</strong> <?= htmlspecialchars($data['alasan_batal']) ?>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="info-box">⏳ Halaman ini otomatis cek status setiap 15 detik. Setelah admin mengkonfirmasi, Anda langsung diarahkan ke invoice.</div>
      <?php endif; ?>

      <!-- Tombol -->
      <div class="btn-wrap">
        <a href="cek_booking.php" class="btn btn-home">Ke Riwayat Booking</a>
        <?php if (!$dibatalkan): ?>
          <a href="booking_tunggu.php?id=<?= $id_booking ?>" class="btn btn-refresh">↻ Refresh</a>
          <button class="btn btn-batal" onclick="bukaPopup()">✕ Batalkan</button>
        <?php endif; ?>
      </div>

      <?php if (!$dibatalkan): ?>
        <div class="refresh-note">Otomatis cek ulang dalam <span id="cd">15</span> detik...</div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script>
  function bukaPopup() {
    document.getElementById('popupBatal').classList.add('show');
  }

  function tutupPopup() {
    document.getElementById('popupBatal').classList.remove('show');
    
  }

  function konfirmasiBatal() {
    
    document.getElementById('alasanInput').closest('form').submit();
  }
</script>
</body>
</html>