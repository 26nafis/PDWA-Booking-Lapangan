<?php $pageTitle = 'Booking Saya'; ?>
<?php ob_start(); ?>
<?php require_once view('components/booking_badge'); ?>

<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Booking Saya</h1>
            <p class="text-gray-500 text-sm mt-1"><?= count($bookings) ?> total booking</p>
        </div>
        <a href="<?= BASE_URL ?>/lapangan" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
            + Booking Baru
        </a>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-600 mb-1">Belum Ada Booking</h3>
            <p class="text-gray-400 text-sm mb-5">Yuk, booking lapangan olahraga sekarang!</p>
            <a href="<?= BASE_URL ?>/lapangan" class="bg-primary text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-primary-dark transition text-sm">
                Lihat Lapangan
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($bookings as $b): ?>
            <?php
            $imageSrc = !empty($b['lapangan_image']) ? UPLOAD_URL . '/' . $b['lapangan_image'] : 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=200&q=80';
            ?>
            <div class="bg-white rounded-2xl border border-gray-200 hover:shadow-md transition overflow-hidden">
                <div class="flex flex-col sm:flex-row">
                    <img src="<?= $imageSrc ?>" class="w-full sm:w-36 h-36 object-cover flex-shrink-0" alt="">
                    <div class="flex-1 p-5">
                        <div class="flex items-start justify-between flex-wrap gap-2 mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900"><?= e($b['lapangan_name']) ?></h3>
                                <div class="text-xs text-gray-500"><?= e($b['category_name']) ?></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <?= bookingBadge($b['status']) ?>
                                <?php if ($b['payment_status']): ?>
                                    <?= paymentBadge($b['payment_status']) ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm text-gray-600 mb-4">
                            <div>
                                <span class="text-xs text-gray-400 block">Tanggal</span>
                                <?= formatTanggal($b['booking_date']) ?>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Waktu</span>
                                <?= formatJam($b['start_time']) ?> – <?= formatJam($b['end_time']) ?>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Total</span>
                                <span class="font-semibold text-primary"><?= formatRupiah((float)$b['total_price']) ?></span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="<?= BASE_URL ?>/booking/detail/<?= $b['id'] ?>"
                               class="text-xs border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
                               Detail
                            </a>

                            <?php if ($b['status'] === 'pending' && !$b['payment_status']): ?>
                            <a href="<?= BASE_URL ?>/payment/upload/<?= $b['id'] ?>"
                               class="text-xs bg-accent hover:bg-accent-dark text-white px-3 py-1.5 rounded-lg transition font-medium">
                               Upload Bukti Bayar
                            </a>
                            <?php endif; ?>

                            <?php if ($b['status'] === 'completed' && !$b['payment_status']): ?>
                            <a href="<?= BASE_URL ?>/review/form/<?= $b['id'] ?>"
                               class="text-xs bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1.5 rounded-lg hover:bg-yellow-100 transition">
                               ⭐ Beri Rating
                            </a>
                            <?php endif; ?>

                            <?php if ($b['status'] === 'pending'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/booking/cancel/<?= $b['id'] ?>"
                                  onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                <?php csrfField(); ?>
                                <button type="submit" class="text-xs text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                    Batalkan
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
