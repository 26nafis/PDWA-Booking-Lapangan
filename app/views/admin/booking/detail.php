<?php $pageTitle = 'Detail Booking #' . $booking['id']; ?>
<?php ob_start(); ?>
<?php require_once view('components/booking_badge'); ?>

<a href="<?= BASE_URL ?>/admin/booking" class="text-sm text-gray-500 hover:text-primary mb-5 inline-flex items-center gap-1">
    ← Kembali
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
    <!-- Info Booking -->
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Info Booking #<?= $booking['id'] ?></h2>
                <?= bookingBadge($booking['status']) ?>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Customer</span>
                    <span class="font-medium"><?= e($booking['user_name']) ?></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Email</span>
                    <span><?= e($booking['user_email']) ?></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">No. HP</span>
                    <span><?= e($booking['user_phone'] ?? '-') ?></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Lapangan</span>
                    <span class="font-medium"><?= e($booking['lapangan_name']) ?></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Tanggal</span>
                    <span><?= formatTanggal($booking['booking_date']) ?></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Waktu</span>
                    <span><?= formatJam($booking['start_time']) ?> – <?= formatJam($booking['end_time']) ?> (<?= $booking['duration_hours'] ?> jam)</span>
                </div>
                <?php if ($booking['notes']): ?>
                <div class="flex justify-between text-gray-600">
                    <span class="text-gray-400">Catatan</span>
                    <span class="text-right max-w-xs"><?= e($booking['notes']) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between font-bold text-gray-900 border-t border-gray-100 pt-3 mt-3">
                    <span>Total</span>
                    <span class="text-primary text-lg"><?= formatRupiah((float)$booking['total_price']) ?></span>
                </div>
            </div>
        </div>

        <!-- Bukti Pembayaran -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Bukti Pembayaran</h2>
            <?php if ($booking['payment_status']): ?>
            <div class="flex items-center gap-3 mb-4">
                <span class="text-sm text-gray-500">Status:</span>
                <?= paymentBadge($booking['payment_status']) ?>
            </div>
            <?php if (!empty($booking['proof_image'])): ?>
                <img src="<?= UPLOAD_URL . '/' . $booking['proof_image'] ?>"
                     class="max-w-sm rounded-xl border shadow-sm" alt="Bukti Transfer">
            <?php endif; ?>
            <?php else: ?>
                <p class="text-sm text-gray-400">Belum ada bukti pembayaran.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Update Status -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-8">
            <h2 class="font-semibold text-gray-800 mb-4">Update Status Booking</h2>
            <form method="POST" action="<?= BASE_URL ?>/admin/booking/update/<?= $booking['id'] ?>">
                <?php csrfField(); ?>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition mb-4">
                    <?php foreach (['pending','confirmed','in_progress','completed','cancelled'] as $st): ?>
                        <option value="<?= $st ?>" <?= $booking['status'] === $st ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_', ' ', $st)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition text-sm">
                    Update Status
                </button>
            </form>

            <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400 space-y-1">
                <div>Dibuat: <?= date('d M Y H:i', strtotime($booking['created_at'])) ?></div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/admin');
?>
