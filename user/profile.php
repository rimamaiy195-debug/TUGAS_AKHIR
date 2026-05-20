<?php 
include '../koneksi.php';
include 'header.php'
 ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rafting Singorojo – Arung Jeram Sungai Bodri</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --river: #1a4a6b;
    --river-deep: #0d2e45;
    --water: #2980b9;
    --gold: #c9963a;
    --cream: #f8f6f2;
    --text: #333;
    --muted: #666;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }
  html { scroll-behavior: smooth; }

  body {
    font-family: 'Nunito', sans-serif;
    background: var(--cream);
    color: var(--text);
    overflow-x: hidden;
  }

  .hero {
    min-height: 92vh;
    background: linear-gradient(150deg, var(--river-deep) 0%, var(--river) 55%, #1e6b8a 100%);
    display: flex;
    align-items: center;
    padding: 60px;
    position: relative;
    overflow: hidden;
  }

  .hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }

  .hero-inner {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 60px;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
  }

  .hero-label {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 18px;
  }

  .hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.4rem, 4.5vw, 3.8rem);
    font-weight: 900;
    color: #fff;
    line-height: 1.12;
    margin-bottom: 18px;
  }

  .hero h1 span { color: var(--gold); }

  .hero p {
    color: rgba(255,255,255,0.68);
    font-size: 1rem;
    line-height: 1.8;
    max-width: 480px;
    margin-bottom: 36px;
  }

  .btn-book {
    display: inline-block;
    background: var(--gold);
    color: #fff;
    padding: 13px 34px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s;
    letter-spacing: 0.5px;
  }
  .btn-book:hover { background: #b8832e; transform: translateY(-2px); }

  .hero-stats {
    display: flex;
    flex-direction: column;
    gap: 16px;
    min-width: 160px;
  }

  .stat {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px;
    padding: 18px 22px;
    text-align: center;
  }
  .stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    font-weight: 900;
    color: var(--gold);
    line-height: 1;
  }
  .stat-lbl {
    font-size: 0.73rem;
    color: rgba(255,255,255,0.5);
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-top: 5px;
  }

  .about {
    padding: 80px 60px;
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 70px;
    align-items: start;
  }

  .about-text h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    color: var(--river-deep);
    line-height: 1.2;
    margin-bottom: 8px;
  }

  .underline {
    width: 48px; height: 3px;
    background: var(--gold);
    border-radius: 2px;
    margin-bottom: 28px;
  }

  .section-label {
    font-size: 0.73rem;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--water);
    margin-bottom: 10px;
  }

  .about-text p {
    color: var(--muted);
    line-height: 1.85;
    margin-bottom: 16px;
    font-size: 0.97rem;
  }
  .about-text p strong { color: var(--river); }

  .info-card {
    background: var(--river-deep);
    color: white;
    border-radius: 12px;
    padding: 28px 32px;
    margin-top: 28px;
  }
  .info-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    color: var(--gold);
    margin-bottom: 10px;
  }
  .info-card p {
    color: rgba(255,255,255,0.72);
    font-size: 0.92rem;
    line-height: 1.75;
    margin: 0;
  }

  .about-gallery {
    display: flex;
    flex-direction: column;
    gap: 14px;
  }

  .gallery-main {
    background: linear-gradient(135deg, var(--river), var(--water));
    border-radius: 12px;
    aspect-ratio: 16/10;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.35);
    font-size: 0.9rem;
    text-align: center;
    overflow: hidden;
  }
  .gallery-main img { width: 100%; height: 100%; object-fit: cover; display: block; }

  .gallery-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .gallery-thumb {
    background: linear-gradient(135deg, #1e6b8a, var(--river));
    border-radius: 10px;
    aspect-ratio: 4/3;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.3);
    font-size: 1.8rem;
    overflow: hidden;
  }
  .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }

  .loc-badge {
    background: #eef5fb;
    border: 1px solid #cce0f0;
    border-radius: 10px;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 14px;
  }
  .loc-icon { font-size: 1.6rem; }
  .loc-name { font-weight: 700; color: var(--river-deep); font-size: 0.95rem; }
  .loc-sub { font-size: 0.82rem; color: var(--muted); margin-top: 2px; }

  .location {
    background: var(--river-deep);
    padding: 80px 60px;
  }

  .location-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }

  .location .section-label { color: var(--gold); }

  .location h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    color: #fff;
    line-height: 1.2;
    margin-bottom: 8px;
  }

  .underline-gold {
    width: 40px; height: 3px;
    background: var(--gold);
    border-radius: 2px;
    margin-bottom: 24px;
  }

  .location-desc {
    color: rgba(255,255,255,0.6);
    line-height: 1.8;
    font-size: 0.95rem;
    margin-bottom: 24px;
  }

  .border-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .border-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 0.9rem;
    color: rgba(255,255,255,0.8);
  }
  .dir {
    background: rgba(201,150,58,0.2);
    color: var(--gold);
    font-weight: 700;
    font-size: 0.7rem;
    letter-spacing: 1px;
    border-radius: 4px;
    padding: 3px 9px;
    text-transform: uppercase;
    min-width: 52px;
    text-align: center;
  }

  .map-wrap {
    border-radius: 12px;
    overflow: hidden;
    height: 320px;
    position: relative;
    box-shadow: 0 12px 40px rgba(0,0,0,0.35);
  }
  .map-wrap iframe { width: 100%; height: 100%; border: 0; display: block; }
  .map-label {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(13,46,69,0.9) 0%, transparent 100%);
    padding: 14px 16px;
    color: white;
  }
  .map-label strong { font-size: 0.9rem; display: block; }
  .map-label span { font-size: 0.76rem; opacity: 0.7; }

  .route {
    padding: 70px 60px;
    background: #f0f5f9;
  }
  .route-inner { max-width: 1200px; margin: 0 auto; }

  .route h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.9rem;
    color: var(--river-deep);
    margin-bottom: 8px;
  }

  .route-desc {
    background: white;
    border-left: 4px solid var(--gold);
    border-radius: 8px;
    padding: 24px 28px;
    color: var(--muted);
    line-height: 1.85;
    font-size: 0.95rem;
    margin: 28px 0;
  }
  .route-desc strong { color: var(--river); }

  .facilities {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
  }
  .fac {
    background: white;
    border-radius: 10px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 2px 12px rgba(13,46,69,0.06);
  }
  .fac-icon { font-size: 2rem; margin-bottom: 10px; }
  .fac-title { font-weight: 700; color: var(--river); font-size: 0.9rem; margin-bottom: 4px; }
  .fac-sub { font-size: 0.82rem; color: var(--muted); }

  footer {
    background: #07192a;
    color: rgba(255,255,255,0.4);
    text-align: center;
    padding: 36px 60px;
    font-size: 0.85rem;
  }
  footer .brand {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    color: var(--gold);
    margin-bottom: 8px;
  }

  .fab {
    position: fixed;
    bottom: 28px; right: 28px;
    width: 52px; height: 52px;
    background: #25D366;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 18px rgba(37,211,102,0.45);
    z-index: 200;
    text-decoration: none;
    transition: transform 0.2s;
  }
  .fab:hover { transform: scale(1.08); }
  .fab svg { width: 26px; height: 26px; fill: white; }

  .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
  .reveal.visible { opacity: 1; transform: none; }
