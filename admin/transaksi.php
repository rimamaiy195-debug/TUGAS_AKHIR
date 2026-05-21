<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Pembayaran</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
    body {
      font-family: 'DM Sans', sans-serif;
      background: #f5f4f0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }
 
    .pay-wrap {
      max-width: 520px;
      width: 100%;
    }
 
    .pay-title {
      font-family: 'DM Serif Display', serif;
      font-size: 28px;
      font-weight: 400;
      color: #1a1a2e;
      margin-bottom: 4px;
    }
 
    .pay-sub {
      font-size: 13px;
      color: #888;
      margin-bottom: 1.5rem;
    }
 
    .section-label {
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #aaa;
      margin-bottom: 10px;
    }
 
    .card {
      background: #fff;
      border: 1px solid #e8e8e8;
      border-radius: 14px;
      padding: 1.25rem;
      margin-bottom: 1rem;
    }
 
    /* Order summary */
    .order-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 7px 0;
      font-size: 14px;
      border-bottom: 1px solid #f0f0f0;
    }
    .order-row:last-child { border-bottom: none; }
    .order-qty { color: #999; font-size: 12px; margin-top: 2px; }
    .order-price { font-weight: 500; color: #1a1a2e; }
    .order-total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0 0;
      font-size: 15px;
      font-weight: 500;
    }
    .total-amount {
      font-family: 'DM Serif Display', serif;
      font-size: 22px;
      color: #1a1a2e;
    }
 
    /* Method tabs */
    .method-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 14px;
    }
    .method-btn {
      flex: 1;
      padding: 8px 12px;
      border: 1px solid #e8e8e8;
      border-radius: 8px;
      background: #f9f9f7;
      font-size: 13px;
      font-family: 'DM Sans', sans-serif;
      color: #888;
      cursor: pointer;
      transition: all 0.15s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    .method-btn.active {
      border-color: #1a1a2e;
      background: #fff;
      color: #1a1a2e;
      font-weight: 500;
    }
    .method-btn:hover:not(.active) { background: #fff; }
 
    /* Card visual */
    .card-visual {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 14px;
      color: #fff;
      position: relative;
      overflow: hidden;
      min-height: 80px;
    }
    .card-chip {
      width: 28px;
      height: 20px;
      background: #d4af37;
      border-radius: 4px;
      margin-bottom: 12px;
    }
    .card-num-preview {
      font-family: monospace;
      font-size: 15px;
      letter-spacing: 0.2em;
      color: rgba(255,255,255,0.9);
      margin-bottom: 10px;
    }
    .card-bottom {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      color: rgba(255,255,255,0.6);
    }
    .card-bottom span {
      color: rgba(255,255,255,0.9);
      font-size: 12px;
      display: block;
      margin-top: 2px;
    }
    .card-logo {
      position: absolute;
      top: 1rem;
      right: 1.25rem;
      font-size: 22px;
      opacity: 0.85;
    }
 
    /* Form fields */
    .field-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }
    .field-group.full { grid-template-columns: 1fr; }
    .field-wrap { margin-bottom: 12px; }
    .field-label {
      font-size: 12px;
      font-weight: 500;
      color: #888;
      margin-bottom: 5px;
      display: block;
    }
 
    input, select {
      width: 100%;
      padding: 9px 12px;
      border: 1px solid #e8e8e8;
      border-radius: 8px;
      font-size: 14px;
      font-family: 'DM Sans', sans-serif;
      color: #1a1a2e;
      background: #fff;
      outline: none;
      transition: border-color 0.15s;
    }
    input:focus, select:focus { border-color: #1a1a2e; }
    input::placeholder { color: #ccc; }
 
    /* Transfer info */
    .transfer-info {
      background: #f9f9f7;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 14px;
    }
    .transfer-info p { margin: 0; }
    .transfer-bank { font-size: 12px; color: #888; margin-bottom: 6px !important; }
    .transfer-number { font-size: 18px; font-weight: 500; letter-spacing: 0.06em; color: #1a1a2e; margin-bottom: 2px !important; }
    .transfer-name { font-size: 13px; color: #888; }
 
    /* Pay button */
    .pay-btn {
      width: 100%;
      padding: 13px;
      border-radius: 8px;
      background: #1a1a2e;
      border: none;
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      font-weight: 500;
      cursor: pointer;
      transition: opacity 0.15s, transform 0.1s;
      margin-top: 4px;
    }
    .pay-btn:hover { opacity: 0.88; }
    .pay-btn:active { transform: scale(0.99); }
 
    .secure-note {
      text-align: center;
      font-size: 12px;
      color: #bbb;
      margin-top: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
    }
 
    /* Success state */
    .success-box {
      text-align: center;
      padding: 2rem 1rem;
      display: none;
    }
    .success-icon {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: #e8f5e9;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 26px;
      color: #388e3c;
    }
    .success-title {
      font-family: 'DM Serif Display', serif;
      font-size: 22px;
      color: #1a1a2e;
      margin-bottom: 6px;
    }
    .success-sub { font-size: 14px; color: #888; }
    .btn-back {
      margin-top: 1rem;
      background: none;
      border: 1px solid #e8e8e8;
      border-radius: 8px;
      padding: 8px 20px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      cursor: pointer;
      color: #1a1a2e;
    }
    .btn-back:hover { background: #f9f9f7; }
  </style>
</head>
<body>
 
<div class="pay-wrap">
  <p class="pay-title">Selesaikan Pembayaran</p>
  <p class="pay-sub">Transaksi aman &amp; terenkripsi</p>
 
  <!-- Ringkasan Pesanan -->
  <div class="card">
    <p class="section-label">Ringkasan pesanan</p>
    <div class="order-row">
      <div>
        <div>Paket Premium</div>
        <div class="order-qty">1 item</div>
      </div>
      <div class="order-price">Rp 299.000</div>
    </div>
    <div class="order-row">
      <div>Biaya layanan</div>
      <div style="color:#999;font-size:14px;">Rp 5.000</div>
    </div>
    <div class="order-total-row">
      <span>Total</span>
      <span class="total-amount">Rp 304.000</span>
    </div>
  </div>
 
  <!-- Form Pembayaran -->
  <div class="card" id="payment-form">
    <p class="section-label">Metode pembayaran</p>
 
    <div class="method-tabs">
      <button class="method-btn active" onclick="setMethod('card', this)">
        <i class="ti ti-credit-card"></i> Kartu
      </button>
      <button class="method-btn" onclick="setMethod('transfer', this)">
        <i class="ti ti-building-bank"></i> Transfer
      </button>
      <button class="method-btn" onclick="setMethod('ewallet', this)">
        <i class="ti ti-device-mobile"></i> E-Wallet
      </button>
    </div>
 
    <!-- Panel: Kartu -->
    <div id="panel-card">
      <div class="card-visual">
        <div class="card-chip"></div>
        <div class="card-num-preview" id="card-preview">•••• •••• •••• ••••</div>
        <div class="card-bottom">
          <div><div>Nama pemegang</div><span id="name-preview">NAMA LENGKAP</span></div>
          <div><div>Berlaku</div><span id="exp-preview">MM/YY</span></div>
        </div>
        <div class="card-logo">💳</div>
      </div>
 
      <div class="field-group full">
        <div class="field-wrap">
          <label class="field-label">Nama pemegang kartu</label>
          <input type="text" placeholder="Sesuai kartu"
            oninput="document.getElementById('name-preview').textContent = this.value.toUpperCase() || 'NAMA LENGKAP'">
        </div>
      </div>
      <div class="field-group full">
        <div class="field-wrap">
          <label class="field-label">Nomor kartu</label>
          <input type="text" placeholder="0000 0000 0000 0000" maxlength="19" oninput="formatCard(this)">
        </div>
      </div>
      <div class="field-group">
        <div class="field-wrap">
          <label class="field-label">Berlaku hingga</label>
          <input type="text" placeholder="MM/YY" maxlength="5" oninput="formatExp(this)">
        </div>
        <div class="field-wrap">
          <label class="field-label">CVV</label>
          <input type="password" placeholder="•••" maxlength="4">
        </div>
      </div>
    </div>
 
    <!-- Panel: Transfer -->
    <div id="panel-transfer" style="display:none;">
      <div class="transfer-info">
        <p class="transfer-bank">Transfer ke rekening</p>
        <p class="transfer-number">1234 5678 9012</p>
        <p class="transfer-name">Bank BCA · a.n. PT Toko Online</p>
      </div>
      <div class="field-group full">
        <div class="field-wrap">
          <label class="field-label">Bank pengirim</label>
          <select>
            <option>Pilih bank</option>
            <option>BCA</option>
            <option>Mandiri</option>
            <option>BNI</option>
            <option>BRI</option>
            <option>CIMB Niaga</option>
          </select>
        </div>
      </div>
      <div class="field-group full">
        <div class="field-wrap">
          <label class="field-label">Nama pengirim</label>
          <input type="text" placeholder="Sesuai nama rekening">
        </div>
      </div>
    </div>
 
    <!-- Panel: E-Wallet -->
    <div id="panel-ewallet" style="display:none;">
      <div class="field-group full">
        <div class="field-wrap">
          <label class="field-label">Pilih e-wallet</label>
          <select>
            <option>Pilih e-wallet</option>
            <option>GoPay</option>
            <option>OVO</option>
            <option>Dana</option>
            <option>ShopeePay</option>
            <option>LinkAja</option>
          </select>
        </div>
      </div>
      <div class="field-group full">
        <div class="field-wrap">
          <label class="field-label">Nomor telepon terdaftar</label>
          <input type="tel" placeholder="08xxxxxxxxxx">
        </div>
      </div>
      <p style="font-size:12px;color:#999;margin-bottom:10px;">
        Kamu akan diarahkan ke aplikasi e-wallet untuk konfirmasi pembayaran.
      </p>
    </div>
 
    <button class="pay-btn" onclick="handlePay()">
      Bayar Rp 304.000
    </button>
    <div class="secure-note">
      <i class="ti ti-lock"></i> Transaksi dienkripsi dengan SSL 256-bit
    </div>
  </div>
 
  <!-- Success State -->
  <div class="card success-box" id="success-box">
    <div class="success-icon">✓</div>
    <p class="success-title">Pembayaran Berhasil!</p>
    <p class="success-sub">Terima kasih. Konfirmasi akan dikirim ke email kamu.</p>
    <button class="btn-back" onclick="resetForm()">Kembali</button>
  </div>
</div>
 
<script>
  function setMethod(method, el) {
    document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    ['card', 'transfer', 'ewallet'].forEach(p => {
      document.getElementById('panel-' + p).style.display = p === method ? 'block' : 'none';
    });
  }
 
  function formatCard(input) {
    let v = input.value.replace(/\D/g, '').slice(0, 16);
    input.value = v.replace(/(.{4})/g, '$1 ').trim();
    const preview = v.padEnd(16, '•');
    document.getElementById('card-preview').textContent = preview.replace(/(.{4})/g, '$1 ').trim();
  }
 
  function formatExp(input) {
    let v = input.value.replace(/\D/g, '');
    if (v.length >= 2) v = v.slice(0, 2) + '/' + v.slice(2, 4);
    input.value = v;
    document.getElementById('exp-preview').textContent = v || 'MM/YY';
  }
 
  function handlePay() {
    document.getElementById('payment-form').style.display = 'none';
    document.getElementById('success-box').style.display = 'block';
  }
 
  function resetForm() {
    document.getElementById('payment-form').style.display = 'block';
    document.getElementById('success-box').style.display = 'none';
  }
</script>
 
</body>
</html>
 