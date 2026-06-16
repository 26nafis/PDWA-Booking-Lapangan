<?php $pageTitle = '404 - Halaman Tidak Ditemukan'; ?>
<?php ob_start(); ?>

<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center">
        <div class="text-8xl font-black text-gray-200 mb-4">404</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Halaman Tidak Ditemukan</h1>
        <p class="text-gray-500 mb-8">Halaman yang kamu cari tidak ada atau sudah dipindahkan.</p>
        <a href="<?= BASE_URL ?>/home"
           class="bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-3 rounded-xl transition">
            Kembali ke Beranda
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/views/layouts/main.php';
?>
