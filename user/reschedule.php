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
    header("Location: cek_booking.php"); exit;
}

/* Hanya boleh reschedule kalau belum Selesai/Dibatalkan */
if (in_array((int)$booking['status'], [2, 3])) {
    header("Location: cek_booking.php"); exit;
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

/* ---- tidak pakai API key, pakai cek_cuaca.php ---- */
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

    /* Cuaca */
    .cuaca-wrap {
      margin-top: 18px; border-radius: 12px; overflow: hidden;
      border: 1.5px solid #e2e8f0;
    }
    .cuaca-header {
      background: #0ea5e9; color: white;
      padding: 10px 14px; font-size: 12px; font-weight: 700;
      display: flex; align-items: center; gap: 6px;
    }
    .cuaca-body {
      padding: 14px; background: #f0f9ff;
      min-height: 60px; display: flex; align-items: center;
      justify-content: center;
    }
    .cuaca-loading { color: #94a3b8; font-size: 12px; font-style: italic; }
    .cuaca-error   { color: #ef4444; font-size: 12px; font-weight: 600; }

    .cuaca-result {
      display: flex; align-items: center; gap: 14px; width: 100%;
    }
    .cuaca-icon { font-size: 2.4rem; flex-shrink: 0; }
    .cuaca-info { flex: 1; }
    .cuaca-desc {
      font-size: 13px; font-weight: 700; color: #0c4a6e;
      text-transform: capitalize; margin-bottom: 4px;
    }
    .cuaca-detail {
      display: flex; flex-wrap: wrap; gap: 8px;
    }
    .cuaca-tag {
      background: white; border: 1px solid #bae6fd;
      border-radius: 20px; padding: 3px 10px;
      font-size: 11px; font-weight: 600; color: #0369a1;
    }
    .cuaca-note {
      font-size: 11px; color: #64748b; margin-top: 6px;
      font-style: italic;
    }

    /* warning cuaca buruk */
    .cuaca-warn {
      margin-top: 8px; background: #fef2f2;
      border: 1px solid #fecaca; border-radius: 8px;
      padding: 8px 12px; font-size: 11px;
      color: #991b1b; font-weight: 600;
    }
    /* cuaca oke */
    .cuaca-ok {
      margin-top: 8px; background: #f0fdf4;
      border: 1px solid #bbf7d0; border-radius: 8px;
      padding: 8px 12px; font-size: 11px;
      color: #166534; font-weight: 600;
    }
  </style>
</head>
<body>

<div class="wrapper">
  <a href="cek_booking.php" class="back-link">← Kembali ke Riwayat</a>

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

        <!-- CUACA -->
        <div class="cuaca-wrap" id="cuacaWrap" style="display:none">
          <div class="cuaca-header">
            🌤️ Prakiraan Cuaca di Tanggal Ini
          </div>
          <div class="cuaca-body" id="cuacaBody">
            <span class="cuaca-loading">Mengambil data cuaca...</span>
          </div>
        </div>

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

// Rekomendasi berdasarkan status cuaca dari cek_cuaca.php
function rekomendasiCuaca(status) {
    const map = {
        'Cerah'         : { ok: true,  pesan: 'Cuaca cerah! Kondisi terbaik untuk rafting hari ini.' },
        'Berawan'       : { ok: true,  pesan: 'Mendung sebagian — cuaca cukup stabil untuk rafting.' },
        'Berkabut'      : { ok: true,  pesan: 'Berkabut — waspadai jarak pandang di sungai.' },
        'Hujan'         : { ok: false, pesan: 'Hujan diprakirakan — pertimbangkan untuk memilih tanggal lain.' },
        'Hujan Deras'   : { ok: false, pesan: 'Hujan deras — rafting berisiko tinggi, tidak direkomendasikan.' },
        'Badai Petir'   : { ok: false, pesan: 'Potensi badai petir — rafting tidak direkomendasikan!' },
        'Salju/Hujan Es': { ok: false, pesan: 'Kondisi ekstrem — tidak disarankan.' },
    };
    return map[status] ?? { ok: true, pesan: 'Cek kondisi lapangan sebelum berangkat.' };
}

async function cekCuaca(tanggal) {
    const wrap = document.getElementById('cuacaWrap');
    const body = document.getElementById('cuacaBody');
    wrap.style.display = 'block';
    body.innerHTML = '<span class="cuaca-loading">⏳ Mengambil data cuaca...</span>';

    try {
        const res  = await fetch(`cek_cuaca.php?tanggal=${tanggal}`);
        const data = await res.json();

        if (!data.forecast) {
            body.innerHTML = `<span class="cuaca-error">⚠️ ${data.error}</span>`;
            return;
        }

        const rek     = rekomendasiCuaca(data.status);
        const tgl_fmt = new Date(tanggal + 'T00:00:00').toLocaleDateString('id-ID', {
            weekday:'long', day:'numeric', month:'long', year:'numeric'
        });

        body.innerHTML = `
          <div class="cuaca-result">
            <div class="cuaca-icon">${data.icon}</div>
            <div class="cuaca-info">
              <div class="cuaca-desc" style="color:${data.teks}">${data.status}</div>
              <div class="cuaca-detail">
                <span class="cuaca-tag">🌡️ ${data.min}°C – ${data.max}°C</span>
                <span class="cuaca-tag">Kode WMO: ${data.kode}</span>
              </div>
              <div class="${rek.ok ? 'cuaca-ok' : 'cuaca-warn'}">
                ${rek.ok ? '✅' : '⚠️'} ${rek.pesan}
              </div>
              <div class="cuaca-note">Prakiraan untuk ${tgl_fmt} · Sumber: Open-Meteo</div>
            </div>
          </div>`;
    } catch (e) {
        body.innerHTML = `<span class="cuaca-error">❌ Gagal mengambil data cuaca. Cek koneksi internet.</span>`;
    }
}

document.getElementById('tanggal_baru').addEventListener('change', function () {
    const val = this.value;
    if (fullDates.includes(val)) {
        alert('Tanggal ' + val + ' sudah penuh! Silakan pilih tanggal lain.');
        this.value = '';
        document.getElementById('cuacaWrap').style.display = 'none';
    } else if (val === tglLama) {
        alert('Tanggal yang dipilih sama dengan tanggal sebelumnya.');
        this.value = '';
        document.getElementById('cuacaWrap').style.display = 'none';
    } else if (val) {
        cekCuaca(val);
    }
});
</script>
</body>
</html>