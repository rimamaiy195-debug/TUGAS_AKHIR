<?php
include '../koneksi.php';
include 'header.php';

<<<<<<< HEAD
	// Ambil data paket dari URL
	$paket = isset($_GET['paket']) ? $_GET['paket'] : '';
	$harga = isset($_GET['harga']) ? (int)$_GET['harga'] : 0;

	// Data paket lengkap
	$data_paket = [
		'Fun Rafting' => [
			'jarak'   => 'Jarak 4 km (~+1- 1,5 jam)',
			'harga'   => 135000,
			'img'     => '../images/1.jpg',
			'img2'    => '../images/2.jpg',
		],
		'Medium' => [
			'jarak'   => 'Jarak 12 km (~2,5 - 3 jam)',
			'harga'   => 175000,
			'img'     => '../images/1.jpg',
			'img2'    => '../images/2.jpg',
		],
		'Long Trip' => [
			'jarak'   => 'Jarak 15 km (~3 - 3,5 jam)',
			'harga'   => 210000,
			'img'     => '../images/1.jpg',
			'img2'    => '../images/2.jpg',
		],
	];

	$info = isset($data_paket[$paket]) ? $data_paket[$paket] : $data_paket['Fun Rafting'];
	$harga_final = $info['harga'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking - Rafting Singorojo</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background-color: #f0eada;
      font-family: 'Poppins', Arial, sans-serif;
    }

    .wrapper {
      max-width: 1200px;
      margin: 28px auto;
      padding: 0 20px;
    }

    .container-content {
      display: flex;
      gap: 22px;
      align-items: flex-start;
    }

    /* ===== KIRI ===== */
    .left-section {
      flex: 2;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    /* Banner paket */
    .paket-banner {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-template-rows: auto auto;
      gap: 12px;
    }

    .paket-banner-main {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      grid-row: 1 / 3;
    }

    .paket-banner-main img {
      width: 100%;
      height: 260px;
      object-fit: cover;
      display: block;
    }

    .paket-label {
      position: absolute;
      top: 0; left: 0;
      background: #2daae1;
      color: white;
      font-weight: 700;
      font-size: 18px;
      padding: 14px 18px;
      width: 100%;
    }

    .paket-banner-img2 {
      border-radius: 12px;
      overflow: hidden;
    }

    .paket-banner-img2 img {
      width: 100%;
      height: 130px;
      object-fit: cover;
      display: block;
    }

    .paket-info-boxes {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .info-box {
      background: white;
      border-radius: 10px;
      border: 1.5px solid #ddd;
      text-align: center;
      padding: 14px 10px;
      font-size: 14px;
      font-weight: 600;
      color: #333;
      line-height: 1.4;
    }

    /* Deskripsi */
    .deskripsi-box {
      background: white;
      border-radius: 14px;
      padding: 22px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    .deskripsi-box p {
      font-size: 13.5px;
      color: #555;
      line-height: 1.7;
      margin-bottom: 14px;
      text-align: justify;
    }

    .deskripsi-box p:last-child { margin-bottom: 0; }

    /* ===== KANAN ===== */
    .right-section {
      flex: 1;
      background: white;
      border-radius: 14px;
      padding: 18px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    .form-title {
      font-weight: 700;
      font-size: 15px;
      color: #333;
      text-align: center;
      margin-bottom: 14px;
      letter-spacing: 0.3px;
    }

    .form-img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 16px;
    }

    /* Tabel form */
    .form-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
    }

    .form-table tr td {
      padding: 6px 4px;
      font-size: 13px;
      color: #444;
      vertical-align: middle;
    }

    .form-table tr td:first-child {
      font-weight: 600;
      white-space: nowrap;
      width: 100px;
    }

    .form-table tr td:nth-child(2) {
      width: 14px;
      color: #888;
    }

    .form-table input[type="date"],
    .form-table input[type="time"],
    .form-table input[type="tel"] {
      border: 1.5px solid #ddd;
      border-radius: 6px;
      padding: 5px 8px;
      font-size: 13px;
      width: 100%;
      font-family: 'Poppins', sans-serif;
      outline: none;
      transition: border 0.2s;
    }

    .form-table input:focus {
      border-color: #2daae1;
    }

    /* Counter orang */
    .counter {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .counter button {
      width: 26px;
      height: 26px;
      border-radius: 5px;
      border: 1.5px solid #ccc;
      background: #f5f5f5;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      line-height: 1;
      transition: background 0.2s;
    }

    .counter button:hover { background: #2daae1; color: white; border-color: #2daae1; }

    .counter span {
      font-weight: 700;
      font-size: 15px;
      min-width: 20px;
      text-align: center;
    }

    /* Divider */
    .divider {
      border: none;
      border-top: 1.5px solid #eee;
      margin: 10px 0;
    }

    /* Total harga */
    .total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: 700;
      font-size: 14px;
      color: #222;
      margin-bottom: 14px;
    }

    .total-row .total-nominal {
      color: #2daae1;
      font-size: 15px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      font-size: 12.5px;
      color: #666;
      margin-bottom: 5px;
    }

    /* Tombol booking */
    .btn-booking {
      background: #3a7d44;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 25px;
      width: 100%;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      letter-spacing: 1px;
      font-family: 'Poppins', sans-serif;
      transition: background 0.2s, transform 0.2s;
    }

    .btn-booking:hover {
      background: #2d6235;
      transform: translateY(-1px);
    }

    @media (max-width: 768px) {
      .container-content { flex-direction: column; }
      .paket-banner { grid-template-columns: 1fr; }
      .paket-banner-main { grid-row: auto; }
    }
  </style>
</head>
<body>

<div class="wrapper">
  <div class="container-content">

    <!-- KIRI -->
    <div class="left-section">

      <!-- Banner & Info Paket -->
      <div class="paket-banner">
        <div class="paket-banner-main">
          <div class="paket-label">PAKET <?= strtoupper(htmlspecialchars($paket)) ?></div>
          <img src="<?= $info['img'] ?>" alt="Rafting">
        </div>

        <div class="paket-banner-img2">
          <img src="<?= $info['img2'] ?>" alt="Rafting 2">
        </div>

        <div class="paket-info-boxes">
          <div class="info-box">
            <?= number_format($info['harga'], 0, ',', '.') ?> Ribu<br>/pax
          </div>
          <div class="info-box">
            <?= htmlspecialchars($info['jarak']) ?>
          </div>
        </div>
      </div>

      <!-- Deskripsi -->
      <div class="deskripsi-box">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
      </div>

    </div>

    <!-- KANAN: FORM PEMESANAN -->
    <div class="right-section">
      <div class="form-title">FROM PEMESANAN</div>

      <img src="<?= $info['img2'] ?>" alt="Preview" class="form-img">

      <form action="booking_proses.php" method="POST">
        <input type="hidden" name="paket" value="<?= htmlspecialchars($paket) ?>">
        <input type="hidden" name="harga_satuan" value="<?= $harga_final ?>">

        <table class="form-table">
          <tr>
            <td>Paket</td>
            <td>:</td>
            <td><strong><?= htmlspecialchars($paket) ?></strong></td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><input type="date" name="tanggal" required min="<?= date('Y-m-d') ?>"></td>
          </tr>
          <tr>
            <td>Jam</td>
            <td>:</td>
            <td><input type="time" name="jam" required></td>
          </tr>
          <tr>
            <td>Orang</td>
            <td>:</td>
            <td>
              <div class="counter">
                <button type="button" onclick="kurang()">-</button>
                <span id="jumlah">2</span>
                <button type="button" onclick="tambah()">+</button>
              </div>
              <input type="hidden" name="jumlah_orang" id="input_jumlah" value="2">
            </td>
          </tr>
          <tr>
            <td>No. Telepon</td>
            <td>:</td>
            <td><input type="tel" name="no_telepon" placeholder="08xxxxxxxxxx" required></td>
          </tr>
        </table>

        <hr class="divider">

        <div class="info-row">
          <span>Harga Satuan</span>
          <span>: Rp <?= number_format($harga_final, 0, ',', '.') ?></span>
        </div>
        <div class="info-row">
          <span>Jumlah Orang</span>
          <span>: <span id="show_orang">2</span> orang</span>
        </div>

        <hr class="divider">

        <div class="total-row">
          <span>TOTAL HARGA :</span>
          <span class="total-nominal" id="show_total">Rp <?= number_format($harga_final * 2, 0, ',', '.') ?></span>
        </div>

        <button type="submit" class="btn-booking">BOOKING</button>
      </form>
    </div>

  </div>
</div>

<script>
  let jumlah = 2;
  const harga = <?= $harga_final ?>;

  function update() {
    document.getElementById('jumlah').textContent = jumlah;
    document.getElementById('input_jumlah').value = jumlah;
    document.getElementById('show_orang').textContent = jumlah;
    const total = jumlah * harga;
    document.getElementById('show_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
  }

  function tambah() {
    jumlah++;
    update();
  }

  function kurang() {
    if (jumlah > 1) { jumlah--; update(); }
  }
</script>
=======
$query = mysqli_query($koneksi, "
    SELECT 
        b.id_booking,
        b.status,
        u.nama       AS nama,
        u.no_hp      AS hp,
        p.nama_paket AS paket,
        j.tanggal    AS tgl
    FROM booking b
    JOIN user   u ON b.id_user   = u.id_user
    JOIN paket  p ON b.id_paket  = p.id_paket
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    ORDER BY b.id_booking DESC
");

$bookings = [];
while ($row = mysqli_fetch_assoc($query)) {
    $bookings[] = [
        'id'     => $row['id_booking'],
        'nama'   => $row['nama'],
        'hp'     => $row['hp'],
        'tgl'    => $row['tgl'],
        'paket'  => $row['paket'],
        'status' => (int)$row['status'],
    ];
}
?>

<style>
  /* reset yang bentrok dari header.php */
  body {
    background-color: #f1f5f9 !important;
    font-family: 'Poppins', sans-serif !important;
  }

  .main-content {
    padding: 32px 48px;
  }

  /* ── Page header ── */
  .page-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; margin-bottom: 28px;
  }
  .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
  .page-header p  { font-size: .8rem; color: #64748b; margin-top: 3px; }

  /* ── Stats ── */
  .stats {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 16px; margin-bottom: 24px;
  }
  .stat-card {
    background: #fff; border-radius: 14px;
    padding: 20px 22px;
    display: flex; align-items: center; gap: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
  }
  .stat-icon {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .stat-icon svg { width: 24px; height: 24px; fill: #fff; }
  .stat-icon.blue  { background: #0d4f6c; }
  .stat-icon.amber { background: #f59e0b; }
  .stat-icon.green { background: #10b981; }
  .stat-num { font-size: 1.7rem; font-weight: 700; line-height: 1; color: #1e293b; }
  .stat-lbl { font-size: .75rem; color: #64748b; margin-top: 4px; font-weight: 500; }

  /* ── Filter bar ── */
  .filter-bar {
    display: flex; gap: 8px; flex-wrap: wrap;
    align-items: center; margin-bottom: 18px;
  }
  .filter-btn {
    padding: 7px 18px; border-radius: 20px;
    border: 1.5px solid #cbd5e1; background: #fff;
    font-family: 'Poppins', sans-serif; font-size: .8rem; font-weight: 600;
    color: #64748b; cursor: pointer; transition: all .2s;
  }
  .filter-btn:hover  { border-color: #0d4f6c; color: #0d4f6c; }
  .filter-btn.active { background: #0d4f6c; color: #fff; border-color: #0d4f6c; }

  .search-wrap { position: relative; margin-left: auto; }
  .search-wrap svg {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    width: 15px; height: 15px; fill: #94a3b8;
  }
  .search-wrap input {
    padding: 8px 14px 8px 34px; border-radius: 20px;
    border: 1.5px solid #cbd5e1;
    font-family: 'Poppins', sans-serif; font-size: .8rem; outline: none; width: 200px;
    background: #fff; transition: border-color .2s;
  }
  .search-wrap input:focus { border-color: #0d4f6c; }

  /* ── Table ── */
  .table-wrap {
    background: #fff; border-radius: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06); overflow: hidden;
  }
  table { width: 100%; border-collapse: collapse; }
  thead { background: #f8fafc; }
  th {
    padding: 13px 18px; text-align: left;
    font-size: .7rem; font-weight: 700;
    letter-spacing: .8px; text-transform: uppercase;
    color: #94a3b8; border-bottom: 1px solid #e2e8f0;
  }
  td {
    padding: 14px 18px; font-size: .83rem;
    border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    color: #1e293b;
  }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #fafcff; }
  .nama-text { font-weight: 600; }
  .hp-text   { font-size: .76rem; color: #64748b; }

  /* ── Badge ── */
  .badge {
    display: inline-block; padding: 4px 12px;
    border-radius: 20px; font-size: .7rem; font-weight: 700;
  }
  .badge-0 { background: #fef9c3; color: #a16207; }
  .badge-1 { background: #d1fae5; color: #065f46; }
  .badge-2 { background: #e0e7ff; color: #3730a3; }
  .badge-3 { background: #fee2e2; color: #991b1b; }

  /* ── Action buttons ── */
  .actions { display: flex; gap: 6px; flex-wrap: wrap; }
  .btn-act {
    padding: 5px 12px; border-radius: 6px; border: none;
    font-family: 'Poppins', sans-serif; font-size: .73rem; font-weight: 600;
    cursor: pointer; transition: opacity .2s;
  }
  .btn-act:hover   { opacity: .8; }
  .btn-confirm { background: #d1fae5; color: #065f46; }
  .btn-done    { background: #e0e7ff; color: #3730a3; }
  .btn-cancel  { background: #fee2e2; color: #991b1b; }

  .empty-row td {
    text-align: center; padding: 40px;
    color: #94a3b8; font-size: .85rem;
  }

  /* ── Toast ── */
  .toast {
    position: fixed; bottom: 24px; right: 24px;
    background: #1e293b; color: #fff;
    padding: 11px 20px; border-radius: 10px;
    font-size: .82rem; font-weight: 500;
    transform: translateY(60px); opacity: 0;
    transition: all .3s; z-index: 999;
  }
  .toast.show { transform: translateY(0); opacity: 1; }

  @media (max-width: 768px) {
    .main-content { padding: 16px; }
    .stats { grid-template-columns: 1fr 1fr; }
  }
</style>

<!-- KONTEN UTAMA -->
<div class="main-content">

  <!-- HEADER -->
  <div class="page-header">
    <div>
      <h1>Manajemen Booking</h1>
      <p>Kelola semua data pemesanan rafting</p>
    </div>
  </div>

  <!-- STATS -->
  <div class="stats">
    <div class="stat-card">
      <div class="stat-icon blue">
        <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
      </div>
      <div>
        <div class="stat-num" id="stat-total">0</div>
        <div class="stat-lbl">Total Booking</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon amber">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
      </div>
      <div>
        <div class="stat-num" id="stat-pending">0</div>
        <div class="stat-lbl">Menunggu Konfirmasi</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green">
        <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
      </div>
      <div>
        <div class="stat-num" id="stat-done">0</div>
        <div class="stat-lbl">Selesai</div>
      </div>
    </div>
  </div>

  <!-- FILTER -->
  <div class="filter-bar">
    <button class="filter-btn active" onclick="setFilter(-1, this)">Semua</button>
    <button class="filter-btn" onclick="setFilter(0, this)">Menunggu</button>
    <button class="filter-btn" onclick="setFilter(1, this)">Diterima</button>
    <button class="filter-btn" onclick="setFilter(2, this)">Selesai</button>
    <button class="filter-btn" onclick="setFilter(3, this)">Dibatalkan</button>
    <div class="search-wrap">
      <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
      <input id="searchInput" oninput="renderTable()" placeholder="Cari nama..."/>
    </div>
  </div>

  <!-- TABLE -->
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Tgl Rafting</th>
          <th>Paket</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="tableBody"></tbody>
    </table>
  </div>

</div><!-- end .main-content -->

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script>
  let bookings      = <?php echo json_encode($bookings); ?>;
  let currentFilter = -1;

  const statusLabel = ['Menunggu', 'Diterima', 'Selesai', 'Dibatalkan'];

  function updateStats() {
    document.getElementById('stat-total').textContent   = bookings.length;
    document.getElementById('stat-pending').textContent = bookings.filter(b => b.status === 0).length;
    document.getElementById('stat-done').textContent    = bookings.filter(b => b.status === 2).length;
  }

  function renderTable() {
    const q    = document.getElementById('searchInput').value.toLowerCase();
    const data = bookings.filter(b =>
      (currentFilter === -1 || b.status === currentFilter) &&
      b.nama.toLowerCase().includes(q)
    );

    document.getElementById('tableBody').innerHTML = data.length === 0
      ? `<tr class="empty-row"><td colspan="6">Tidak ada data booking</td></tr>`
      : data.map((b, i) => `
          <tr>
            <td>${i + 1}</td>
            <td>
              <div class="nama-text">${b.nama}</div>
              <div class="hp-text">${b.hp}</div>
            </td>
            <td>${b.tgl}</td>
            <td>${b.paket}</td>
            <td><span class="badge badge-${b.status}">${statusLabel[b.status] ?? '-'}</span></td>
            <td>
              <div class="actions">
                ${b.status === 0 ? `<button class="btn-act btn-confirm" onclick="changeStatus(${b.id}, 1)">✔ Terima</button>` : ''}
                ${b.status === 1 ? `<button class="btn-act btn-done"    onclick="changeStatus(${b.id}, 2)">🏁 Selesai</button>` : ''}
                ${b.status !== 3 && b.status !== 2
                  ? `<button class="btn-act btn-cancel" onclick="changeStatus(${b.id}, 3)">✖ Batal</button>` : ''}
              </div>
            </td>
          </tr>
        `).join('');
>>>>>>> 8210e45366ee45e87ff64bdbcdeccc647298473f

    updateStats();
  }

  function setFilter(f, el) {
    currentFilter = f;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    renderTable();
  }

  function changeStatus(id, status) {
    fetch('update_status.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body:    `id=${id}&status=${status}`
    })
    .then(res => {
      if (!res.ok) throw new Error();
      bookings = bookings.map(b => b.id == id ? { ...b, status } : b);
      renderTable();
      const msgs = { 1:'✅ Booking diterima!', 2:'🎉 Rafting selesai!', 3:'❌ Booking dibatalkan.' };
      showToast(msgs[status] || 'Status diperbarui.');
    })
    .catch(() => showToast('❌ Gagal mengubah status.'));
  }

  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
  }

  document.addEventListener('DOMContentLoaded', renderTable);
</script>