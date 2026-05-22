<?php
// cek_cuaca.php
// Letakkan file ini di folder yang sama dengan booking.php

header('Content-Type: application/json');

$tanggal = isset($_GET['tanggal']) ? trim($_GET['tanggal']) : '';

if (empty($tanggal) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    echo json_encode(['forecast' => null, 'error' => 'Tanggal tidak valid']);
    exit;
}

$api = "https://api.open-meteo.com/v1/forecast"
     . "?latitude=-7.02&longitude=110.43"
     . "&daily=weather_code,temperature_2m_max,temperature_2m_min"
     . "&timezone=Asia%2FBangkok"
     . "&forecast_days=16";

$ctx = stream_context_create(['http' => ['timeout' => 5]]);
$json = @file_get_contents($api, false, $ctx);

if (!$json) {
    echo json_encode(['forecast' => null, 'error' => 'Gagal ambil data cuaca']);
    exit;
}

$data = json_decode($json, true);

if (!isset($data['daily']['time'])) {
    echo json_encode(['forecast' => null, 'error' => 'Format data cuaca tidak dikenal']);
    exit;
}

$index = array_search($tanggal, $data['daily']['time']);

if ($index === false) {
    echo json_encode(['forecast' => null, 'error' => 'Tanggal di luar jangkauan prakiraan (maks. 16 hari ke depan)']);
    exit;
}

$kode = (int)$data['daily']['weather_code'][$index];
$max  = $data['daily']['temperature_2m_max'][$index];
$min  = $data['daily']['temperature_2m_min'][$index];

// WMO Weather Code mapping
if ($kode === 0) {
    $status = 'Cerah';
    $icon   = '☀️';
    $warna  = '#fff8e1';
    $border = '#ffe082';
    $teks   = '#b7791f';
} elseif ($kode <= 3) {
    $status = 'Berawan';
    $icon   = '⛅';
    $warna  = '#f0f4f8';
    $border = '#b0bec5';
    $teks   = '#455a64';
} elseif ($kode <= 48) {
    $status = 'Berkabut';
    $icon   = '🌫️';
    $warna  = '#f5f5f5';
    $border = '#bdbdbd';
    $teks   = '#616161';
} elseif ($kode <= 67) {
    $status = 'Hujan';
    $icon   = '🌧️';
    $warna  = '#e3f2fd';
    $border = '#90caf9';
    $teks   = '#1565c0';
} elseif ($kode <= 77) {
    $status = 'Salju/Hujan Es';
    $icon   = '🌨️';
    $warna  = '#e8eaf6';
    $border = '#9fa8da';
    $teks   = '#283593';
} elseif ($kode <= 82) {
    $status = 'Hujan Deras';
    $icon   = '⛈️';
    $warna  = '#e3f2fd';
    $border = '#64b5f6';
    $teks   = '#0d47a1';
} else {
    $status = 'Badai Petir';
    $icon   = '⛈️';
    $warna  = '#fce4ec';
    $border = '#f48fb1';
    $teks   = '#880e4f';
}

echo json_encode([
    'forecast' => true,
    'status'   => $status,
    'icon'     => $icon,
    'min'      => $min,
    'max'      => $max,
    'kode'     => $kode,
    'warna'    => $warna,
    'border'   => $border,
    'teks'     => $teks,
]);