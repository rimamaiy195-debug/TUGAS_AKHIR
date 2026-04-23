<?php 
include '../koneksi.php';
include 'header.php';
 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Rafting Singorojo</title>
	<style>
		*, *: :before, *: :after {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		:root{
		  --river:   #0d4f6c;
	      --sky:     #1a8fb5;
	      --foam:    #e8f7fc;
	      --accent:  #f4a024;
	      --dark:    #0a2d3e;
	      --white:   #ffffff;
	    }

	    body {
	      font-family: 'Nunito', sans-serif;
	      background: var(--foam);
	      color: var(--dark);
	      min-height: 100vh;
	      overflow-x: hidden;
	    }

	    .hero {
	      position: relative;
	      background: linear-gradient(135deg, var(--dark) 0%, var(--river) 50%, var(--sky) 100%);
	      padding: 60px 40px 80px;
	      text-align: center;
	      overflow: hidden;
	    }
	    .hero::before {
	      content: '';
	      position: absolute;
	      inset: 0;
	      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
	      animation: drift 20s linear infinite;
	    }
	    @keyframes drift { to { background-position: 120px 120px; } }

	    .hero::after {
	      content: '';
	      position: absolute;
	      bottom: -2px; left: 0; right: 0;
	      height: 60px;
	      background: var(--foam);
	      clip-path: ellipse(55% 100% at 50% 100%);
	    }

	    .hero-tag {
	      display: inline-block;
	      background: var(--accent);
	      color: var(--dark);
	      font-size: 0.72rem;
	      font-weight: 700;
	      letter-spacing: 3px;
	      text-transform: uppercase;
	      padding: 4px 14px;
	      border-radius: 20px;
	      margin-bottom: 16px;
	      position: relative;
	    }
	    .hero h1 {
	      font-family: 'Bebas Neue', sans-serif;
	      font-size: clamp(3rem, 8vw, 6rem);
	      color: var(--white);
	      letter-spacing: 4px;
	      line-height: 1;
	      position: relative;
	    }
	    .hero h1 span { color: var(--accent); }
	    .hero p {
	      color: rgba(255,255,255,.7);
	      font-size: 1rem;
	      margin-top: 12px;
	      position: relative;
	    }

	    .gallery-section {
	      max-width: 1200px;
	      margin: 0 auto;
	      padding: 60px 24px 80px;
	    }

	    .section-label {
	      display: flex;
	      align-items: center;
	      gap: 12px;
	      margin-bottom: 36px;
	    }
	    .section-label::before, .section-label::after {
	      content: '';
	      flex: 1;
	      height: 2px;
	      background: linear-gradient(to right, transparent, var(--sky), transparent);
	    }
	    .section-label span {
	      font-family: 'Bebas Neue', sans-serif;
	      font-size: 1.4rem;
	      letter-spacing: 3px;
	      color: var(--river);
	      white-space: nowrap;
	    }

	    .grid {
	      display: grid;
	      grid-template-columns: repeat(3, 1fr);
	      gap: 24px;
	    }

	    .card {
	      background: var(--white);
	      border-radius: 16px;
	      overflow: hidden;
	      box-shadow: 0 4px 20px rgba(13,79,108,.10);
	      transition: transform .35s cubic-bezier(.22,1,.36,1), box-shadow .35s ease;
	      cursor: pointer;
	      position: relative;
	    }
	    .card:hover {
	      transform: translateY(-8px) scale(1.02);
	      box-shadow: 0 16px 48px rgba(13,79,108,.22);
	    }
	    .card:nth-child(2) { margin-top: 32px; }
	    .card:nth-child(5) { margin-top: 32px; }

	    .card-img {
	      position: relative;
	      height: 220px;
	      overflow: hidden;
	      background: linear-gradient(135deg, #0d4f6c, #1a8fb5);
	    }
	    .card-img img {
	      width: 100%; height: 100%;
	      object-fit: cover;
	      transition: transform .5s ease;
	    }
	    .card:hover .card-img img { transform: scale(1.08); }

	    .card-overlay {
	      position: absolute;
	      inset: 0;
	      background: linear-gradient(to top, rgba(10,45,62,.75) 0%, transparent 60%);
	      opacity: 0;
	      transition: opacity .35s ease;
	      display: flex;
	      align-items: flex-end;
	      padding: 16px;
	    }
	    .card:hover .card-overlay { opacity: 1; }
	    .card-overlay-icon {
	      width: 40px; height: 40px;
	      background: var(--accent);
	      border-radius: 50%;
	      display: flex; align-items: center; justify-content: center;
	      margin-left: auto;
	    }
	    .card-overlay-icon svg { width: 18px; height: 18px; fill: var(--dark); }

	    .card-badge {
	      position: absolute;
	      top: 12px; left: 12px;
	      background: var(--accent);
	      color: var(--dark);
	      font-size: 0.65rem;
	      font-weight: 700;
	      letter-spacing: 2px;
	      text-transform: uppercase;
	      padding: 3px 10px;
	      border-radius: 20px;
	    }

	    .card-body {
	      padding: 16px 18px 18px;
	    }
	    .card-title {
	      font-weight: 700;
	      font-size: .92rem;
	      color: var(--river);
	      line-height: 1.4;
	    }
	    .card-sub {
	      font-size: .8rem;
	      color: #6b8fa0;
	      margin-top: 4px;
	    }

	    .lightbox {
	      display: none;
	      position: fixed;
	      inset: 0;
	      background: rgba(10,45,62,.92);
	      z-index: 1000;
	      align-items: center;
	      justify-content: center;
	      backdrop-filter: blur(6px);
	    }
	    .lightbox.active { display: flex; }
	    .lightbox-inner {
	      position: relative;
	      max-width: 80vw;
	      max-height: 85vh;
	    }
	    .lightbox-inner img {
	      max-width: 100%; max-height: 80vh;
	      border-radius: 12px;
	      box-shadow: 0 30px 80px rgba(0,0,0,.5);
	    }
	    .lightbox-close {
	      position: absolute;
	      top: -14px; right: -14px;
	      width: 36px; height: 36px;
	      background: var(--accent);
	      color: var(--dark);
	      border: none;
	      border-radius: 50%;
	      font-size: 1.2rem;
	      font-weight: 700;
	      cursor: pointer;
	      display: flex; align-items: center; justify-content: center;
	    }

	    .footer {
	      background: var(--dark);
	      color: rgba(255,255,255,.5);
	      text-align: center;
	      padding: 20px;
	      font-size: .8rem;
	      letter-spacing: 1px;
	    }
	    .footer span { color: var(--accent); }

	    @media (max-width: 768px) {
	      .grid { grid-template-columns: repeat(2, 1fr); }
	      .card:nth-child(2), .card:nth-child(5) { margin-top: 0; }
	    }
	    @media (max-width: 480px) {
	      .grid { grid-template-columns: 1fr; }
	    }

	    .card {
	      opacity: 0;
	      transform: translateY(30px);
	      animation: fadeUp .5s ease forwards;
	    }
	    .card:nth-child(1) { animation-delay: .05s; }
	    .card:nth-child(2) { animation-delay: .15s; }
	    .card:nth-child(3) { animation-delay: .25s; }
	    .card:nth-child(4) { animation-delay: .35s; }
	    .card:nth-child(5) { animation-delay: .45s; }
	    .card:nth-child(6) { animation-delay: .55s; }
	    @keyframes fadeUp {
	      to { opacity: 1; transform: translateY(0); }
	    }
	  </style>
	</head>
	<body>

	<!-- HERO -->
	<header class="hero">
	  <div class="hero-tag">Sungai Bodri • Singorojo</div>
	  <h1>Galeri <span>Rafting</span></h1>
	  <p>Momen tak terlupakan di atas arus deras Sungai Bodri</p>
	</header>

	<!-- GALLERY -->
	<section class="gallery-section">
	  <div class="section-label"><span>📸 Foto Kegiatan</span></div>

	  <div class="grid" id="gallery">

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	        <img src="../images/2.jpg"/>
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	      	<img src="../images/6.jpg">
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	      	<img src="../images/15.jpg">
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	      	<img src="../images/20.JPG">
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	      	<img src="../images/29.JPG">
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	      	<img src="../images/49.jpg">
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	      	<img src="../images/44.jpg">
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	      	<img src="../images/35.jpg">
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	    <div class="card" onclick="openLight(this)">
	      <div class="card-img">
	      	<img src="../images/51.jpg">
	        <div class="card-overlay">
	          <div class="card-overlay-icon">
	            <svg viewBox="0 0 24 24"><path d="M15 3h6v6h-2V5h-4V3zm-6 0H3v6h2V5h4V3zM3 15h2v4h4v2H3v-6zm18 0v6h-6v-2h4v-4h2z"/></svg>
	          </div>
	        </div>
	        <span class="card-badge">Arung Jeram</span>
	      </div>
	      <div class="card-body">
	        <div class="card-title">Rafting Singorojo / Arung jeram</div>
	        <div class="card-sub">Sungai Bodri Singorojo</div>
	      </div>
	    </div>

	  </div>
	</section>

	<!-- LIGHTBOX -->
	<div class="lightbox" id="lightbox" onclick="closeLight()">
	  <div class="lightbox-inner" onclick="event.stopPropagation()">
	    <button class="lightbox-close" onclick="closeLight()">×</button>
	    <img id="lightbox-img" src="" alt=""/>
	  </div>
	</div>

</body>
</html>