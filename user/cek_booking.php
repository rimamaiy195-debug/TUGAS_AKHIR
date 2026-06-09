<?php
include '../koneksi.php';
include 'header.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user = (int)$_SESSION['id_user'];

$sql = "SELECT 
            b.id_booking, b.total_harga, b.status, 
            p.nama_paket, p.harga,
            j.tanggal, j.jam, j.jumlah
        FROM booking b
        JOIN paket p  ON b.id_paket  = p.id_paket
        JOIN jadwal j ON b.id_jadwal = j.id_jadwal
        WHERE b.id_user = ?
        ORDER BY b.id_booking DESC";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$kapasitas_harian = 2;
$cek_full = $koneksi->query("
    SELECT j.tanggal, COUNT(*) AS total
    FROM booking b
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    WHERE b.status != 3
    GROUP BY j.tanggal
");
$full_data = [];
while ($r = $cek_full->fetch_assoc()) {
    $full_data[$r['tanggal']] = (int)$r['total'];
}

function isHariPenuh($tanggal, $full_data, $kapasitas) {
    return ($full_data[$tanggal] ?? 0) >= $kapasitas;
}

function badgeStyle($s) {
    $map = [
        '0' => 'background:#fff3cd;color:#856404;',
        '1' => 'background:#d1ecf1;color:#0c5460;',
        '2' => 'background:#d4edda;color:#155724;',
        '3' => 'background:#f8d7da;color:#721c24;',
        '4' => 'background:#ede9fe;color:#5b21b6;',
    ];
    return $map[(string)$s] ?? 'background:#eee;color:#555;';
}

function badgeLabel($s) {
    $map = [
        '0' => '⏳ Menunggu Konfirmasi',
        '1' => '✓ Diterima',
        '2' => '✔ Selesai',
        '3' => '✕ Dibatalkan',
        '4' => '💰 Lunas',
    ];
    return $map[(string)$s] ?? 'Status ' . $s;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Riwayat Booking - Rafting Singorojo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0eada; font-family: 'Poppins', sans-serif; }

    .wrapper { max-width: 900px; margin: 32px auto; padding: 0 20px 60px; }

    .page-title { font-size: 20px; font-weight: 700; color: #333; margin-bottom: 20px; }
    .page-title small { display: block; font-size: 13px; color: #aaa; font-weight: 400; margin-top: 2px; }

    .booking-list { display: flex; flex-direction: column; gap: 14px; }

    .booking-item {
      background: white; border-radius: 14px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.08);
      overflow: hidden; transition: transform 0.2s;
    }
    .booking-item:hover { transform: translateY(-2px); }

    .item-header {
      background: #2daae1; color: white;
      padding: 12px 20px;
      display: flex; justify-content: space-between; align-items: center;
    }
    .item-header .no { font-weight: 700; font-size: 15px; }
    .item-header .tgl { font-size: 12px; opacity: 0.9; }

    .item-body { padding: 16px 20px; }

    .item-grid {
      display: grid; grid-template-columns: 1fr 1fr 1fr 1fr;
      gap: 12px; margin-bottom: 14px; align-items: center;
    }

    .item-info .lbl { font-size: 11px; color: #aaa; font-weight: 600; text-transform: uppercase; margin-bottom: 3px; }
    .item-info .val { font-size: 14px; font-weight: 600; color: #222; }

    .status-inline {
      display: inline-block;
      padding: 5px 12px; border-radius: 20px;
      font-size: 12px; font-weight: 700;
    }

    .item-footer {
      display: flex; justify-content: space-between; align-items: center;
      padding-top: 12px; border-top: 1px solid #f0f0f0;
      flex-wrap: wrap; gap: 10px;
    }

    .total { font-size: 17px; font-weight: 700; color: #2daae1; }

    .btn-wrap { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn {
      padding: 8px 18px; border-radius: 20px; border: none;
      font-size: 12px; font-weight: 700; cursor: pointer;
      font-family: 'Poppins', sans-serif; text-decoration: none;
      display: inline-block; transition: opacity 0.2s;
    }
    .btn:hover { opacity: 0.85; }
    .btn-cek      { background: #2daae1; color: white; }
    .btn-invoice  { background: #3a7d44; color: white; }
    .btn-bayar    { background: #f6ad55; color: white; animation: glow 1.5s infinite; }
    .btn-reschedule { background: #f97316; color: white; }

    @keyframes glow {
      0%,100% { box-shadow: 0 0 0 0 rgba(246,173,85,0.4); }
      50%      { box-shadow: 0 0 0 8px rgba(246,173,85,0); }
    }

    .notif-konfirm {
      background: #d4edda; border: 1px solid #c3e6cb;
      border-radius: 10px; padding: 12px 16px;
      font-size: 13px; color: #155724; font-weight: 600;
      margin-bottom: 10px;
      display: flex; align-items: center; gap: 8px;
    }

    /* Notif HARI PENUH */
    .notif-penuh {
      background: #fff1f1;
      border: 1.5px solid #f87171;
      border-radius: 10px;
      padding: 13px 16px;
      margin-bottom: 12px;
      display: flex; align-items: flex-start; gap: 10px;
    }
    .notif-penuh .icon { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
    .notif-penuh .teks { flex: 1; }
    .notif-penuh .judul { font-size: 13px; font-weight: 700; color: #b91c1c; margin-bottom: 3px; }
    .notif-penuh .sub { font-size: 12px; color: #ef4444; font-weight: 500; line-height: 1.5; }
    .notif-penuh .btn-ganti {
      margin-top: 8px; display: inline-block;
      background: #ef4444; color: white;
      padding: 6px 14px; border-radius: 20px;
      font-size: 11px; font-weight: 700;
      text-decoration: none; transition: opacity .2s;
    }
    .notif-penuh .btn-ganti:hover { opacity: .85; }

    .alasan-box {
      background: #fff5f5; border-left: 4px solid #e74c3c;
      border-radius: 6px; padding: 8px 12px;
      font-size: 12px; color: #742a2a; margin-top: 10px; line-height: 1.5;
    }

    .empty {
      text-align: center; padding: 60px 20px; color: #bbb;
      background: white; border-radius: 14px;
    }
    .empty svg { width: 60px; height: 60px; stroke: #ddd; margin-bottom: 14px; }
    .empty p { font-size: 14px; margin-bottom: 16px; }
    .btn-pesan {
      background: #2daae1; color: white; padding: 11px 28px;
      border-radius: 25px; text-decoration: none;
      font-weight: 700; font-size: 13px; display: inline-block;
    }

    @media (max-width: 600px) {
      .item-grid { grid-template-columns: 1fr 1fr; }
    }
  </style>
</head>
<body>

<div class="wrapper">
  <div class="page-title">
    Riwayat Booking Saya
    <small>Pantau status semua pemesanan Anda</small>
  </div>

  <div class="booking-list">
    <?php if ($result && $result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
        $hari_penuh = isHariPenuh($row['tanggal'], $full_data, $kapasitas_harian);
        $tgl_fmt    = date('d F Y', strtotime($row['tanggal']));
        $status     = (int)$row['status'];
    ?>
      <div class="booking-item">
        <div class="item-header">
          <span class="no">#<?= str_pad($row['id_booking'], 5, '0', STR_PAD_LEFT) ?></span>
          <span class="tgl"><?= $tgl_fmt ?> · <?= date('H:i', strtotime($row['jam'])) ?> WIB</span>
        </div>
        <div class="item-body">

          <?php if ($hari_penuh && !in_array($status, [2, 3])): ?>
            <div class="notif-penuh">
              <div class="icon">🚫</div>
              <div class="teks">
                <div class="judul">Hari Penuh!</div>
                <div class="sub">
                  Kuota rafting pada <strong><?= $tgl_fmt ?></strong> sudah penuh.<br>
                  Silakan pilih tanggal lain — data booking kamu akan tetap tersimpan.
                </div>
                <a href="reschedule.php?id=<?= $row['id_booking'] ?>" class="btn-ganti">
                  📅 Ganti Tanggal
                </a>
              </div>
            </div>
          <?php endif; ?>

          <?php if ($status == 1 && !$hari_penuh): ?>
            <div class="notif-konfirm" style="background:#fff3cd;border-color:#ffc107;color:#856404;">
              💳 Booking Anda telah diterima! Mohon segera lakukan pembayaran.
            </div>
          <?php endif; ?>

          <div class="item-grid">
            <div class="item-info">
              <div class="lbl">Paket</div>
              <div class="val"><?= htmlspecialchars($row['nama_paket']) ?></div>
            </div>
            <div class="item-info">
              <div class="lbl">Peserta</div>
              <div class="val"><?= $row['jumlah'] ?> orang</div>
            </div>
            <div class="item-info">
              <div class="lbl">Harga/pax</div>
              <div class="val">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>
            </div>
            <div class="item-info">
              <div class="lbl">Status</div>
              <div class="val">
                <span class="status-inline" style="<?= badgeStyle($status) ?>">
                  <?= badgeLabel($status) ?>
                </span>
              </div>
            </div>
          </div>

          <?php if ($status == 3 && !empty($row['alasan_batal'])): ?>
            <div class="alasan-box">
              <strong>Alasan:</strong> <?= htmlspecialchars($row['alasan_batal']) ?>
            </div>
          <?php endif; ?>

          <div class="item-footer">
            <div class="total">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></div>
            <div class="btn-wrap">
              <?php if ($status == 0): ?>
                <a href="booking_tunggu.php?id=<?= $row['id_booking'] ?>" class="btn btn-cek">⏳ Cek Status</a>
              <?php elseif ($status == 1 && !$hari_penuh): ?>
                <a href="transaksi.php?id=<?= $row['id_booking'] ?>" class="btn btn-bayar">💳 Bayar Sekarang</a>
                <a href="invoice.php?id=<?= $row['id_booking'] ?>" class="btn btn-invoice">🧾 Invoice</a>
              <?php elseif ($status == 4): ?>
                <a href="invoice.php?id=<?= $row['id_booking'] ?>" class="btn btn-invoice">🧾 Invoice</a>
              <?php elseif ($status == 2): ?>
                <a href="invoice.php?id=<?= $row['id_booking'] ?>" class="btn btn-invoice">🧾 Invoice</a>
              <?php endif; ?>
            </div>
          </div>

        </div>
      </div>
    <?php endwhile; else: ?>
      <div class="empty">
        <svg fill="none" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
        </svg>
        <p>Anda belum memiliki riwayat booking.</p>
        <a href="paket.php" class="btn-pesan">Pesan Sekarang</a>
      </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>