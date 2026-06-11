<?php
// booking.php
include '../koneksi.php';
include 'header.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php"); exit;
}

$id_user    = (int)$_SESSION['id_user'];
$nama_paket = isset($_GET['paket']) ? trim($_GET['paket']) : '';
$harga_url  = isset($_GET['harga']) ? (int)$_GET['harga'] : 0;

if (empty($nama_paket)) {
    header("Location: paket.php"); exit;
}

$error = isset($_GET['error']) ? $_GET['error'] : '';

// ── Ambil data paket dari DB ──────────────────────────────────────────────────
$stmt = $koneksi->prepare("SELECT * FROM paket WHERE nama_paket = ? LIMIT 1");
$stmt->bind_param("s", $nama_paket);
$stmt->execute();
$paket = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$paket) {
    $paket = [
        'id_paket'   => 0,
        'nama_paket' => $nama_paket,
        'harga'      => $harga_url,
        'kapasitas'  => 20,
        'deskripsi'  => '',
    ];
}

// ── Ambil data user ───────────────────────────────────────────────────────────
$stmt = $koneksi->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ── Mapping jarak & deskripsi per paket ──────────────────────────────────────
$jarak_map = [
    'PAKET FUN RAFTING' => 'Jarak 4 KM (~1 - 1,5 jam)',
    'PAKET MEDIUM'      => 'Jarak 12 km (~2,5 - 3 jam)',
    'PAKET LONG TRIP'   => 'Jarak 15 km (~3 - 3,5 jam)',
];
$jarak = $jarak_map[strtoupper(trim($nama_paket))] ?? 'Jarak 4 KM (~1 - 1,5 jam)';

$deskripsi_default = 'Istilah arung jeram berasal dari kata whitewater rafting atau rafting yang jika diterjemahkan bebas ke dalam bahasa Indonesia berarti mengarungi sungai menggunakan perahu dengan mengandalkan keterampilan mendayung. Menurut Federasi Arung Jeram Internasional (IRF), definisi arung jeram adalah "aktivitas manusia dalam mengarungi sungai dengan mengandalkan keterampilan dan kekuatan fisik untuk mendayung perahu yang terbuat dari bahan lunak yang secara umum diterima sebagai aktivitas sosial, komersial, dan olahraga".

Pengertian arung jeram dalam kompetensi ini adalah:

• Berdasarkan mediannya — Dilakukan di sungai yang berarus.
• Berdasarkan sarananya — Menggunakan perahu berbahan dasar karet (inflatable).
• Berdasarkan tenaga yang digunakan — Mengandalkan kekuatan dan kemampuan fisik dalam mendayung.
• Berdasarkan jumlah awaknya — Berawak dua orang atau lebih dimana salah seorang bertindak sebagai pengemudi.';

$deskripsi = $deskripsi_default;
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

    .wrapper { max-width: 1600px; margin: 28px auto; padding: 0 20px; }
    .container-content { display: flex; gap: 22px; align-items: flex-start; }

    /* ── LEFT ── */
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

    .paket-banner-side { display: flex; flex-direction: column; gap: 12px; }

    .paket-banner-img2 { border-radius: 12px; overflow: hidden; }
    .paket-banner-img2 img { width: 100%; height: 120px; object-fit: cover; display: block; }

    .guide-info {
      background: white; padding: 10px; border-radius: 0 0 12px 12px;
      font-size: 13px; font-weight: 600; color: #333; text-align: center;
      border: 1px solid #eee; border-top: none;
    }

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

    /* ── RIGHT (Form) ── */
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
    .form-table input[type="tel"],
    .form-table select {
      border: 1.5px solid #ddd; border-radius: 6px; padding: 5px 8px;
      font-size: 13px; width: 100%; font-family: 'Poppins', sans-serif; outline: none;
      transition: border 0.2s;
    }
    .form-table input:focus,
    .form-table select:focus { border-color: #2daae1; }

    /* ── Cuaca Box ── */
    #forecast-box {
      display: none;
      margin-top: 8px;
      padding: 10px 12px;
      border-radius: 8px;
      font-size: 12px;
      line-height: 1.6;
      transition: all 0.3s ease;
    }
    #forecast-box .cuaca-icon { font-size: 20px; vertical-align: middle; margin-right: 4px; }
    #forecast-box .cuaca-status { font-weight: 700; }
    #forecast-box .cuaca-suhu { margin-top: 2px; color: inherit; opacity: 0.85; }
    #forecast-loading {
      display: none; margin-top: 8px; font-size: 12px; color: #888; font-style: italic;
    }

    /* ── Counter ── */
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
    .btn-booking:hover:not(:disabled) { background: #2d6235; transform: translateY(-1px); }
    .btn-booking:disabled { opacity: 0.5; cursor: not-allowed; }

    .notif-penuh {
      display: none; background: #ffe5e5; border: 1px solid #f5c6c6;
      color: #c0392b; border-radius: 8px; padding: 8px 12px;
      font-size: 12px; margin-top: 10px;
    }

    @media (max-width: 768px) {
      .container-content { flex-direction: column; }
      .paket-banner { grid-template-columns: 1fr; }
      .paket-banner-main { grid-row: auto; }
      .paket-banner-side { flex-direction: row; flex-wrap: wrap; }
      .paket-banner-img2 { flex: 1; min-width: 140px; }
      .paket-info-boxes { flex: 1; min-width: 140px; }
    }
    /* Hilangkan panah atas bawah input number */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}
  </style>