</style>
</head>
<body>

<section class="hero">
  <div class="hero-inner">
    <div>
      <div class="hero-label">Wisata Arung Jeram · Kabupaten Kendal</div>
      <h1>Rafting<br><span>Singorojo</span><br>Sungai Bodri</h1>
      <p>Rasakan sensasi menaklukkan arus deras Sungai Bodri — petualangan arung jeram yang memacu adrenalin di tengah alam Singorojo yang asri.</p>
      <a href="booking.php" class="btn-book">Booking Sekarang →</a>
    </div>
    <div class="hero-stats">
      <div class="stat">
        <div class="stat-num">38 km</div>
        <div class="stat-lbl">Dari Kota</div>
      </div>
      <div class="stat">
        <div class="stat-num">3–4 m</div>
        <div class="stat-lbl">Panjang Rakit</div>
      </div>
      <div class="stat">
        <div class="stat-num">⭐ 5.0</div>
        <div class="stat-lbl">Rating</div>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--cream);">
  <div class="about reveal">
    <div class="about-text">
      <div class="section-label">Tentang Kami</div>
      <h2>Rafting Singorojo /<br>Arung Jeram Sungai Bodri</h2>
      <div class="underline"></div>

      <p><strong>Rafting</strong> adalah kegiatan rekreasi luar ruangan yang menggunakan rakit tiup untuk mengarungi sungai berarus deras. Menghadapi risiko menjadi bagian dari pengalaman yang tak terlupakan.</p>

      <p>Aktivitas ini berkembang dari individu yang mendayung rakit sepanjang 3–4 meter menjadi rakit multi-orang — olahraga petualangan yang populer sejak tahun 1950-an.</p>

      <p><strong>Tubing & Rafting di Sungai Bodri</strong> hadir atas inisiatif pemuda Singorojo yang diprakarsai Nuris Nur Sahid bersama Pokdarwis dan Karang Taruna "Samudra".</p>

      <div class="info-card">
        <h3>Pokdarwis Samudra</h3>
        <p>Memanfaatkan sungai yang tadinya diancam penambangan liar sebagai wahana wisata ekstrem — meningkatkan ekonomi masyarakat sekaligus menjaga ekosistem Sungai Bodri.</p>
      </div>
    </div>

    <div class="about-gallery">
      <div class="gallery-main">
        <div>
          <div style="font-size:2.5rem;margin-bottom:8px;"><img src="../images/53.jpg"></div>
        </div>
      </div>
      <div class="gallery-row">
        <div class="gallery-thumb"><img src="../images/5.jpg"></div>
        <div class="gallery-thumb"><img src="../images/50.JPG"></div>
      </div>
      <div class="loc-badge">
        <div class="loc-icon">📍</div>
        <div>
          <div class="loc-name">Desa Singorojo, Kec. Singorojo</div>
          <div class="loc-sub">Kabupaten Kendal, Jawa Tengah</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="location">
  <div class="location-inner reveal">
    <div>
      <div class="section-label">Batas Wilayah</div>
      <h2>Letak Geografis<br>Desa Singorojo</h2>
      <div class="underline-gold"></div>
      <p class="location-desc">Singorojo berbatasan dengan beberapa kecamatan dan desa di Kabupaten Kendal:</p>
      <ul class="border-list">
        <li><span class="dir">Utara</span> Kec. Kaliwungu Selatan & Kec. Ampel</li>
        <li><span class="dir">Timur</span> Desa Cacaban & Desa Kalirejo</li>
        <li><span class="dir">Selatan</span> Desa Banyuringin & Kab. Temanggung</li>
        <li><span class="dir">Barat</span> Kecamatan Patean</li>
      </ul>
    </div>
    <div class="map-wrap">
      <iframe
        loading="lazy"
        allowfullscreen
        referrerpolicy="no-referrer-when-downgrade"
        src="https://www.google.com/maps?q=-7.0797804,110.1838086&z=15&output=embed">
      </iframe>
      <div class="map-label">
        <strong>📍 BODRI Rafting Singorojo</strong>
        <span>Sawah Hutan, Singorojo, Kab. Kendal · ±38 km dari kota</span>
      </div>
    </div>
  </div>
