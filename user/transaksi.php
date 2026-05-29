<?php
include '../koneksi.php';
include 'header.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// =============================================
// INFO REKENING ADMIN — GANTI INI!
// =============================================
$adminBank     = 'BCA';
$adminNorek    = '1234567890';
$adminAtasNama = 'Nama Pemilik';

// =============================================
// INFO E-WALLET ADMIN — GANTI INI!
// =============================================
$ewalletInfo = [
    'GoPay'     => ['nomor' => '0812-3456-7890', 'akun' => 'Rafting Singorojo'],
    'OVO'       => ['nomor' => '0812-3456-7890', 'akun' => 'Rafting Singorojo'],
    'Dana'      => ['nomor' => '0812-3456-7890', 'akun' => 'Rafting Singorojo'],
    'ShopeePay' => ['nomor' => '0812-3456-7890', 'akun' => 'Rafting Singorojo'],
    'LinkAja'   => ['nomor' => '0812-3456-7890', 'akun' => 'Rafting Singorojo'],
];

/* ambil id booking */
$id_booking = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_booking) {
    die("
    <div style='font-family:Arial;max-width:500px;margin:50px auto;padding:20px;
        background:#fff3cd;border:1px solid #ffe69c;border-radius:10px;
        color:#664d03;text-align:center;'>
        <h3>⚠ Link tidak valid</h3>
        <p>ID booking tidak ditemukan.</p>
        <p>Pastikan membuka halaman dari menu booking.</p>
    </div>");
}

// Ambil data booking
$stmt = $koneksi->prepare("
    SELECT b.*, p.nama_paket, j.tanggal, j.jam
    FROM booking b
    JOIN paket p ON b.id_paket = p.id_paket
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    WHERE b.id_booking = ? AND b.id_user = ?
");
$stmt->bind_param("ii", $id_booking, $_SESSION['id_user']);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) die('Booking tidak ditemukan.');
if ($booking['status'] != 1) die('Booking belum dikonfirmasi oleh admin.');

// Cek apakah sudah pernah upload bukti
$cek = $koneksi->prepare("SELECT * FROM pembayaran WHERE id_booking = ?");
$cek->bind_param("i", $id_booking);
$cek->execute();
$pembayaran = $cek->get_result()->fetch_assoc();

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$pembayaran) {
    $metode       = trim($_POST['metode'] ?? '');
    $namaPengirim = trim($_POST['nama_pengirim'] ?? '');
    $bankPengirim = trim($_POST['bank_pengirim'] ?? '');
    $ewalletPilih = trim($_POST['ewallet_pilihan'] ?? '');
    $nomorHp      = trim($_POST['nomor_hp'] ?? '');

    if (!$metode || !$namaPengirim) {
        $error = 'Semua field wajib diisi.';
    } elseif ($metode === 'transfer' && !$bankPengirim) {
        $error = 'Pilih bank pengirim.';
    } elseif ($metode === 'ewallet' && (!$ewalletPilih || !$nomorHp)) {
        $error = 'Pilih e-wallet dan isi nomor HP.';
    } elseif (empty($_FILES['bukti_transfer']['tmp_name'])) {
        $error = 'Bukti pembayaran wajib diupload.';
    } else {
        $file    = $_FILES['bukti_transfer'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = $metode === 'ewallet' ? ['jpg','jpeg','png'] : ['jpg','jpeg','png','pdf'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($ext, $allowed)) {
            $error = $metode === 'ewallet'
                ? 'Format file tidak didukung. Gunakan JPG atau PNG.'
                : 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.';
        } elseif ($file['size'] > $maxSize) {
            $error = 'Ukuran file maksimal 2MB.';
        } else {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $fileName = 'bukti_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

            if (!move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                $error = 'Gagal mengupload file. Coba lagi.';
            } else {
                $infoPengirim = $metode === 'transfer'
                    ? $bankPengirim
                    : $ewalletPilih . ' (' . $nomorHp . ')';

                // INSERT pakai MySQLi bind_param
                $ins = $koneksi->prepare("
                    INSERT INTO pembayaran (id_booking, bank_pengirim, nama_pengirim, bukti_transfer, status_bayar)
                    VALUES (?, ?, ?, ?, 'menunggu')
                ");
                $ins->bind_param("isss", $id_booking, $infoPengirim, $namaPengirim, $fileName);
                $ins->execute();

                // Update status booking jadi 2 (menunggu verifikasi pembayaran)
                $upd = $koneksi->prepare("UPDATE booking SET status = 2 WHERE id_booking = ?");
                $upd->bind_param("i", $id_booking);
                $upd->execute();

                $success = true;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran Booking · Rafting Singorojo</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DM Sans', sans-serif; background: #f0f7ff; min-height: 100vh; padding: 2rem 1rem; }
    .wrap { max-width: 520px; width: 100%; margin: 0 auto; }
    .wrap { max-width: 520px; width: 100%; }
    .brand { display: flex; align-items: center; gap: 10px; margin-bottom: 1.25rem; }
    .brand-icon { width: 40px; height: 40px; background: #1565c0; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; }
    .brand-name { font-size: 18px; font-weight: 500; color: #1a1a2e; }
    .brand-sub { font-size: 12px; color: #888; }
    .section-label { font-size: 11px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; color: #aaa; margin-bottom: 10px; }
    .card { background: #fff; border: 1px solid #e0eaff; border-radius: 14px; padding: 1.25rem; margin-bottom: 1rem; }
    .banner-ok { background: #e8f5e9; border: 1px solid #a5d6a7; border-radius: 10px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 1rem; }
    .banner-icon { width: 30px; height: 30px; background: #388e3c; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; flex-shrink: 0; }
    .banner-title { font-size: 14px; font-weight: 500; color: #2e7d32; margin-bottom: 2px; }
    .banner-sub { font-size: 13px; color: #388e3c; line-height: 1.5; }
    .detail-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; border-bottom: 1px solid #f0f7ff; }
    .detail-row:last-child { border: none; }
    .detail-key { color: #888; }
    .detail-val { font-weight: 500; color: #1a1a2e; }
    .detail-total { display: flex; justify-content: space-between; padding: 10px 0 0; }
    .total-amount { font-family: 'DM Serif Display', serif; font-size: 22px; color: #1565c0; }
    .method-tabs { display: flex; gap: 8px; margin-bottom: 14px; }
    .method-btn { flex: 1; padding: 10px 8px; border: 1px solid #e0eaff; border-radius: 8px; background: #f9f9f7; font-size: 13px; font-family: 'DM Sans', sans-serif; color: #888; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.15s; }
    .method-btn.active { border: 2px solid #1565c0; background: #e8f0fe; color: #0c447c; font-weight: 500; }
    .rekening-box { background: #e8f0fe; border: 1px solid #90b8f5; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 12px; }
    .rek-label { font-size: 11px; color: #3b6dc9; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 6px; }
    .rek-bank { font-size: 13px; color: #1a1a2e; margin-bottom: 2px; }
    .rek-norek { font-size: 20px; font-weight: 600; color: #0c447c; letter-spacing: 0.08em; display: flex; align-items: center; gap: 10px; margin-bottom: 2px; }
    .rek-atas { font-size: 13px; color: #3b6dc9; }
    .copy-btn { font-size: 11px; background: #1565c0; color: #fff; border: none; border-radius: 5px; padding: 3px 10px; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .ewallet-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; margin-bottom: 14px; }
    .ew-opt { border: 1px solid #e0eaff; border-radius: 8px; padding: 8px 4px; cursor: pointer; text-align: center; transition: all 0.15s; }
    .ew-opt:hover { border-color: #1565c0; background: #f0f7ff; }
    .ew-opt.active { border: 2px solid #1565c0; background: #e8f0fe; }
    .ew-icon { font-size: 20px; margin-bottom: 3px; }
    .ew-name { font-size: 11px; color: #888; font-weight: 500; }
    .ew-opt.active .ew-name { color: #0c447c; }
    .ewallet-note { background: #f0f7ff; border: 1px solid #e0eaff; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #555; margin-bottom: 12px; line-height: 1.5; }
    .field-wrap { margin-bottom: 12px; }
    .field-label { font-size: 12px; font-weight: 500; color: #888; margin-bottom: 5px; display: block; }
    .field-group { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    input, select { width: 100%; padding: 10px 12px; border: 1px solid #e0eaff; border-radius: 8px; font-size: 14px; font-family: 'DM Sans', sans-serif; color: #1a1a2e; outline: none; background: #fff; }
    input:focus, select:focus { border-color: #1565c0; }
    input::placeholder { color: #ccc; }
    .upload-area { border: 1.5px dashed #90b8f5; border-radius: 10px; padding: 1.25rem; text-align: center; cursor: pointer; background: #f8fbff; }
    .upload-area:hover { border-color: #1565c0; background: #e8f0fe; }
    .upload-text { font-size: 13px; color: #888; line-height: 1.5; }
    .upload-text strong { color: #1565c0; }
    .upload-preview { margin-top: 8px; font-size: 13px; color: #388e3c; display: none; }
    .pay-btn { width: 100%; padding: 13px; border-radius: 8px; background: #1565c0; border: none; color: #fff; font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 500; cursor: pointer; margin-top: 4px; }
    .pay-btn:hover { opacity: 0.88; }
    .secure-note { text-align: center; font-size: 12px; color: #bbb; margin-top: 10px; }
    .error-box { background: #fff3f3; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c0392b; margin-bottom: 12px; }
    .already-box { background: #fff8e1; border: 1px solid #ffe082; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #f57f17; }
    .success-card { text-align: center; padding: 2.5rem 1rem; }
    .success-icon { width: 64px; height: 64px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 30px; }
    .success-title { font-family: 'DM Serif Display', serif; font-size: 24px; color: #1a1a2e; margin-bottom: 8px; }
    .success-sub { font-size: 14px; color: #888; line-height: 1.7; }
  </style>
</head>
<body>
<div class="wrap">

  <div class="brand">
    <div class="brand-icon">🚣</div>
    <div>
      <div class="brand-name">Rafting Singorojo</div>
      <div class="brand-sub">Konfirmasi Pembayaran</div>
    </div>
  </div>

<?php if ($success): ?>
  <div class="card success-card">
    <div class="success-icon">✓</div>
    <p class="success-title">Bukti Terkirim!</p>
    <p class="success-sub">Bukti pembayaran kamu sudah kami terima.<br>Admin akan memverifikasi dalam 1×24 jam.<br>Siap-siap basah! 🌊</p>
  </div>

<?php else: ?>

  <div class="banner-ok">
    <div class="banner-icon">✓</div>
    <div>
      <div class="banner-title">Booking kamu sudah dikonfirmasi!</div>
      <div class="banner-sub">Mohon segera lakukan pembayaran dan upload bukti.</div>
    </div>
  </div>

  <div class="card">
    <p class="section-label">Detail booking</p>
    <div class="detail-row"><span class="detail-key">Paket</span><span class="detail-val"><?= htmlspecialchars($booking['nama_paket']) ?></span></div>
    <div class="detail-row"><span class="detail-key">Tanggal</span><span class="detail-val"><?= date('d/m/Y', strtotime($booking['tanggal'])) ?> · <?= htmlspecialchars($booking['jam']) ?></span></div>
    <div class="detail-row"><span class="detail-key">Jumlah peserta</span><span class="detail-val"><?= $booking['jumlah_orang'] ?> orang</span></div>
    <div class="detail-total">
      <span style="font-size:15px;font-weight:500;">Total pembayaran</span>
      <span class="total-amount">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
    </div>
  </div>

  <div class="card">
    <p class="section-label">Metode pembayaran</p>

    <?php if ($pembayaran): ?>
      <div class="already-box">⏳ Kamu sudah mengupload bukti pembayaran. Sedang diverifikasi oleh admin.</div>

    <?php else: ?>
      <?php if ($error): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="metode" id="input-metode" value="transfer">

        <div class="method-tabs">
          <button type="button" class="method-btn active" onclick="setMethod('transfer', this)">🏦 Transfer bank</button>
          <button type="button" class="method-btn" onclick="setMethod('ewallet', this)">📱 E-Wallet</button>
        </div>

        <!-- Panel Transfer -->
        <div id="panel-transfer">
          <div class="rekening-box">
            <div class="rek-label">Rekening tujuan</div>
            <div class="rek-bank">Bank <?= htmlspecialchars($adminBank) ?></div>
            <div class="rek-norek"><?= htmlspecialchars($adminNorek) ?> <button type="button" class="copy-btn" onclick="copyRek(this)">Salin</button></div>
            <div class="rek-atas">a.n. <?= htmlspecialchars($adminAtasNama) ?></div>
          </div>
          <p style="font-size:12px;color:#aaa;margin-bottom:14px;">Transfer tepat <strong>Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></strong> lalu upload bukti di bawah.</p>
          <div class="field-group">
            <div class="field-wrap">
              <label class="field-label">Bank pengirim</label>
              <select name="bank_pengirim">
                <option value="">Pilih bank</option>
                <?php foreach (['BCA','Mandiri','BNI','BRI','BSI','CIMB Niaga','Danamon'] as $b): ?>
                  <option value="<?= $b ?>" <?= ($_POST['bank_pengirim'] ?? '') === $b ? 'selected' : '' ?>><?= $b ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field-wrap">
              <label class="field-label">Nama pengirim</label>
              <input type="text" name="nama_pengirim" placeholder="Sesuai rekening" value="<?= htmlspecialchars($_POST['nama_pengirim'] ?? '') ?>">
            </div>
          </div>
          <div class="field-wrap">
            <label class="field-label">Bukti transfer</label>
            <div class="upload-area" onclick="document.getElementById('file-inp').click()">
              <div style="font-size:26px;margin-bottom:6px;">📎</div>
              <div class="upload-text"><strong>Klik atau drag</strong> file ke sini<br>JPG, PNG, PDF · Maks 2MB</div>
              <div class="upload-preview" id="prev-transfer"></div>
              <input type="file" id="file-inp" name="bukti_transfer" accept=".jpg,.jpeg,.png,.pdf" style="display:none" onchange="previewFile(this,'prev-transfer')">
            </div>
          </div>
        </div>

        <!-- Panel E-Wallet -->
        <div id="panel-ewallet" style="display:none;">
          <p class="section-label" style="margin-bottom:8px;">Pilih e-wallet</p>
          <div class="ewallet-grid">
            <?php $icons = ['GoPay'=>'🟢','OVO'=>'🟣','Dana'=>'🔵','ShopeePay'=>'🟠','LinkAja'=>'🔴']; ?>
            <?php foreach ($icons as $nama => $icon): ?>
            <div class="ew-opt <?= $nama === 'GoPay' ? 'active' : '' ?>" onclick="pilihEwallet(this, '<?= $nama ?>')">
              <div class="ew-icon"><?= $icon ?></div>
              <div class="ew-name"><?= $nama === 'ShopeePay' ? 'Shopee' : $nama ?></div>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="ewallet-note">
            Transfer ke <strong id="ew-nomor"><?= $ewalletInfo['GoPay']['nomor'] ?></strong>
            (<?= $ewalletInfo['GoPay']['akun'] ?> · <span id="ew-platform">GoPay</span>)
          </div>

          <input type="hidden" name="ewallet_pilihan" id="input-ewallet" value="GoPay">

          <div class="field-wrap">
            <label class="field-label">Nomor HP pengirim</label>
            <input type="tel" name="nomor_hp" placeholder="08xxxxxxxxxx" value="<?= htmlspecialchars($_POST['nomor_hp'] ?? '') ?>">
          </div>
          <div class="field-wrap">
            <label class="field-label">Nama pengirim</label>
            <input type="text" name="nama_pengirim" placeholder="Nama di e-wallet kamu" value="<?= htmlspecialchars($_POST['nama_pengirim'] ?? '') ?>">
          </div>
          <div class="field-wrap">
            <label class="field-label">Screenshot bukti transfer</label>
            <div class="upload-area" onclick="document.getElementById('file-ew').click()">
              <div style="font-size:26px;margin-bottom:6px;">📎</div>
              <div class="upload-text"><strong>Klik atau drag</strong> file ke sini<br>JPG, PNG · Maks 2MB</div>
              <div class="upload-preview" id="prev-ewallet"></div>
              <input type="file" id="file-ew" name="bukti_transfer" accept=".jpg,.jpeg,.png" style="display:none" onchange="previewFile(this,'prev-ewallet')">
            </div>
          </div>
        </div>

        <button type="submit" class="pay-btn">Kirim Bukti Pembayaran</button>
        <div class="secure-note">🔒 Data kamu aman & hanya untuk verifikasi</div>
      </form>
    <?php endif; ?>
  </div>

<?php endif; ?>
</div>

<script>
const ewalletInfo = <?= json_encode($ewalletInfo) ?>;
const adminNorek  = '<?= $adminNorek ?>';

function setMethod(m, el) {
  document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('input-metode').value = m;
  document.getElementById('panel-transfer').style.display = m === 'transfer' ? 'block' : 'none';
  document.getElementById('panel-ewallet').style.display  = m === 'ewallet'  ? 'block' : 'none';
}

function pilihEwallet(el, nama) {
  document.querySelectorAll('.ew-opt').forEach(e => e.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('input-ewallet').value = nama;
  document.getElementById('ew-nomor').textContent    = ewalletInfo[nama].nomor;
  document.getElementById('ew-platform').textContent = nama;
}

function copyRek(btn) {
  navigator.clipboard.writeText(adminNorek).then(() => {
    btn.textContent = 'Tersalin!';
    setTimeout(() => btn.textContent = 'Salin', 2000);
  });
}

function previewFile(input, id) {
  const p = document.getElementById(id);
  if (input.files && input.files[0]) {
    p.style.display = 'block';
    p.textContent   = '✓ ' + input.files[0].name;
  }
}
</script>
</body>
</html>