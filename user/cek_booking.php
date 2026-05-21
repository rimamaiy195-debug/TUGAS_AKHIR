<?php
include '../koneksi.php';
include 'header.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user = (int)$_SESSION['id_user'];

// Ambil semua booking milik user ini
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

function badgeStyle($s) {
    $map = [
        'pending'    => 'background:#fff3cd;color:#856404;',
        'konfirmasi' => 'background:#d1ecf1;color:#0c5460;',
        'selesai'    => 'background:#d4edda;color:#155724;',
        'batal'      => 'background:#f8d7da;color:#721c24;',
    ];
    return $map[$s] ?? 'background:#eee;color:#555;';
}

function badgeLabel($s) {
    $map = [
        'pending'    => '⏳ Menunggu',
        'konfirmasi' => '✓ Dikonfirmasi',
        'selesai'    => '✔ Selesai',
        'batal'      => '✕ Dibatalkan',
    ];
    return $map[$s] ?? ucfirst($s);
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
      display: grid; grid-template-columns: 1fr 1fr 1fr;
      gap: 12px; margin-bottom: 14px;
    }

    .item-info .lbl { font-size: 11px; color: #aaa; font-weight: 600; text-transform: uppercase; margin-bottom: 3px; }
    .item-info .val { font-size: 14px; font-weight: 600; color: #222; }

    .item-footer {
      display: flex; justify-content: space-between; align-items: center;
      padding-top: 12px; border-top: 1px solid #f0f0f0;
    }

    .badge {
      padding: 5px 14px; border-radius: 20px;
      font-size: 12px; font-weight: 700;
    }

    .total { font-size: 16px; font-weight: 700; color: #2daae1; }

    .btn-wrap { display: flex; gap: 8px; }
    .btn {
      padding: 8px 18px; border-radius: 20px; border: none;
      font-size: 12px; font-weight: 700; cursor: pointer;
      font-family: 'Poppins', sans-serif; text-decoration: none;
      display: inline-block; transition: opacity 0.2s;
    }
    .btn:hover { opacity: 0.85; }
    .btn-cek     { background: #2daae1; color: white; }
    .btn-invoice { background: #3a7d44; color: white; }

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
    .btn-pesan { background: #2daae1; color: white; padding: 11px 28px; border-radius: 25px; text-decoration: none; font-weight: 700; font-size: 13px; display: inline-block; }

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
    ?>
      <div class="booking-item">
        <div class="item-header">
          <span class="no">#<?= str_pad($row['id_booking'], 5, '0', STR_PAD_LEFT) ?></span>
          <span class="tgl"><?= date('d F Y', strtotime($row['tanggal'])) ?> · <?= date('H:i', strtotime($row['jam'])) ?> WIB</span>
        </div>
        <div class="item-body">
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
          </div>

          <?php if ($row['status'] === 'batal' && !empty($row['alasan_batal'])): ?>
            <div class="alasan-box">
              <strong>Alasan Batal:</strong> <?= htmlspecialchars($row['alasan_batal']) ?>
            </div>
          <?php endif; ?>

          <div class="item-footer">
            <div>
              <span class="badge" style="<?= badgeStyle($row['status']) ?>">
                <?= badgeLabel($row['status']) ?>
              </span>
              <div class="total" style="margin-top:6px;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></div>
            </div>
            <div class="btn-wrap">
              <?php if ($row['status'] === 'pending'): ?>
                <a href="booking_tunggu.php?id=<?= $row['id_booking'] ?>" class="btn btn-cek">Cek Status</a>
              <?php elseif (in_array($row['status'], ['konfirmasi','selesai'])): ?>
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