</head>
<body>

<div class="wrapper">
  <div class="container-content">

    <!-- ════════════════ KIRI ════════════════ -->
    <div class="left-section">
      <div class="paket-banner">

        <!-- Foto utama -->
        <div class="paket-banner-main">
          <?php $nama_display = preg_replace('/^PAKET\s+/i', '', $paket['nama_paket']); ?>
          <div class="paket-label">PAKET <?= strtoupper(htmlspecialchars($nama_display)) ?></div>
          <img src="../images/1.jpg" alt="Rafting">
        </div>

        <!-- Kolom kanan banner -->
        <div class="paket-banner-side">
          <div>
            <div class="paket-banner-img2">
              <img src="../images/15.jpg" alt="Rafting 2">
            </div>
            <div class="guide-info">
              🚣 1 Boat maksimal 4 peserta <br>
              🧑‍ Didampingi 2 pembimbing profesional
            </div>
          </div>

          <div class="paket-info-boxes">
            <div class="info-box">Rp <?= number_format($paket['harga'], 0, ',', '.') ?><br>/pax</div>
            <div class="info-box"><?= htmlspecialchars($jarak) ?></div>
          </div>
        </div>

      </div><!-- /.paket-banner -->

      <div class="deskripsi-box">
        <p><?= nl2br(htmlspecialchars($deskripsi)) ?></p>
      </div>
    </div><!-- /.left-section -->

    <!-- ════════════════ KANAN (Form) ════════════════ -->
    <div class="right-section">
      <div class="form-title">FORM PEMESANAN</div>
      <img src="../images/6.jpg" alt="Preview" class="form-img">

      <?php if ($error): ?>
        <div class="alert-error">⚠ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="booking_proses.php" method="POST">
        <input type="hidden" name="id_paket"   value="<?= (int)$paket['id_paket'] ?>">
        <input type="hidden" name="id_user"    value="<?= $id_user ?>">
        <input type="hidden" name="harga"      value="<?= (int)$paket['harga'] ?>">
        <input type="hidden" name="nama_paket" value="<?= htmlspecialchars($paket['nama_paket']) ?>">

        <table class="form-table">

          <!-- Paket -->
          <tr>
            <td>Paket</td><td>:</td>
            <td><strong><?= htmlspecialchars($paket['nama_paket']) ?></strong></td>
          </tr>

          <!-- Nama -->
          <tr>
            <td>Nama</td><td>:</td>
            <td><strong><?= htmlspecialchars($user['nama']) ?></strong></td>
          </tr>

          <!-- Tanggal + Prakiraan Cuaca -->
          <tr>
            <td>Tanggal</td><td>:</td>
            <td>
              <input type="date" name="tanggal" id="input_tanggal"
                     required min="<?= date('Y-m-d') ?>">

              <!-- Loading indicator -->
              <div id="forecast-loading">⏳ Mengecek cuaca...</div>

              <!-- Kotak hasil cuaca (diisi JS) -->
              <div id="forecast-box">
                <span class="cuaca-icon" id="fc-icon"></span>
                <span class="cuaca-status" id="fc-status"></span>
                <div class="cuaca-suhu" id="fc-suhu"></div>
              </div>
            </td>
          </tr>

          <!-- Jam -->
          <tr>
            <td>Jam</td><td>:</td>
            <td>
              <select name="jam" required>
                <option value="">-- Pilih Jam --</option>
                <?php
                $jam_tersedia = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00'];
                foreach ($jam_tersedia as $j) {
                    echo "<option value=\"$j\">$j</option>";
                }
                ?>
              </select>
            </td>
          </tr>

          <!-- Jumlah Orang -->
<tr>
  <td>Orang</td><td>:</td>
  <td>
    <div class="counter">
      <button type="button" onclick="kurang()">−</button>

      <input
        type="number"
        id="jumlah"
        name="jumlah"
        value="1"
        min="1"
        oninput="ubahJumlah(this.value)"
        style="
          width:70px;
          text-align:center;
          border:1.5px solid #ddd;
          border-radius:6px;
          padding:4px;
          font-weight:700;
        "
      >

      <button type="button" onclick="tambah()">+</button>
    </div>
  </td>
