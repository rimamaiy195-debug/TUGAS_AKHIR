<?php
include '../koneksi.php';
include 'header.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user   = (int)$_SESSION['id_user'];
$id_booking = (int)($_GET['id'] ?? 0);

/* Ambil data booking — pastikan milik user ini */
$stmt = $koneksi->prepare("
    SELECT b.id_booking, b.id_jadwal, b.status,
           p.nama_paket, j.tanggal, j.jam, j.jumlah
    FROM booking b
    JOIN paket  p ON b.id_paket  = p.id_paket
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    WHERE b.id_booking = ? AND b.id_user = ?
");
$stmt->bind_param("ii", $id_booking, $id_user);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    header("Location: riwayat.php"); exit;
}

/* Hanya boleh reschedule kalau belum Selesai/Dibatalkan */
if (in_array((int)$booking['status'], [2, 3])) {
    header("Location: riwayat.php"); exit;
}

/* Hitung jadwal yang FULL */
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

/* Proses simpan tanggal baru */
$pesan_sukses = '';
$pesan_error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_baru = $_POST['tanggal_baru'] ?? '';

    /* Validasi: tidak boleh kosong & tidak boleh kemarin */
    if (!$tanggal_baru || $tanggal_baru < date('Y-m-d')) {
        $pesan_error = 'Tanggal tidak valid. Pilih tanggal mulai hari ini.';

    /* Validasi: tanggal baru tidak boleh sama dengan yang lama */
    } elseif ($tanggal_baru === $booking['tanggal']) {
        $pesan_error = 'Tanggal yang dipilih sama dengan tanggal sebelumnya.';

    /* Validasi: tanggal baru tidak boleh penuh */
    } elseif (($full_data[$tanggal_baru] ?? 0) >= $kapasitas_harian) {
        $pesan_error = 'Tanggal ' . date('d F Y', strtotime($tanggal_baru)) . ' sudah penuh. Silakan pilih tanggal lain.';

    } else {
        /* Cari atau buat jadwal baru untuk tanggal yang dipilih.
           Kita pakai jam & jumlah yang sama dari jadwal lama,
           tapi tanggalnya diganti. */
        $jam    = $booking['jam'];
        $jumlah = $booking['jumlah'];

        /* Cek apakah jadwal dengan tanggal+jam itu sudah ada */
        $cek = $koneksi->prepare("SELECT id_jadwal FROM jadwal WHERE tanggal = ? AND jam = ? LIMIT 1");
        $cek->bind_param("ss", $tanggal_baru, $jam);
        $cek->execute();
        $jadwal_row = $cek->get_result()->fetch_assoc();
        $cek->close();

        if ($jadwal_row) {
            $id_jadwal_baru = $jadwal_row['id_jadwal'];
        } else {
            /* Buat jadwal baru */
            $ins = $koneksi->prepare("INSERT INTO jadwal (tanggal, jam, jumlah) VALUES (?, ?, ?)");
            $ins->bind_param("ssi", $tanggal_baru, $jam, $jumlah);
            $ins->execute();
            $id_jadwal_baru = $koneksi->insert_id;
            $ins->close();
        }

        /* Update booking dengan jadwal baru, reset status ke Menunggu (0) */
        $upd = $koneksi->prepare("UPDATE booking SET id_jadwal = ?, status = 0 WHERE id_booking = ?");
        $upd->bind_param("ii", $id_jadwal_baru, $id_booking);
        $upd->execute();
        $upd->close();

        $pesan_sukses = 'Tanggal berhasil diubah ke ' . date('d F Y', strtotime($tanggal_baru)) . '. Booking kamu kembali menunggu konfirmasi admin.';

        /* Refresh data booking agar tampilan update */
        $booking['tanggal']   = $tanggal_baru;
        $booking['id_jadwal'] = $id_jadwal_baru;
        $booking['status']    = 0;

        /* Update full_data lokal supaya kalkulasi akurat */
        $full_data[$tanggal_baru] = ($full_data[$tanggal_baru] ?? 0) + 1;
    }
}