</section>

<section class="route">
  <div class="route-inner reveal">
    <div class="section-label">Rute</div>
    <h2>Cara Menuju Lokasi</h2>
    <div class="underline"></div>
    <div class="route-desc">
      📍 <strong>Lokasi Lembah Singorojo</strong> berada di antara perkebunan Karet dan perkebunan Plantera Fruit, berjarak sekitar <strong>38 km dari kota Kendal</strong> — ditempuh melalui jalur utama Kendal–Singorojo.
    </div>
    <div class="facilities">
      <div class="fac">
        <div class="fac-icon">🚗</div>
        <div class="fac-title">Kendaraan Pribadi</div>
        <div class="fac-sub">Area parkir luas</div>
      </div>
      <div class="fac">
        <div class="fac-icon">🏕️</div>
        <div class="fac-title">Fasilitas Lengkap</div>
        <div class="fac-sub">Toilet, ganti baju, mushola</div>
      </div>
      <div class="fac">
        <div class="fac-icon">🦺</div>
        <div class="fac-title">Pemandu Profesional</div>
        <div class="fac-sub">Terlatih & berpengalaman</div>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="brand">Rafting Singorojo</div>
  <p>Arung Jeram Sungai Bodri · Desa Singorojo, Kab. Kendal, Jawa Tengah</p>
  <p style="margin-top:16px;font-size:0.76rem;opacity:0.35;">© 2025 Rafting Singorojo. All rights reserved.</p>
</footer>

<a href="https://wa.me/6283102048865" class="fab" target="_blank" title="Chat WhatsApp">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
  </svg>
</a>

<script>
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>