<?php $pageTitle = 'Beranda'; ?>
<?php ob_start(); ?>

<!-- Hero -->
<section class="bg-gradient-to-br from-green-700 via-green-600 to-emerald-500 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20 flex flex-col lg:flex-row items-center gap-12">
        <div class="flex-1 text-center lg:text-left">
            <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4">⚽ Platform Booking Terpercaya</span>
            <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-4">
                Booking Lapangan<br>Olahraga Favoritmu
            </h1>
            <p class="text-green-100 text-lg mb-8 max-w-lg">Temukan dan pesan lapangan futsal, badminton, basket, hingga tennis — mudah, cepat, dan terpercaya.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                <a href="<?= BASE_URL ?>/lapangan" class="bg-white text-primary font-bold px-8 py-3 rounded-xl hover:bg-green-50 transition text-center">
                    Lihat Lapangan
                </a>
                <?php if (!isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/auth/register" class="bg-white/20 hover:bg-white/30 border border-white/40 text-white font-semibold px-8 py-3 rounded-xl transition text-center">
                    Daftar Gratis
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex-1 hidden lg:flex justify-center">
            <div class="w-80 h-80 bg-white/10 rounded-full flex items-center justify-center">
                <svg class="w-48 h-48 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <?php $stats = [['6+','Lapangan Tersedia'],['4','Jenis Olahraga'],['100%','Pembayaran Aman'],['7:00 - 22:00','Jam Operasional']]; ?>
        <?php foreach ($stats as [$num, $label]): ?>
        <div>
            <div class="text-2xl font-extrabold text-primary"><?= $num ?></div>
            <div class="text-sm text-gray-500 mt-1"><?= $label ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Kategori -->
<section class="max-w-7xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Pilih Olahraga</h2>
    <p class="text-gray-500 text-center mb-8">Berbagai jenis lapangan tersedia untuk kamu</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $catIcons = [
            'Futsal'   => '⚽',
            'Badminton'=> '🏸',
            'Basket'   => '🏀',
            'Tennis'   => '🎾',
        ];
        foreach ($categories as $cat):
            $icon = $catIcons[$cat['name']] ?? '🏟️';
        ?>
        <a href="<?= BASE_URL ?>/lapangan?category=<?= $cat['id'] ?>"
           class="bg-white border border-gray-200 hover:border-primary hover:shadow-md rounded-2xl p-6 text-center group transition">
            <div class="text-4xl mb-3"><?= $icon ?></div>
            <div class="font-semibold text-gray-800 group-hover:text-primary transition"><?= e($cat['name']) ?></div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Lapangan Terbaru -->
<section class="bg-gray-50 py-14">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Lapangan Tersedia</h2>
                <p class="text-gray-500 text-sm mt-1">Pilih dan booking sekarang</p>
            </div>
            <a href="<?= BASE_URL ?>/lapangan" class="text-primary font-semibold hover:underline text-sm">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach (array_slice($lapangan, 0, 6) as $lap): ?>
                <?php require view('components/lapangan_card'); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<?php if (!isLoggedIn()): ?>
<section class="bg-primary py-16 text-white text-center">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-3">Siap Main? Booking Sekarang!</h2>
        <p class="text-green-100 mb-6">Daftar gratis dan nikmati kemudahan booking lapangan olahraga.</p>
        <a href="<?= BASE_URL ?>/auth/register" class="bg-white text-primary font-bold px-10 py-3 rounded-xl hover:bg-green-50 transition inline-block">
            Daftar Gratis Sekarang
        </a>
    </div>
</section>
<?php endif; ?>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