$tgl_lama_fmt = date('d F Y', strtotime($booking['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ganti Tanggal Booking - Rafting Singorojo</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0eada; font-family: 'Poppins', sans-serif; }

    .wrapper { max-width: 520px; margin: 40px auto; padding: 0 20px 60px; }

    .back-link {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 13px; font-weight: 600; color: #2daae1;
      text-decoration: none; margin-bottom: 20px;
    }
    .back-link:hover { opacity: .8; }

    .card {
      background: white; border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0,0,0,.09);
      overflow: hidden;
    }

    .card-header {
      background: #f97316; color: white;
      padding: 18px 24px;
    }
    .card-header h2 { font-size: 16px; font-weight: 700; }
    .card-header p  { font-size: 12px; opacity: .85; margin-top: 3px; }

    .card-body { padding: 24px; }

    /* Info booking lama */
    .info-lama {
      background: #f8fafc; border-radius: 10px;
      padding: 14px 16px; margin-bottom: 20px;
      display: flex; flex-direction: column; gap: 6px;
    }
    .info-lama .row { display: flex; justify-content: space-between; align-items: center; }
    .info-lama .lbl { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; }
    .info-lama .val { font-size: 13px; font-weight: 600; color: #1e293b; }
    .tgl-lama-badge {
      display: inline-block; background: #fee2e2; color: #991b1b;
      font-size: 11px; font-weight: 700; padding: 2px 10px;
      border-radius: 20px; margin-left: 6px;
    }

    /* Notif sukses / error */
    .notif {
      border-radius: 10px; padding: 12px 16px;
      font-size: 13px; font-weight: 600;
      margin-bottom: 18px; display: flex; align-items: flex-start; gap: 8px;
      line-height: 1.5;
    }
    .notif-ok    { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
    .notif-err   { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }

    /* Form */
    label.field-lbl {
      display: block; font-size: 12px; font-weight: 700;
      color: #475569; margin-bottom: 6px; text-transform: uppercase;
      letter-spacing: .5px;
    }

    .date-input-wrap { position: relative; margin-bottom: 10px; }
    .date-input-wrap input[type="date"] {
      width: 100%; padding: 12px 16px;
      border: 1.5px solid #cbd5e1; border-radius: 10px;
      font-family: 'Poppins', sans-serif; font-size: 14px;
      color: #1e293b; outline: none;
      transition: border-color .2s;
    }
    .date-input-wrap input[type="date"]:focus { border-color: #f97316; }

    .hint {
      font-size: 11px; color: #94a3b8; margin-bottom: 20px;
      line-height: 1.5;
    }
    .hint span { color: #ef4444; font-weight: 600; }

    .btn-submit {
      width: 100%; padding: 13px;
      background: #f97316; color: white;
      border: none; border-radius: 10px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px; font-weight: 700;
      cursor: pointer; transition: opacity .2s;
    }
    .btn-submit:hover { opacity: .88; }

    .btn-kembali {
      display: block; text-align: center;
      margin-top: 12px; padding: 11px;
      border: 1.5px solid #cbd5e1; border-radius: 10px;
      font-family: 'Poppins', sans-serif;
      font-size: 13px; font-weight: 600;
      color: #64748b; text-decoration: none;
      transition: border-color .2s, color .2s;
    }
    .btn-kembali:hover { border-color: #f97316; color: #f97316; }

    /* Kalender highlight — pakai datalist sebagai hint OPEN */
    .open-dates { margin-top: 16px; }
    .open-dates .ttl { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px; }
    .open-dates .chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .chip {
      padding: 4px 12px; border-radius: 20px;
      font-size: 11px; font-weight: 700;
      cursor: pointer; transition: opacity .15s;
      border: none; font-family: 'Poppins', sans-serif;
    }
    .chip:hover { opacity: .8; }
    .chip-open { background: #d1fae5; color: #065f46; }
    .chip-full { background: #fee2e2; color: #991b1b; cursor: not-allowed; }
  </style>
</head>
<body>

<div class="wrapper">
  <a href="riwayat.php" class="back-link">← Kembali ke Riwayat</a>

  <div class="card">
    <div class="card-header">
      <h2>📅 Ganti Tanggal Booking</h2>
      <p>Booking #<?= str_pad($id_booking, 5, '0', STR_PAD_LEFT) ?> · <?= htmlspecialchars($booking['nama_paket']) ?></p>
    </div>
    <div class="card-body">

      <!-- Info booking lama -->
      <div class="info-lama">
        <div class="row">
          <span class="lbl">Tanggal sekarang</span>
          <span class="val">
            <?= $tgl_lama_fmt ?>
            <span class="tgl-lama-badge">PENUH</span>
          </span>
        </div>
        <div class="row">
          <span class="lbl">Jam</span>
          <span class="val"><?= date('H:i', strtotime($booking['jam'])) ?> WIB</span>
        </div>
        <div class="row">
          <span class="lbl">Peserta</span>
          <span class="val"><?= $booking['jumlah'] ?> orang</span>
        </div>
      </div>

      <?php if ($pesan_sukses): ?>
        <div class="notif notif-ok">✅ <?= $pesan_sukses ?></div>
      <?php endif; ?>
      <?php if ($pesan_error): ?>
        <div class="notif notif-err">❌ <?= $pesan_error ?></div>
      <?php endif; ?>

      <?php if (!$pesan_sukses): ?>
      <form method="POST">
        <label class="field-lbl">Pilih Tanggal Baru</label>
        <div class="date-input-wrap">
          <input type="date"
                 name="tanggal_baru"
                 id="tanggal_baru"
                 min="<?= date('Y-m-d') ?>"
                 required
                 value="<?= htmlspecialchars($_POST['tanggal_baru'] ?? '') ?>">
        </div>
        <p class="hint">
          Pilih tanggal yang masih <span>OPEN</span> (belum penuh).<br>
          Setelah ganti tanggal, booking akan kembali menunggu konfirmasi admin.
        </p>

        <!-- Chip tanggal yang sudah penuh sebagai referensi -->
        <?php
        $full_dates = array_keys(array_filter($full_data, fn($v) => $v >= $kapasitas_harian));
        sort($full_dates);
        $upcoming_full = array_filter($full_dates, fn($d) => $d >= date('Y-m-d'));
        if ($upcoming_full):
        ?>
        <div class="open-dates">
          <div class="ttl">Tanggal yang sudah PENUH</div>
          <div class="chips">
            <?php foreach ($upcoming_full as $fd): ?>
              <span class="chip chip-full">🚫 <?= date('d M', strtotime($fd)) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <br>
        <button type="submit" class="btn-submit">Simpan Tanggal Baru</button>
      </form>
      <?php else: ?>
        <a href="cek_booking.php" class="btn-kembali">Lihat Riwayat Booking</a>
      <?php endif; ?>

      <?php if (!$pesan_sukses): ?>
        <a href="cek_booking.php" class="btn-kembali">Batal</a>
      <?php endif; ?>

    </div>
  </div>
</div>

<script>
/* Blokir tanggal yang sudah penuh dari input date */
const fullDates = <?php echo json_encode(array_values(array_filter($full_dates ?? [], fn($d) => $d >= date('Y-m-d')))); ?>;
const tglLama   = '<?= $booking['tanggal'] ?>';

document.getElementById('tanggal_baru').addEventListener('change', function () {
    const val = this.value;
    if (fullDates.includes(val)) {
        alert('Tanggal ' + val + ' sudah penuh! Silakan pilih tanggal lain.');
        this.value = '';
    } else if (val === tglLama) {
        alert('Tanggal yang dipilih sama dengan tanggal sebelumnya.');
        this.value = '';
    }
});
</script>
</body>
</html>