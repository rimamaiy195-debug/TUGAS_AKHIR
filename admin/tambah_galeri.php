<?php
include '../koneksi.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Foto Galeri</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Poppins', sans-serif; background: #f5f7fa; color: #333; }

    .header {
      background: #1a5f7a;
      color: white;
      text-align: center;
      padding: 40px 20px 30px;
    }
    .header h1 { font-size: 2rem; font-weight: 700; }
    .header h1 span { color: #f5a623; }
    .header p { margin-top: 8px; font-size: 0.9rem; opacity: 0.85; }

    .container {
      max-width: 520px;
      margin: 40px auto;
      padding: 0 20px 60px;
    }

    .card {
      background: white;
      border-radius: 14px;
      padding: 32px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    label {
      display: block;
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 8px;
      color: #1a5f7a;
    }

    .card select, .card input[type="text"] {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #d0e4ed;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      margin-bottom: 20px;
      outline: none;
    }
    select:focus, input[type="text"]:focus {
      border-color: #1a5f7a;
    }

    .upload-area {
      border: 2px dashed #f5a623;
      border-radius: 10px;
      padding: 36px 20px;
      text-align: center;
      background: #fffbf2;
      cursor: pointer;
      margin-bottom: 16px;
      transition: background 0.2s;
    }
    .upload-area:hover { background: #fff3d6; }
    .upload-area .icon { font-size: 40px; }
    .upload-area p { color: #f5a623; font-weight: 600; margin-top: 8px; }
    .upload-area small { color: #aaa; font-size: 12px; }

    input[type="file"] { display: none; }

    #preview {
      display: none;
      width: 100%;
      border-radius: 10px;
      border: 2px solid #f5a623;
      margin-bottom: 8px;
    }
    #namaFile { font-size: 13px; color: #555; margin-bottom: 20px; }

    .btn-submit {
      width: 100%;
      background: #f5a623;
      color: white;
      border: none;
      padding: 13px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 700;
      font-family: 'Poppins', sans-serif;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-submit:hover { background: #d4891a; }

    .btn-back {
      display: block;
      text-align: center;
      margin-top: 14px;
      color: #888;
      text-decoration: none;
      font-size: 14px;
    }
    .btn-back:hover { color: #1a5f7a; }

    /* Alert */
    .alert-success {
      background: #d4edda; color: #155724;
      border: 1px solid #c3e6cb;
      padding: 12px 18px; border-radius: 8px;
      margin-bottom: 20px; font-size: 14px;
    }
    .alert-error {
      background: #f8d7da; color: #721c24;
      border: 1px solid #f5c6cb;
      padding: 12px 18px; border-radius: 8px;
      margin-bottom: 20px; font-size: 14px;
    }
  </style>
</head>
<body>

<div class="header">
  <h1>Tambah <span>Foto</span></h1>
  <p>Upload foto kegiatan rafting baru</p>
</div>

<div class="container">
  <div class="card">

    <?php if (isset($_GET['status'])): ?>
      <?php if ($_GET['status'] == 'sukses'): ?>
        <div class="alert-success">✅ Foto berhasil ditambahkan!</div>
      <?php else: ?>
        <div class="alert-error">❌ Gagal menambahkan foto. Coba lagi.</div>
      <?php endif; ?>
    <?php endif; ?>

    <form action="proses_tambah_galeri.php" method="POST" enctype="multipart/form-data">

      <label>Kategori</label>
      <select name="kategori">
        <option value="Arung Jeram">Arung Jeram</option>
        <option value="Camping">Camping</option>
        <option value="Outbound">Outbound</option>
        <option value="Dokumentasi">Dokumentasi</option>
      </select>

      <label>Foto</label>
      <label for="foto" class="upload-area">
        <div class="icon">🖼️</div>
        <p>Klik untuk pilih gambar</p>
        <small>JPG, PNG, WEBP — Maks. 2MB</small>
      </label>
      <input type="file" name="foto" id="foto" accept="image/*" onchange="previewGambar(this)">
      <img id="preview" src="#" alt="Preview">
      <p id="namaFile"></p>

      <button type="submit" class="btn-submit">💾 Simpan Foto</button>
    </form>

    <a href="galeri.php" class="btn-back">← Kembali ke Galeri</a>
  </div>
</div>

<script>
function previewGambar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const prev = document.getElementById('preview');
      prev.src = e.target.result;
      prev.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
    document.getElementById('namaFile').textContent = '📎 ' + input.files[0].name;
  }
}
</script>

</body>
</html>