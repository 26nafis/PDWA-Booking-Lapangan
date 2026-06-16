<?php $pageTitle = 'Manajemen Booking'; ?>
<?php ob_start(); ?>
<?php require_once view('components/booking_badge'); ?>

<!-- Filter -->
<div class="flex flex-wrap gap-2 mb-6">
    <?php
    $filters = [
        '' => 'Semua',
        'pending' => 'Pending',
        'confirmed' => 'Dikonfirmasi',
        'in_progress' => 'Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
    $currentStatus = $_GET['status'] ?? '';
    foreach ($filters as $val => $label):
    ?>
    <a href="<?= BASE_URL ?>/admin/booking<?= $val ? '?status=' . $val : '' ?>"
       class="px-4 py-2 rounded-lg text-sm font-medium transition
              <?= $currentStatus === $val ? 'bg-primary text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
        <?= $label ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
            <tr>
                <th class="px-5 py-3 text-left">#</th>
                <th class="px-5 py-3 text-left">Customer</th>
                <th class="px-5 py-3 text-left">Lapangan</th>
                <th class="px-5 py-3 text-left">Tanggal & Waktu</th>
                <th class="px-5 py-3 text-left">Total</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Bayar</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if (empty($bookings)): ?>
            <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">Belum ada booking</td></tr>
            <?php endif; ?>
            <?php foreach ($bookings as $b): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3 text-gray-400 font-mono">#<?= $b['id'] ?></td>
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-900"><?= e($b['user_name']) ?></div>
                    <div class="text-xs text-gray-400"><?= e($b['user_email']) ?></div>
                </td>
                <td class="px-5 py-3 text-gray-700"><?= e($b['lapangan_name']) ?></td>
                <td class="px-5 py-3 text-gray-600 text-xs">
                    <div><?= date('d M Y', strtotime($b['booking_date'])) ?></div>
                    <div><?= formatJam($b['start_time']) ?> – <?= formatJam($b['end_time']) ?></div>
                </td>
                <td class="px-5 py-3 font-semibold text-primary"><?= formatRupiah((float)$b['total_price']) ?></td>
                <td class="px-5 py-3"><?= bookingBadge($b['status']) ?></td>
                <td class="px-5 py-3">
                    <?php if ($b['payment_status']): ?>
                        <?= paymentBadge($b['payment_status']) ?>
                    <?php else: ?>
                        <span class="text-xs text-gray-400">-</span>
                    <?php endif; ?>
                </td>
                <td class="px-5 py-3 text-center">
                    <a href="<?= BASE_URL ?>/admin/booking/detail/<?= $b['id'] ?>"
                       class="text-primary text-xs font-medium border border-primary/30 px-3 py-1.5 rounded-lg hover:bg-primary/10 transition">
                        Detail
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/admin');
?>
