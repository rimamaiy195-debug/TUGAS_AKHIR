<?php 
include '../koneksi.php';
include 'header.php';
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

	  /* HEADER */
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

	  /* SECTION */
	  .section {
	    max-width: 900px;
	    margin: 0 auto;
	    padding: 36px 20px;
	  }

	  .section-title {
	    font-size: 1rem;
	    font-weight: 600;
	    color: #1a5f7a;
	    margin-bottom: 20px;
	    display: flex;
	    align-items: center;
	    gap: 8px;
	  }

	  .section-title::after {
	    content: '';
	    flex: 1;
	    height: 1px;
	    background: #d0e4ed;
	  }

	  /* GRID */
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

	  /* Ganti .img-placeholder dengan img jika punya foto asli */
	  .card .img-placeholder {
	    width: 100%;
	    aspect-ratio: 4/3;
	    background: linear-gradient(135deg, #c8dfe9, #a8c8d8);
	    display: flex;
	    align-items: center;
	    justify-content: center;
	    font-size: 2rem;
	    color: #5a9bb5;
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

    .wa-float:hover {
      transform: scale(1.1);
      background: #1ebe5d;
    }


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
	  <div class="section-title">📷 Foto Kegiatan</div>
	  <div class="grid">

	    <!-- Ganti div.img-placeholder dengan <img src="foto1.jpg" alt="..."> jika punya foto -->
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/45.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/47.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/37.JPG"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/51.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/33.JPG"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/24.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/2.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/1.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/15.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/9.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/10.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>
	    <div class="card">
	      <div class="img-placeholder"><img src="../images/20.jpg"></div>
	      <div class="tag">Arung Jeram</div>
	    </div>

	  </div>
	</div>

	<a href="https://wa.me/6283102048865" target="_blank" class="wa-float">
    <i class="fab fa-whatsapp"></i>
  </a>

</body>
</html>