<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?><?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/custom.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#16a34a', dark: '#15803d', light: '#dcfce7' },
                        accent:  { DEFAULT: '#f97316', dark: '#ea580c' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<!-- Navbar -->
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="<?= BASE_URL ?>/home" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900 text-lg"><?= APP_NAME ?></span>
            </a>

            <!-- Nav Links (desktop) -->
            <div class="hidden md:flex items-center gap-6">
                <a href="<?= BASE_URL ?>/home" class="text-gray-600 hover:text-primary font-medium transition">Beranda</a>
                <a href="<?= BASE_URL ?>/lapangan" class="text-gray-600 hover:text-primary font-medium transition">Lapangan</a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/booking/history" class="text-gray-600 hover:text-primary font-medium transition">Booking Saya</a>
                <?php endif; ?>
            </div>

            <!-- Auth -->
            <div class="flex items-center gap-3">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="text-sm text-primary font-medium hover:underline">Dashboard Admin</a>
                    <?php endif; ?>
                    <div class="relative group">
                        <button class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-full px-4 py-2 text-sm font-medium transition">
                            <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold">
                                <?= strtoupper(substr($_SESSION['name'], 0, 1)) ?>
                            </div>
                            <?= e($_SESSION['name']) ?>
                        </button>
                        <div class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <a href="<?= BASE_URL ?>/booking/history" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl">Booking Saya</a>
                            <hr class="border-gray-100">
                            <a href="<?= BASE_URL ?>/auth/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-xl">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/auth/login" class="text-sm font-medium text-gray-600 hover:text-primary transition">Masuk</a>
                    <a href="<?= BASE_URL ?>/auth/register" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2 rounded-lg transition">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Message -->
<div class="max-w-7xl mx-auto w-full px-4 pt-4">
    <?php require_once view('components/alert'); ?>
</div>

<!-- Main Content -->
<main class="flex-1">
    <?= $content ?? '' ?>
</main>

<!-- Footer -->
<footer class="bg-white border-t border-gray-200 mt-16">
    <div class="max-w-7xl mx-auto px-4 py-8 text-center text-gray-500 text-sm">
        &copy; <?= date('Y') ?> <?= APP_NAME ?>. Semua hak dilindungi.
    </div>
</footer>

<script src="<?= BASE_URL ?>/js/app.js"></script>
</body>
</html>
