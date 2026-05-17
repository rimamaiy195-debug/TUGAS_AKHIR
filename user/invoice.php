<?php
include '../koneksi.php';
include '../header.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_booking = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_booking <= 0) { header("Location: ../paket.php"); exit; }

$sql = "SELECT 
            b.id_booking, b.total_harga, b.status,
            u.nama, u.email, u.no_hp, u.alamat,
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

// Hanya bisa akses kalau sudah dikonfirmasi
if ($data['status'] === 'pending') {
    header("Location: booking_tunggu.php?id=$id_booking"); exit;
}
if ($data['status'] === 'batal') {
    header("Location: booking_tunggu.php?id=$id_booking"); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice - Rafting Singorojo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0eada; font-family: 'Poppins', sans-serif; }

    .wrapper { max-width: 700px; margin: 36px auto; padding: 0 20px 60px; }

    .action-bar { display: flex; gap: 10px; margin-bottom: 18px; justify-content: flex-end; }
    .btn {
      padding: 10px 24px; border-radius: 25px; border: none;
      font-size: 13px; font-weight: 700; cursor: pointer;
      font-family: 'Poppins', sans-serif; text-decoration: none;
      display: inline-flex; align-items: center; gap: 6px; transition: opacity 0.2s;
    }
    .btn:hover { opacity: 0.85; }
    .btn-home  { background: #e0e0e0; color: #444; }
    .btn-print { background: #3a7d44; color: white; }

    .invoice-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 6px 28px rgba(0,0,0,0.11); }

    .inv-header {
      background: #2daae1; color: white; padding: 26px 32px;
      display: flex; justify-content: space-between; align-items: center;
    }
    .inv-header .brand { font-size: 19px; font-weight: 700; line-height: 1.3; }
    .inv-header .brand small { display: block; font-size: 11.5px; font-weight: 400; opacity: 0.85; margin-top: 2px; }
    .inv-header .inv-meta { text-align: right; }
    .inv-header .inv-meta .inv-no { font-size: 22px; font-weight: 700; }
    .inv-header .inv-meta small { display: block; font-size: 11px; opacity: 0.85; margin-bottom: 2px; }

    .confirmed-strip {
      background: #d4edda; color: #155724; padding: 10px 32px;
      font-size: 13px; font-weight: 600;
      display: flex; align-items: center; gap: 8px;
      border-bottom: 1px solid #c3e6cb;
    }
    .confirmed-strip svg { width: 16px; height: 16px; stroke: #155724; fill: none; stroke-width: 2.5; }

    .print-date {
      padding: 8px 32px; font-size: 11.5px; color: #aaa;
      border-bottom: 1px solid #f0f0f0;
      display: flex; justify-content: space-between;
    }

    .inv-body { padding: 26px 32px; }

    .section-label {
      font-size: 10.5px; font-weight: 700; color: #bbb;
      letter-spacing: 1.2px; text-transform: uppercase;
      margin-bottom: 10px; margin-top: 22px;
    }
    .section-label:first-child { margin-top: 0; }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; }
    .info-item .lbl { color: #999; font-size: 11.5px; margin-bottom: 2px; }
    .info-item .val { color: #222; font-weight: 600; font-size: 13px; }

    .divider { border: none; border-top: 1.5px solid #f0f0f0; margin: 20px 0; }

    .detail-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .detail-table thead th {
      background: #f7f7f7; padding: 10px 12px; text-align: left;
      font-size: 11.5px; color: #888; font-weight: 600; border-bottom: 2px solid #eee;
    }
    .detail-table thead th:last-child { text-align: right; }
    .detail-table tbody td { padding: 13px 12px; color: #444; border-bottom: 1px solid #f5f5f5; }
    .detail-table tbody td:last-child { text-align: right; font-weight: 700; color: #222; }

    .total-section { margin-top: 16px; }
    .total-line { display: flex; justify-content: space-between; font-size: 13px; color: #666; padding: 5px 0; }
    .total-final {
      display: flex; justify-content: space-between; align-items: center;
      font-size: 17px; font-weight: 700; color: #2daae1;
      border-top: 2px solid #2daae1; margin-top: 10px; padding-top: 12px;
    }

    .note-box {
      background: #f0f9ff; border-left: 4px solid #2daae1;
      border-radius: 6px; padding: 12px 16px;
      font-size: 12px; color: #555; line-height: 1.6; margin-top: 22px;
    }

    .inv-footer {
      background: #f9f9f9; border-top: 1px solid #eee; padding: 16px 32px;
      text-align: center; font-size: 12px; color: #aaa; line-height: 1.8;
    }
    .inv-footer strong { color: #777; }

    @media print {
      body { background: white; }
      .action-bar { display: none !important; }
      .wrapper { margin: 0; max-width: 100%; padding: 0; }
      .invoice-card { box-shadow: none; border-radius: 0; }
    }
  </style>
</head>
<body>

<div class="wrapper">

  <div class="action-bar">
    <a href="../index.php" class="btn btn-home">← Kembali ke Home</a>
    <button class="btn btn-print" onclick="window.print()">🖨 Cetak Invoice</button>
  </div>

  <div class="invoice-card">

    <div class="inv-header">
      <div class="brand">
        Rafting Singorojo
        <small>Jl. Singorojo, Kendal, Jawa Tengah</small>
        <small>0812-XXXX-XXXX</small>
      </div>
      <div class="inv-meta">
        <small>No. Invoice</small>
        <div class="inv-no">#<?= str_pad($data['id_booking'], 5, '0', STR_PAD_LEFT) ?></div>
      </div>
    </div>

    <div class="confirmed-strip">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      Booking Dikonfirmasi — Siap berangkat!
    </div>

    <div class="print-date">
      <span>Tanggal Cetak: <?= date('d F Y, H:i') ?> WIB</span>
      <span>Status: <strong><?= ucfirst($data['status']) ?></strong></span>
    </div>

    <div class="inv-body">

      <div class="section-label">Data Pemesan</div>
      <div class="info-grid">
        <div class="info-item">
          <div class="lbl">Nama Lengkap</div>
          <div class="val"><?= htmlspecialchars($data['nama']) ?></div>
        </div>
        <div class="info-item">
          <div class="lbl">No. HP</div>
          <div class="val"><?= htmlspecialchars($data['no_hp']) ?></div>
        </div>
        <div class="info-item">
          <div class="lbl">Email</div>
          <div class="val"><?= htmlspecialchars($data['email']) ?></div>
        </div>
        <?php if (!empty($data['alamat'])): ?>
        <div class="info-item">
          <div class="lbl">Alamat</div>
          <div class="val"><?= htmlspecialchars($data['alamat']) ?></div>
        </div>
        <?php endif; ?>
      </div>

      <hr class="divider">

      <div class="section-label">Jadwal Kegiatan</div>
      <div class="info-grid">
        <div class="info-item">
          <div class="lbl">Tanggal</div>
          <div class="val"><?= date('d F Y', strtotime($data['tanggal'])) ?></div>
        </div>
        <div class="info-item">
          <div class="lbl">Jam</div>
          <div class="val"><?= date('H:i', strtotime($data['jam'])) ?> WIB</div>
        </div>
        <div class="info-item">
          <div class="lbl">Jumlah Peserta</div>
          <div class="val"><?= $data['jumlah'] ?> orang</div>
        </div>
        <div class="info-item">
          <div class="lbl">Lokasi</div>
          <div class="val">Sungai Bodri, Singorojo</div>
        </div>
      </div>

      <hr class="divider">

      <div class="section-label">Detail Paket</div>
      <table class="detail-table">
        <thead>
          <tr>
            <th>Nama Paket</th>
            <th>Harga / pax</th>
            <th>Jumlah Peserta</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?= htmlspecialchars($data['nama_paket']) ?></td>
            <td>Rp <?= number_format($data['harga'], 0, ',', '.') ?></td>
            <td><?= $data['jumlah'] ?> orang</td>
            <td>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></td>
          </tr>
        </tbody>
      </table>

      <div class="total-section">
        <div class="total-line">
          <span>Harga Satuan</span>
          <span>Rp <?= number_format($data['harga'], 0, ',', '.') ?></span>
        </div>
        <div class="total-line">
          <span>Jumlah Peserta</span>
          <span><?= $data['jumlah'] ?> orang</span>
        </div>
        <div class="total-final">
          <span>TOTAL HARGA</span>
          <span>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></span>
        </div>
      </div>

      <div class="note-box">
        📌 <strong>Catatan:</strong> Harap tiba 15 menit sebelum jadwal. Bawa pakaian ganti dan alas kaki yang tidak mudah lepas. Semua peralatan rafting sudah disediakan. Info: <strong>0812-XXXX-XXXX</strong>
      </div>

    </div>

    <div class="inv-footer">
      Terima kasih telah memilih <strong>Rafting Singorojo</strong>!<br>
      Invoice ini sah sebagai bukti booking yang telah dikonfirmasi.
    </div>

  </div>
</div>
</body>
</html>