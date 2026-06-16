<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?>Admin | <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/custom.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: { DEFAULT: '#16a34a', dark: '#15803d', light: '#dcfce7' } } } }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-700">
            <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <div class="font-bold text-sm"><?= APP_NAME ?></div>
                <div class="text-xs text-gray-400">Admin Panel</div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <?php
            $currentUrl = $_GET['url'] ?? 'admin/dashboard';
            function isActiveMenu(string $prefix): string {
                $url = $_GET['url'] ?? '';
                return str_starts_with($url, $prefix) ? 'bg-primary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white';
            }
            ?>
            <a href="<?= BASE_URL ?>/admin/dashboard" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= isActiveMenu('admin/dashboard') ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="<?= BASE_URL ?>/admin/lapangan" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= isActiveMenu('admin/lapangan') ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Lapangan
            </a>
            <a href="<?= BASE_URL ?>/admin/booking" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= isActiveMenu('admin/booking') ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Manajemen Booking
            </a>
            <a href="<?= BASE_URL ?>/admin/payment" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition <?= isActiveMenu('admin/payment') ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Verifikasi Pembayaran
            </a>
            <hr class="border-gray-700 my-2">
            <a href="<?= BASE_URL ?>/home" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Lihat Website
            </a>
            <a href="<?= BASE_URL ?>/auth/logout" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-900/30 hover:text-red-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </a>
        </nav>

        <!-- Admin info -->
        <div class="px-6 py-4 border-t border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-bold">
                    <?= strtoupper(substr($_SESSION['name'] ?? 'A', 0, 1)) ?>
                </div>
                <div>
                    <div class="text-sm font-medium text-white"><?= e($_SESSION['name'] ?? '') ?></div>
                    <div class="text-xs text-gray-400">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-xl font-semibold text-gray-800"><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></h1>
            <div class="text-sm text-gray-500"><?= date('l, d F Y') ?></div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-8">
            <?php require_once view('components/alert'); ?>
            <?= $content ?? '' ?>
        </main>
    </div>
</div>

<script src="<?= BASE_URL ?>/js/app.js"></script>
</body>
</html>
