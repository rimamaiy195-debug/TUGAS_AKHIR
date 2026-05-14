<?php
include '../koneksi.php';
include 'header.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_booking = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_booking <= 0) {
    header("Location: paket.php");
    exit;
}

// Ambil semua data booking + join tabel lain
$sql = "SELECT 
            b.id_booking,
            b.total_harga,
            b.status,
            u.nama,
            u.email,
            u.no_hp,
            p.nama_paket,
            p.harga,
            p.kapasitas,
            j.tanggal,
            j.jam,
            j.jumlah
        FROM booking b
        JOIN user u    ON b.id_user   = u.id_user
        JOIN paket p   ON b.id_paket  = p.id_paket
        JOIN jadwal j  ON b.id_jadwal = j.id_jadwal
        WHERE b.id_booking = ? AND b.id_user = ?";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("ii", $id_booking, $_SESSION['id_user']);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    header("Location: paket.php");
    exit;
}

// Badge warna status
$badge = [
    'pending'    => ['bg' => '#fff3cd', 'color' => '#856404', 'label' => 'Menunggu Konfirmasi'],
    'konfirmasi' => ['bg' => '#d1ecf1', 'color' => '#0c5460', 'label' => 'Dikonfirmasi'],
    'selesai'    => ['bg' => '#d4edda', 'color' => '#155724', 'label' => 'Selesai'],
    'batal'      => ['bg' => '#f8d7da', 'color' => '#721c24', 'label' => 'Dibatalkan'],
];
$s = $badge[$data['status']] ?? $badge['pending'];
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

    .wrapper { max-width: 680px; margin: 40px auto; padding: 0 20px 40px; }

    /* Invoice card */
    .invoice-card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 6px 24px rgba(0,0,0,0.1);
    }

    /* Header invoice */
    .inv-header {
      background: #2daae1;
      color: white;
      padding: 24px 28px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .inv-header .brand { font-size: 20px; font-weight: 700; line-height: 1.3; }
    .inv-header .brand small { display: block; font-size: 12px; font-weight: 400; opacity: 0.85; }

    .inv-header .inv-no { text-align: right; }
    .inv-header .inv-no span { display: block; font-size: 12px; opacity: 0.85; }
    .inv-header .inv-no strong { font-size: 18px; }

    /* Status badge */
    .status-bar {
      padding: 10px 28px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid #eee;
    }

    .badge {
      padding: 4px 14px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      background: <?= $s['bg'] ?>;
      color: <?= $s['color'] ?>;
    }

    /* Body */
    .inv-body { padding: 24px 28px; }

    .section-label {
      font-size: 11px;
      font-weight: 700;
      color: #aaa;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 10px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 6px 20px;
      margin-bottom: 22px;
    }

    .info-item { font-size: 13px; color: #555; }
    .info-item strong { display: block; color: #222; font-size: 14px; }

    /* Tabel detail */
    .detail-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 6px;
      font-size: 13px;
    }

    .detail-table thead th {
      background: #f5f5f5;
      padding: 10px 12px;
      text-align: left;
      font-size: 12px;
      color: #777;
      font-weight: 600;
    }

    .detail-table tbody td {
      padding: 12px;
      border-bottom: 1px solid #f0f0f0;
      color: #444;
    }

    .detail-table tbody td:last-child { text-align: right; font-weight: 600; }

    /* Total */
    .total-box {
      background: #f8f8f8;
      border-radius: 10px;
      padding: 14px 16px;
      margin-top: 16px;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: #555;
      margin-bottom: 6px;
    }

    .total-final {
      display: flex;
      justify-content: space-between;
      font-size: 16px;
      font-weight: 700;
      color: #2daae1;
      padding-top: 10px;
      border-top: 2px solid #e0e0e0;
      margin-top: 6px;
    }

    /* Footer invoice */
    .inv-footer {
      background: #f9f9f9;
      border-top: 1px solid #eee;
      padding: 16px 28px;
      font-size: 12px;
      color: #888;
      text-align: center;
      line-height: 1.6;
    }

    /* Tombol aksi */
    .action-buttons {
      display: flex;
      gap: 12px;
      margin-top: 20px;
      justify-content: center;
    }

    .btn {
      padding: 11px 28px;
      border-radius: 25px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      border: none;
      font-family: 'Poppins', sans-serif;
      text-decoration: none;
      display: inline-block;
      transition: opacity 0.2s, transform 0.2s;
    }

    .btn:hover { opacity: 0.88; transform: translateY(-1px); }

    .btn-home  { background: #2daae1; color: white; }
    .btn-print { background: #3a7d44; color: white; }

    @media print {
      .action-buttons, header { display: none !important; }
      body { background: white; }
      .wrapper { margin: 0; max-width: 100%; padding: 0; }
      .invoice-card { box-shadow: none; border-radius: 0; }
    }
  </style>
</head>
<body>

<div class="wrapper">
  <div class="invoice-card">

    <!-- Header -->
    <div class="inv-header">
      <div class="brand">
        Rafting Singorojo
        <small>Jl. Singorojo, Kendal, Jawa Tengah</small>
      </div>
      <div class="inv-no">
        <span>No. Booking</span>
        <strong>#<?= str_pad($data['id_booking'], 5, '0', STR_PAD_LEFT) ?></strong>
      </div>
    </div>

    <!-- Status -->
    <div class="status-bar">
      <span style="font-size:13px;color:#555;">Status:</span>
      <span class="badge"><?= $s['label'] ?></span>
    </div>

    <!-- Body -->
    <div class="inv-body">

      <!-- Info Pemesan -->
      <div class="section-label">Data Pemesan</div>
      <div class="info-grid">
        <div class="info-item">Nama<strong><?= htmlspecialchars($data['nama']) ?></strong></div>
        <div class="info-item">Email<strong><?= htmlspecialchars($data['email']) ?></strong></div>
        <div class="info-item">No. HP<strong><?= htmlspecialchars($data['no_hp']) ?></strong></div>
      </div>

      <!-- Info Jadwal -->
      <div class="section-label">Jadwal Kegiatan</div>
      <div class="info-grid">
        <div class="info-item">Tanggal<strong><?= date('d F Y', strtotime($data['tanggal'])) ?></strong></div>
        <div class="info-item">Jam<strong><?= date('H:i', strtotime($data['jam'])) ?> WIB</strong></div>
        <div class="info-item">Jumlah Peserta<strong><?= $data['jumlah'] ?> orang</strong></div>
      </div>

      <!-- Detail Paket -->
      <div class="section-label">Detail Paket</div>
      <table class="detail-table">
        <thead>
          <tr>
            <th>Nama Paket</th>
            <th>Harga/pax</th>
            <th>Peserta</th>
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

      <!-- Total -->
      <div class="total-box">
        <div class="total-row">
          <span>Harga Satuan</span>
          <span>Rp <?= number_format($data['harga'], 0, ',', '.') ?></span>
        </div>
        <div class="total-row">
          <span>Jumlah Peserta</span>
          <span><?= $data['jumlah'] ?> orang</span>
        </div>
        <div class="total-final">
          <span>TOTAL HARGA</span>
          <span>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></span>
        </div>
      </div>

    </div><!-- /inv-body -->

    <!-- Footer -->
    <div class="inv-footer">
      Terima kasih telah memesan di Rafting Singorojo!<br>
      Kami akan menghubungi Anda untuk konfirmasi. Hubungi kami: <strong>0812-XXXX-XXXX</strong>
    </div>

  </div><!-- /invoice-card -->

  <!-- Tombol aksi -->
  <div class="action-buttons">
    <a href="index.php" class="btn btn-home">← Kembali ke Home</a>
    <button class="btn btn-print" onclick="window.print()">🖨️ Cetak Invoice</button>
  </div>

</div>

</body>
</html>