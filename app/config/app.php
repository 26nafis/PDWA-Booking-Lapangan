<?php
// ============================================================
//  app/config/app.php — Konstanta & Konfigurasi Global
// ============================================================

defined('APP_ROOT')    || define('APP_ROOT',    dirname(__DIR__));
defined('PUBLIC_ROOT') || define('PUBLIC_ROOT', dirname(APP_ROOT) . '/public');
defined('BASE_URL')    || define('BASE_URL',    '/booking-lapangan/public');
defined('APP_NAME')    || define('APP_NAME',    'SportField Booking');
defined('UPLOAD_PATH') || define('UPLOAD_PATH', PUBLIC_ROOT . '/uploads');
defined('UPLOAD_URL')  || define('UPLOAD_URL',  BASE_URL . '/uploads');

// Jam operasional (06:00 - 22:00)
defined('OPEN_HOUR')   || define('OPEN_HOUR',   6);
defined('CLOSE_HOUR')  || define('CLOSE_HOUR',  22);

// Pajak (11%)
defined('TAX_RATE')    || define('TAX_RATE',    0.11);

// Mulai session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper: return path view
function view(string $path): string {
    return APP_ROOT . '/views/' . $path . '.php';
}

// Helper: format rupiah
function formatRupiah(float $amount): string {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Helper: format tanggal Indonesia
function formatTanggal(string $date): string {
    $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date);
    return $hari[date('w', $ts)] . ', ' . date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}

// Helper: format jam
function formatJam(string $time): string {
    return date('H:i', strtotime($time));
}
