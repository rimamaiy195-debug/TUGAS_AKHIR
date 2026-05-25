<?php 
include '../koneksi.php';
include 'header.php';

// Ambil semua foto dari database
$query  = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeri Rafting</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f5f7fa;
      color: #333;
    }

    .header {
      background: #1a5f7a;
      color: white;
      text-align: center;
      padding: 40px 20px 30px;
    }

    .header .badge {
      display: inline-block;
      background: #f5a623;
      color: white;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1.5px;
      padding: 4px 14px;
      border-radius: 20px;
      margin-bottom: 14px;
      text-transform: uppercase;
    }

    .header h1 {
      font-size: 2.4rem;
      font-weight: 700;
      line-height: 1.1;
    }

    .header h1 span { color: #f5a623; }

    .header p {
      margin-top: 8px;
      font-size: 0.9rem;
      opacity: 0.85;
    }

    .section {
      max-width: 900px;
      margin: 0 auto;
      padding: 36px 20px;
    }

    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .section-title {
      font-size: 1rem;
      font-weight: 600;
      color: #1a5f7a;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-tambah {
      background: #f5a623;
      color: white;
      padding: 9px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background 0.2s;
    }
    .btn-tambah:hover { background: #d4891a; }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
    }

    .card {
      border-radius: 10px;
      overflow: hidden;
      background: #e8f2f7;
      position: relative;
    }

    .card img {
      width: 100%;
      aspect-ratio: 4/3;
      object-fit: cover;
      display: block;
    }

    .card .tag {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #f5a623;
      color: white;
      font-size: 9px;
      font-weight: 700;
      letter-spacing: 1px;
      padding: 3px 9px;
      border-radius: 12px;
      text-transform: uppercase;
    }

    /* Tombol hapus */
    .card .btn-hapus {
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(220,53,69,0.85);
      color: white;
      border: none;
      border-radius: 50%;
      width: 28px;
      height: 28px;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: background 0.2s;
    }
    .card .btn-hapus:hover { background: #c82333; }

    .kosong {
      text-align: center;
      color: #aaa;
      padding: 60px 0;
      grid-column: 1/-1;
    }

    footer {
      background: #07192a;
      color: rgba(255,255,255,0.4);
      text-align: center;
      padding: 36px 60px;
      font-size: 0.85rem;
    }

    footer .brand {
      font-size: 1.4rem;
      color: #f5a623;
      margin-bottom: 8px;
      font-weight: 700;
    }

    .wa-float {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #25D366;
      color: white;
      width: 55px;
      height: 55px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      text-decoration: none;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      z-index: 999;
      transition: 0.3s;
    }
    .wa-float:hover { transform: scale(1.1); background: #1ebe5d; }

    @media (max-width: 600px) {
      .grid { grid-template-columns: 1fr 1fr; }
      .header h1 { font-size: 1.8rem; }
    }
  </style>
</head>
<body>

<div class="header">
  <div class="badge">Sungai Bodri • Singorojo</div>
  <h1>Galeri <span>Rafting</span></h1>
  <p>Momen tak terlupakan di atas arus deras Sungai Bodri</p>
</div>

<div class="section">
  <div class="section-header">
    <div class="section-title">📷 Foto Kegiatan</div>
    <a href="tambah_galeri.php" class="btn-tambah">➕ Tambah Foto</a>
  </div>

  <div class="grid">
    <?php if (mysqli_num_rows($query) == 0): ?>
      <div class="kosong">Belum ada foto. Klik "Tambah Foto" untuk upload!</div>
    <?php else: ?>
      <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <div class="card">
          <img src="../images/<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['kategori']) ?>">
          <div class="tag"><?= htmlspecialchars($row['kategori']) ?></div>
          <a href="hapus_galeri.php?id=<?= $row['id'] ?>"
             class="btn-hapus"
             onclick="return confirm('Hapus foto ini?')"
             title="Hapus">✕</a>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</div>

<footer>
  <div class="brand">Rafting Singorojo</div>
  <p>Arung Jeram Sungai Bodri · Desa Singorojo, Kab. Kendal, Jawa Tengah</p>
  <p style="margin-top:16px;font-size:0.76rem;opacity:0.35;">© 2025 Rafting Singorojo. All rights reserved.</p>
</footer>

<a href="https://wa.me/6283102048865" target="_blank" class="wa-float">
  <i class="fab fa-whatsapp"></i>
</a>

</body>
</html>