</tr>

          <!-- No. Telepon -->
          <tr>
            <td>No. Telepon</td><td>:</td>
            <td>
              <input type="tel" name="no_telp" placeholder="08xxxxxxxxxx"
                     value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>" required>
            </td>
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
          <span class="total-nominal" id="show_total">
            Rp <?= number_format($paket['harga'], 0, ',', '.') ?>
          </span>
        </div>

        <button type="submit" class="btn-booking" id="btn-booking">BOOKING</button>
      </form>

      <div class="notif-penuh" id="notif-penuh">
        ⚠ Booking di tanggal ini sudah penuh (maks. 50 orang). Silakan pilih tanggal lain.
      </div>

    </div><!-- /.right-section -->

  </div><!-- /.container-content -->
</div><!-- /.wrapper -->

<script>
  // ── Counter orang ──────────────────────────────────────────────────
  // ── Counter orang ──────────────────────────────────────────────────
let jumlah = 1;
const harga = <?= (int)$paket['harga'] ?>;

function formatRupiah(angka) {
  return 'Rp ' + angka.toLocaleString('id-ID');
}

function updateCounter() {
  document.getElementById('jumlah').value = jumlah;
  document.getElementById('show_orang').textContent = jumlah;
  document.getElementById('show_total').textContent = formatRupiah(jumlah * harga);
}

function tambah() {
  jumlah++;
  updateCounter();
}

function kurang() {
  if (jumlah > 1) {
    jumlah--;
    updateCounter();
  }
}

function ubahJumlah(nilai) {
  jumlah = parseInt(nilai) || 1;

  if (jumlah < 1) {
    jumlah = 1;
  }

  updateCounter();
}
  // ── Listener tanggal (cuaca + kapasitas) ──────────────────────────
  document.getElementById('input_tanggal').addEventListener('change', function () {
    const tanggal   = this.value;
    const notif     = document.getElementById('notif-penuh');
    const btn       = document.getElementById('btn-booking');
    const fcBox     = document.getElementById('forecast-box');
    const fcLoading = document.getElementById('forecast-loading');

    if (!tanggal) return;

    // Reset tampilan
    fcBox.style.display     = 'none';
    fcLoading.style.display = 'none';

    // ── 1. Cek kapasitas ─────────────────────────────────────────
    fetch('cek_kapasitas.php?tanggal=' + encodeURIComponent(tanggal))
      .then(r => r.json())
      .then(data => {
        if (data.penuh) {
          notif.style.display = 'block';
          btn.disabled        = true;
        } else {
          notif.style.display = 'none';
          btn.disabled        = false;
        }
      })
      .catch(() => {
        // Gagal cek kapasitas — biarkan user tetap bisa booking
        notif.style.display = 'none';
        btn.disabled        = false;
      });

    // ── 2. Cek cuaca via AJAX ─────────────────────────────────────
    fcLoading.style.display = 'block';

    fetch('cek_cuaca.php?tanggal=' + encodeURIComponent(tanggal))
      .then(r => r.json())
      .then(data => {
        fcLoading.style.display = 'none';

        if (data.forecast) {
          // Isi konten
          document.getElementById('fc-icon').textContent   = data.icon;
          document.getElementById('fc-status').textContent = data.status;
          document.getElementById('fc-suhu').textContent   =
            '🌡 ' + data.min + '°C – ' + data.max + '°C';

          // Terapkan warna dinamis dari server
          fcBox.style.background   = data.warna;
          fcBox.style.border       = '1px solid ' + data.border;
          fcBox.style.color        = data.teks;
          fcBox.style.display      = 'block';
        } else {
          // Ada error atau tanggal di luar jangkauan — tampilkan pesan
          document.getElementById('fc-icon').textContent   = 'ℹ️';
          document.getElementById('fc-status').textContent = data.error || 'Cuaca tidak tersedia';
          document.getElementById('fc-suhu').textContent   = '';
          fcBox.style.background = '#f5f5f5';
          fcBox.style.border     = '1px solid #ddd';
          fcBox.style.color      = '#666';
          fcBox.style.display    = 'block';
        }
      })
      .catch(() => {
        fcLoading.style.display = 'none';
        document.getElementById('fc-icon').textContent   = '⚠️';
        document.getElementById('fc-status').textContent = 'Gagal memuat prakiraan cuaca';
        document.getElementById('fc-suhu').textContent   = '';
        fcBox.style.background = '#fff3cd';
        fcBox.style.border     = '1px solid #ffc107';
        fcBox.style.color      = '#856404';
        fcBox.style.display    = 'block';
      });
  });
</script>
</body>
</html>