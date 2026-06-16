<?php $pageTitle = e($lapangan['name']); ?>
<?php ob_start(); ?>

<div class="max-w-7xl mx-auto px-4 py-10">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="<?= BASE_URL ?>/lapangan" class="hover:text-primary">Lapangan</a>
        <span>/</span>
        <span class="text-gray-800"><?= e($lapangan['name']) ?></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kiri: Info -->
        <div class="lg:col-span-2">
            <!-- Gambar -->
            <div class="rounded-2xl overflow-hidden aspect-video bg-gray-100 mb-6">
                <?php $imageSrc = !empty($lapangan['image']) ? UPLOAD_URL . '/' . $lapangan['image'] : 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=800&q=80'; ?>
                <img src="<?= $imageSrc ?>" alt="<?= e($lapangan['name']) ?>" class="w-full h-full object-cover">
            </div>

            <!-- Info -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="bg-green-100 text-green-700 text-sm font-medium px-3 py-1 rounded-full">
                    <?= e($lapangan['category_name']) ?>
                </span>
                <?php $avg = round((float)$lapangan['avg_rating'], 1); ?>
                <?php if ($avg > 0): ?>
                    <span class="flex items-center gap-1 text-sm text-gray-600">
                        <span class="text-yellow-400">★</span> <?= $avg ?> (<?= (int)$lapangan['review_count'] ?> ulasan)
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= e($lapangan['name']) ?></h1>
            <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                <?= e($lapangan['location'] ?? '-') ?>
            </div>
            <p class="text-gray-600 leading-relaxed mb-6"><?= e($lapangan['description'] ?? '') ?></p>

            <!-- Slot Ketersediaan -->
            <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                <h2 class="font-semibold text-gray-800 mb-4">Ketersediaan Slot</h2>
                <form method="GET" class="flex gap-3 mb-5">
                    <input type="date" name="date" value="<?= e($date) ?>" min="<?= date('Y-m-d') ?>"
                           class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <button type="submit" class="bg-primary text-white text-sm px-5 py-2 rounded-lg hover:bg-primary-dark transition">Cek Slot</button>
                </form>

                <div class="grid grid-cols-4 sm:grid-cols-6 gap-2" id="slot-grid">
                    <?php
                    $bookedRanges = [];
                    foreach ($bookedSlots as $slot) {
                        $s = (int)explode(':', $slot['start_time'])[0];
                        $e2 = (int)explode(':', $slot['end_time'])[0];
                        for ($h = $s; $h < $e2; $h++) {
                            $bookedRanges[] = $h;
                        }
                    }
                    for ($hour = OPEN_HOUR; $hour < CLOSE_HOUR; $hour++):
                        $isBooked = in_array($hour, $bookedRanges);
                        $isPast = ($date === date('Y-m-d') && $hour < (int)date('H'));
                    ?>
                    <div class="text-center py-2 rounded-lg text-xs font-medium
                        <?= $isBooked ? 'bg-red-100 text-red-600 cursor-not-allowed' : ($isPast ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-green-100 text-green-700 cursor-pointer hover:bg-green-200') ?>">
                        <?= sprintf('%02d:00', $hour) ?>
                        <?php if ($isBooked): ?><div class="text-[10px] mt-0.5">Penuh</div><?php elseif ($isPast): ?><div class="text-[10px] mt-0.5">Lewat</div><?php else: ?><div class="text-[10px] mt-0.5">Bebas</div><?php endif; ?>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-green-100 rounded inline-block"></span> Tersedia</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-100 rounded inline-block"></span> Terisi</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-gray-200 rounded inline-block"></span> Sudah Lewat</span>
                </div>
            </div>

            <!-- Reviews -->
            <?php if (!empty($reviews)): ?>
            <div>
                <h2 class="font-semibold text-gray-800 mb-4">Ulasan Pelanggan</h2>
                <div class="space-y-4">
                    <?php foreach ($reviews as $review): ?>
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-primary-light rounded-full flex items-center justify-center text-primary font-bold text-sm">
                                    <?= strtoupper(substr($review['user_name'], 0, 1)) ?>
                                </div>
                                <span class="font-medium text-sm text-gray-800"><?= e($review['user_name']) ?></span>
                            </div>
                            <span class="text-yellow-400 text-sm"><?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?></span>
                        </div>
                        <?php if ($review['comment']): ?>
                            <p class="text-sm text-gray-600"><?= e($review['comment']) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Kanan: Booking Card -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-2xl p-6 sticky top-24">
                <div class="text-center mb-5 pb-5 border-b border-gray-100">
                    <div class="text-3xl font-bold text-primary"><?= formatRupiah((float)$lapangan['price_per_hour']) ?></div>
                    <div class="text-gray-400 text-sm">per jam</div>
                </div>

                <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/booking/form/<?= $lapangan['id'] ?>?date=<?= e($date) ?>"
                   class="w-full block text-center bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-xl transition text-sm">
                    🏟️ Booking Sekarang
                </a>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login"
                   class="w-full block text-center bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-xl transition text-sm">
                    Login untuk Booking
                </a>
                <?php endif; ?>

                <div class="mt-5 space-y-3 text-sm text-gray-600">
                    <div class="flex items-center gap-2"><span class="text-green-500">✓</span> Konfirmasi instan</div>
                    <div class="flex items-center gap-2"><span class="text-green-500">✓</span> Pembayaran transfer bank</div>
                    <div class="flex items-center gap-2"><span class="text-green-500">✓</span> Bisa dibatalkan (status pending)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
