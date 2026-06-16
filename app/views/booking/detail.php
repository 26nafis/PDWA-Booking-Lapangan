<?php $pageTitle = 'Detail Booking #' . $booking['id']; ?>
<?php ob_start(); ?>
<?php require_once view('components/booking_badge'); ?>

<div class="max-w-3xl mx-auto px-4 py-10">
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="<?= BASE_URL ?>/booking/history" class="hover:text-primary">Booking Saya</a>
        <span>/</span>
        <span class="text-gray-800">Booking #<?= $booking['id'] ?></span>
    </nav>

    <!-- Status Tracker -->
    <?php
    $steps = ['pending' => 0, 'confirmed' => 1, 'in_progress' => 2, 'completed' => 3];
    $currentStep = $steps[$booking['status']] ?? ($booking['status'] === 'cancelled' ? -1 : 0);
    $stepLabels = ['Menunggu', 'Dikonfirmasi', 'Berlangsung', 'Selesai'];
    ?>
    <?php if ($booking['status'] !== 'cancelled'): ?>
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="font-semibold text-gray-700 mb-5 text-sm">Status Booking</h2>
        <div class="flex items-center">
            <?php foreach ($stepLabels as $i => $label): ?>
                <div class="flex-1 flex flex-col items-center relative">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center z-10
                        <?= $i <= $currentStep ? 'bg-primary text-white' : 'bg-gray-200 text-gray-400' ?>">
                        <?php if ($i < $currentStep): ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <?php else: ?>
                            <span class="text-xs font-bold"><?= $i + 1 ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="text-xs mt-2 font-medium <?= $i <= $currentStep ? 'text-primary' : 'text-gray-400' ?>"><?= $label ?></span>
                    <?php if ($i < count($stepLabels) - 1): ?>
                        <div class="absolute top-4 left-1/2 w-full h-0.5 <?= $i < $currentStep ? 'bg-primary' : 'bg-gray-200' ?>"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="text-red-700 font-medium text-sm">Booking ini telah dibatalkan</span>
    </div>
    <?php endif; ?>

    <!-- Info Booking -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800">Detail Pesanan</h2>
            <?= bookingBadge($booking['status']) ?>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between text-gray-600">
                <span class="text-gray-400">Lapangan</span>
                <span class="font-medium"><?= e($booking['lapangan_name']) ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span class="text-gray-400">Kategori</span>
                <span><?= e($booking['category_name']) ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span class="text-gray-400">Lokasi</span>
                <span><?= e($booking['location'] ?? '-') ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span class="text-gray-400">Tanggal</span>
                <span><?= formatTanggal($booking['booking_date']) ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span class="text-gray-400">Waktu</span>
                <span><?= formatJam($booking['start_time']) ?> – <?= formatJam($booking['end_time']) ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span class="text-gray-400">Durasi</span>
                <span><?= $booking['duration_hours'] ?> jam</span>
            </div>
            <?php if ($booking['notes']): ?>
            <div class="flex justify-between text-gray-600">
                <span class="text-gray-400">Catatan</span>
                <span class="text-right max-w-xs"><?= e($booking['notes']) ?></span>
            </div>
            <?php endif; ?>
            <div class="flex justify-between font-bold text-gray-900 border-t border-gray-100 pt-3">
                <span>Total Pembayaran</span>
                <span class="text-primary"><?= formatRupiah((float)$booking['total_price']) ?></span>
            </div>
        </div>
    </div>

    <!-- Status Pembayaran -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">Status Pembayaran</h2>
        <?php if ($booking['payment_status']): ?>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Status</span>
                <?= paymentBadge($booking['payment_status']) ?>
            </div>
            <?php if (!empty($booking['proof_image'])): ?>
            <div class="mt-4">
                <p class="text-xs text-gray-400 mb-2">Bukti Transfer</p>
                <img src="<?= UPLOAD_URL . '/' . $booking['proof_image'] ?>" class="w-40 rounded-lg border" alt="Bukti bayar">
            </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-sm text-gray-500 mb-3">Belum ada bukti pembayaran yang diupload.</p>
            <?php if ($booking['status'] !== 'cancelled'): ?>
            <a href="<?= BASE_URL ?>/payment/upload/<?= $booking['id'] ?>"
               class="inline-block bg-accent hover:bg-accent-dark text-white text-sm font-semibold px-5 py-2 rounded-lg transition">
                Upload Bukti Pembayaran
            </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Actions -->
    <div class="flex gap-3 flex-wrap">
        <a href="<?= BASE_URL ?>/booking/history" class="border border-gray-300 text-gray-700 text-sm px-5 py-2.5 rounded-lg hover:bg-gray-50 transition">
            ← Kembali
        </a>
        <?php if ($booking['status'] === 'completed' && !$hasReviewed): ?>
        <a href="<?= BASE_URL ?>/review/form/<?= $booking['id'] ?>"
           class="bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
            ⭐ Beri Rating
        </a>
        <?php endif; ?>
        <?php if ($booking['status'] === 'pending'): ?>
        <form method="POST" action="<?= BASE_URL ?>/booking/cancel/<?= $booking['id'] ?>"
              onsubmit="return confirm('Yakin ingin membatalkan?')">
            <?php csrfField(); ?>
            <button type="submit" class="bg-red-50 text-red-600 border border-red-200 text-sm px-5 py-2.5 rounded-lg hover:bg-red-100 transition">
                Batalkan Booking
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
