<?php
include '../koneksi.php';
include 'header.php';

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
    ORDER BY j.tanggal ASC, b.id_booking DESC
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

$today = date('Y-m-d');
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>

<style>
  body {
    background-color: #f1f5f9 !important;
    font-family: 'Poppins', sans-serif !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .main-content {
    padding: 32px 48px !important;
    font-family: 'Poppins', sans-serif !important;
  }

  .main-content * {
    font-family: 'Poppins', sans-serif !important;
    box-sizing: border-box !important;
  }

  .page-header {
    display: flex !important; align-items: flex-start !important;
    justify-content: space-between !important; margin-bottom: 28px !important;
  }
  .page-header h1 { font-size: 1.5rem !important; font-weight: 700 !important; color: #1e293b !important; margin: 0 !important; }
  .page-header p  { font-size: .8rem !important; color: #64748b !important; margin-top: 3px !important; margin-bottom: 0 !important; }

  .stats {
    display: grid !important; grid-template-columns: repeat(3, 1fr) !important;
    gap: 16px !important; margin-bottom: 24px !important;
  }
  .stat-card {
    background: #fff !important; border-radius: 14px !important; padding: 20px 22px !important;
    display: flex !important; align-items: center !important; gap: 16px !important;
    box-shadow: 0 1px 4px rgba(0,0,0,.06) !important;
  }
  .stat-icon {
    width: 50px !important; height: 50px !important; border-radius: 12px !important;
    display: flex !important; align-items: center !important; justify-content: center !important;
    flex-shrink: 0 !important;
  }
  .stat-icon svg { width: 24px !important; height: 24px !important; fill: #fff !important; }
  .stat-icon.blue  { background: #0d4f6c !important; }
  .stat-icon.amber { background: #f59e0b !important; }
  .stat-icon.green { background: #10b981 !important; }
  .stat-num { font-size: 1.7rem !important; font-weight: 700 !important; line-height: 1 !important; color: #1e293b !important; }
  .stat-lbl { font-size: .75rem !important; color: #64748b !important; margin-top: 4px !important; font-weight: 500 !important; }

  .toolbar {
    display: flex !important; gap: 10px !important; flex-wrap: wrap !important;
    align-items: center !important; margin-bottom: 14px !important;
  }

  .date-group {
    display: flex !important; align-items: center !important; gap: 8px !important;
    background: #fff !important; border: 1.5px solid #cbd5e1 !important;
    border-radius: 10px !important; padding: 6px 14px !important;
  }
  .date-group label {
    font-size: .78rem !important; font-weight: 600 !important;
    color: #64748b !important; white-space: nowrap !important;
    margin: 0 !important; padding: 0 !important;
  }
  .date-group input[type="date"] {
    border: none !important; outline: none !important; background: transparent !important;
    font-family: 'Poppins', sans-serif !important; font-size: .82rem !important;
    color: #1e293b !important; cursor: pointer !important; padding: 0 !important; margin: 0 !important;
  }

  .btn-today {
  padding: 7px 14px; border-radius: 8px;
  border: 1.5px solid #cbd5e1; background: #fff;
  font-family: 'Poppins', sans-serif; font-size: .78rem; font-weight: 600;
  color: #64748b; cursor: pointer; transition: all .2s;
  }
  .btn-today.active {
    background: #0d4f6c !important; color: #fff !important; border-color: #0d4f6c !important;
  }

  .btn-reset {
    padding: 7px 14px !important; border-radius: 8px !important;
    border: 1.5px solid #cbd5e1 !important; background: #fff !important;
    font-family: 'Poppins', sans-serif !important; font-size: .78rem !important; font-weight: 600 !important;
    color: #64748b !important; cursor: pointer !important; text-decoration: none !important;
  }
  .btn-reset:hover { border-color: #0d4f6c !important; color: #0d4f6c !important; }

  .btn-reset.active {
  background: #0d4f6c !important;
  color: #fff !important;
  border-color: #0d4f6c !important;
  }

  .toolbar-divider { width: 1px !important; height: 30px !important; background: #e2e8f0 !important; }

  .status-btn {
    padding: 7px 16px !important; border-radius: 20px !important;
    border: 1.5px solid #cbd5e1 !important; background: #fff !important;
    font-family: 'Poppins', sans-serif !important; font-size: .78rem !important; font-weight: 600 !important;
    color: #64748b !important; cursor: pointer !important;
    text-decoration: none !important; display: inline-block !important;
  }
  .status-btn:hover  { border-color: #0d4f6c !important; color: #0d4f6c !important; }
  .status-btn.active {
    background: #0d4f6c !important; color: #fff !important; border-color: #0d4f6c !important;
  }

  .search-wrap { position: relative !important; margin-left: auto !important; }
  .search-wrap svg {
    position: absolute !important; left: 11px !important; top: 50% !important;
    transform: translateY(-50%) !important; width: 15px !important; height: 15px !important; fill: #94a3b8 !important;
  }
  .search-wrap input {
    padding: 8px 14px 8px 34px !important; border-radius: 20px !important;
    border: 1.5px solid #cbd5e1 !important; background: #fff !important;
    font-family: 'Poppins', sans-serif !important; font-size: .8rem !important;
    outline: none !important; width: 200px !important;
  }
  .search-wrap input:focus { border-color: #0d4f6c !important; }

  .info-strip {
    display: none; align-items: center !important; gap: 8px !important;
    background: #e8f4f8 !important; border: 1px solid #b8dde8 !important;
    border-radius: 8px !important; padding: 8px 14px !important;
    font-size: .8rem !important; color: #0d4f6c !important; font-weight: 600 !important;
    margin-bottom: 14px !important;
  }
  .info-strip.show { display: flex !important; }
  .info-strip svg { width: 16px !important; height: 16px !important; fill: #0d4f6c !important; flex-shrink: 0 !important; }

  .table-wrap {
    background: #fff !important; border-radius: 14px !important;
    box-shadow: 0 1px 4px rgba(0,0,0,.06) !important; overflow: hidden !important;
  }
  .table-wrap table { width: 100% !important; border-collapse: collapse !important; }
  .table-wrap thead { background: #f8fafc !important; }
  .table-wrap th {
    padding: 13px 18px !important; text-align: left !important;
    font-size: .7rem !important; font-weight: 700 !important;
    letter-spacing: .8px !important; text-transform: uppercase !important;
    color: #94a3b8 !important; border-bottom: 1px solid #e2e8f0 !important;
    background: #f8fafc !important;
  }
  .table-wrap td {
    padding: 14px 18px !important; font-size: .83rem !important;
    border-bottom: 1px solid #f1f5f9 !important; vertical-align: middle !important;
    color: #1e293b !important;
  }
  .table-wrap tr:last-child td { border-bottom: none !important; }
  .table-wrap tr:hover td { background: #fafcff !important; }
  .table-wrap tr.today-row td { background: #f0fdf4 !important; }
  .table-wrap tr.today-row:hover td { background: #dcfce7 !important; }

  .nama-text { font-weight: 600 !important; }
  .hp-text   { font-size: .76rem !important; color: #64748b !important; }
  .tgl-text  { font-weight: 600 !important; }
  .tgl-badge {
    display: inline-block !important; font-size: .65rem !important; font-weight: 700 !important;
    background: #10b981 !important; color: #fff !important;
    padding: 1px 7px !important; border-radius: 10px !important; margin-left: 6px !important;
    vertical-align: middle !important;
  }

  .badge { display: inline-block !important; padding: 4px 12px !important; border-radius: 20px !important; font-size: .7rem !important; font-weight: 700 !important; }
  .badge-0 { background: #fef9c3 !important; color: #a16207 !important; }
  .badge-1 { background: #d1fae5 !important; color: #065f46 !important; }
  .badge-2 { background: #e0e7ff !important; color: #3730a3 !important; }
  .badge-3 { background: #fee2e2 !important; color: #991b1b !important; }

  .actions { display: flex !important; gap: 6px !important; flex-wrap: wrap !important; }
  .btn-act {
    padding: 5px 12px !important; border-radius: 6px !important; border: none !important;
    font-family: 'Poppins', sans-serif !important; font-size: .73rem !important; font-weight: 600 !important;
    cursor: pointer !important;
  }
  .btn-confirm { background: #d1fae5 !important; color: #065f46 !important; }
  .btn-done    { background: #e0e7ff !important; color: #3730a3 !important; }
  .btn-cancel  { background: #fee2e2 !important; color: #991b1b !important; }

  .empty-row td { text-align: center !important; padding: 40px !important; color: #94a3b8 !important; font-size: .85rem !important; }

  .toast {
    position: fixed !important; bottom: 24px !important; right: 24px !important;
    background: #1e293b !important; color: #fff !important;
    padding: 11px 20px !important; border-radius: 10px !important;
    font-size: .82rem !important; font-weight: 500 !important;
    transform: translateY(60px) !important; opacity: 0 !important;
    transition: all .3s !important; z-index: 999 !important;
  }
  .toast.show { transform: translateY(0) !important; opacity: 1 !important; }

  @media (max-width: 768px) {
    .main-content { padding: 16px !important; }
    .stats { grid-template-columns: 1fr 1fr !important; }
    .search-wrap { margin-left: 0 !important; }
  }
</style>

<div class="main-content">

  <div class="page-header">
    <div>
      <h1>Manajemen Booking</h1>
      <p>Kelola semua data pemesanan rafting</p>
    </div>
  </div>

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

  <div class="toolbar">
    <div class="date-group">
      <svg viewBox="0 0 24 24" style="width:15px;height:15px;fill:#64748b;flex-shrink:0"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
      <label>Tanggal</label>
      <input type="date" id="filterTgl" onchange="renderTable()"/>
    </div>
    <button class="btn-today" onclick="setToday()">Hari Ini</button>
    <button class="btn-reset active" onclick="resetTgl()">Semua Tanggal</button>
    <div class="toolbar-divider"></div>

    <button class="status-btn active" onclick="setFilter(-1, this)">Semua</button>
    <button class="status-btn" onclick="setFilter(0, this)">Menunggu</button>
    <button class="status-btn" onclick="setFilter(1, this)">Diterima</button>
    <button class="status-btn" onclick="setFilter(2, this)">Selesai</button>
    <button class="status-btn" onclick="setFilter(3, this)">Dibatalkan</button>

    <div class="search-wrap">
      <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
      <input id="searchInput" oninput="renderTable()" placeholder="Cari nama..."/>
    </div>
  </div>

  <!-- INFO STRIP -->
  <div class="info-strip" id="infoStrip">
    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
    <span id="infoText"></span>
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

</div>

<div class="toast" id="toast"></div>

<script>
  let bookings      = <?php echo json_encode($bookings); ?>;
  let currentFilter = -1;
  const TODAY       = '<?php echo $today; ?>';

  const statusLabel = ['Menunggu', 'Diterima', 'Selesai', 'Dibatalkan'];

  function formatTgl(d) {
    if (!d) return '-';
    const [y, m, day] = d.split('-');
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return `${parseInt(day)} ${months[parseInt(m)-1]} ${y}`;
  }

  function updateStats() {
    document.getElementById('stat-total').textContent   = bookings.length;
    document.getElementById('stat-pending').textContent = bookings.filter(b => b.status === 0).length;
    document.getElementById('stat-done').textContent    = bookings.filter(b => b.status === 2).length;
  }

  function renderTable() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const tglVal = document.getElementById('filterTgl').value;

    const data = bookings.filter(b => {
      const matchStatus = currentFilter === -1 || b.status === currentFilter;
      const matchSearch = b.nama.toLowerCase().includes(q);
      const matchTgl    = !tglVal || b.tgl === tglVal;
      return matchStatus && matchSearch && matchTgl;
    });

    const strip = document.getElementById('infoStrip');
    if (tglVal) {
      const label = tglVal === TODAY ? 'Hari ini' : formatTgl(tglVal);
      document.getElementById('infoText').textContent = `${label}: ditemukan ${data.length} booking`;
      strip.classList.add('show');
    } else {
      strip.classList.remove('show');
    }

    document.getElementById('tableBody').innerHTML = data.length === 0
      ? `<tr class="empty-row"><td colspan="6">Tidak ada booking untuk filter ini</td></tr>`
      : data.map((b, i) => {
          const isToday = b.tgl === TODAY;
          return `
            <tr class="${isToday ? 'today-row' : ''}">
              <td>${i + 1}</td>
              <td>
                <div class="nama-text">${b.nama}</div>
                <div class="hp-text">${b.hp}</div>
              </td>
              <td>
                <span class="tgl-text">${formatTgl(b.tgl)}</span>
                ${isToday ? '<span class="tgl-badge">Hari ini</span>' : ''}
              </td>
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
            </tr>`;
        }).join('');

    updateStats();
  }

  function setFilter(f, el) {
    currentFilter = f;
    document.querySelectorAll('.status-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    renderTable();
  }

  function setToday() {
  document.getElementById('filterTgl').value = TODAY;
  document.querySelector('.btn-today').classList.add('active');
  document.querySelector('.btn-reset').classList.remove('active');
  renderTable();
  }

  function resetTgl() {
    document.getElementById('filterTgl').value = '';
    document.querySelector('.btn-reset').classList.add('active');
    document.querySelector('.btn-today').classList.remove('active');
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