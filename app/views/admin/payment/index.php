<?php $pageTitle = 'Verifikasi Pembayaran'; ?>
<?php ob_start(); ?>
<?php require_once view('components/booking_badge'); ?>

<div class="space-y-5">
    <?php if (empty($payments)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <p class="text-gray-400">Belum ada data pembayaran</p>
        </div>
    <?php endif; ?>

    <?php foreach ($payments as $p): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex flex-col lg:flex-row">
            <!-- Bukti Transfer -->
            <div class="lg:w-48 bg-gray-50 p-4 flex items-center justify-center border-r border-gray-100">
                <?php if (!empty($p['proof_image'])): ?>
                    <a href="<?= UPLOAD_URL . '/' . $p['proof_image'] ?>" target="_blank">
                        <img src="<?= UPLOAD_URL . '/' . $p['proof_image'] ?>"
                             class="max-h-40 rounded-lg object-contain hover:opacity-90 transition cursor-zoom-in" alt="Bukti Transfer">
                    </a>
                <?php else: ?>
                    <div class="text-gray-300 text-sm">Tidak ada gambar</div>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div class="flex-1 p-5">
                <div class="flex items-start justify-between flex-wrap gap-3 mb-3">
                    <div>
                        <div class="font-semibold text-gray-900"><?= e($p['user_name']) ?></div>
                        <div class="text-xs text-gray-500"><?= e($p['lapangan_name']) ?> · <?= date('d M Y', strtotime($p['booking_date'])) ?></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <?= paymentBadge($p['status']) ?>
                        <span class="font-bold text-primary"><?= formatRupiah((float)$p['total_price']) ?></span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 text-sm text-gray-600 mb-4">
                    <div>
                        <span class="text-xs text-gray-400 block">Booking ID</span>
                        #<?= $p['booking_id'] ?>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Metode</span>
                        <?= e($p['payment_method'] ?? '-') ?>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block">Diupload</span>
                        <?= date('d M Y H:i', strtotime($p['created_at'])) ?>
                    </div>
                </div>

                <!-- Action Form -->
                <?php if ($p['status'] === 'pending'): ?>
                <form method="POST" action="<?= BASE_URL ?>/admin/payment/verify/<?= $p['id'] ?>" class="flex gap-2 flex-wrap items-end">
                    <?php csrfField(); ?>
                    <div class="flex-1 min-w-48">
                        <input type="text" name="notes" placeholder="Catatan (opsional)"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
                    </div>
                    <button type="submit" name="status" value="verified"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded-lg transition text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Verifikasi
                    </button>
                    <button type="submit" name="status" value="rejected"
                            onclick="return confirm('Tolak pembayaran ini?')"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold px-5 py-2 rounded-lg transition text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak
                    </button>
                </form>
                <?php else: ?>
                    <p class="text-xs text-gray-400">
                        <?= $p['status'] === 'verified' ? '✓ Diverifikasi' : '✕ Ditolak' ?>
                        pada <?= $p['verified_at'] ? date('d M Y H:i', strtotime($p['verified_at'])) : '-' ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/admin');
?>
