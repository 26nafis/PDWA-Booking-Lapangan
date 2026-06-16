<?php $pageTitle = 'Dashboard'; ?>
<?php ob_start(); ?>
<?php require_once view('components/booking_badge'); ?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
    <?php
    $cards = [
        ['Total Booking',  $stats['total_bookings'],   'bg-blue-50',   'text-blue-600',   'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['Booking Hari Ini',$stats['today_bookings'],  'bg-green-50',  'text-green-600',  'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['Menunggu Konfirmasi',$stats['pending_bookings'],'bg-yellow-50','text-yellow-600','M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z'],
        ['Total Revenue',  formatRupiah($stats['total_revenue']),'bg-purple-50','text-purple-600','M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    ?>
    <?php foreach ($cards as [$label, $value, $bg, $textColor, $iconPath]): ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-medium text-gray-500"><?= $label ?></span>
            <div class="w-9 h-9 <?= $bg ?> rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 <?= $textColor ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $iconPath ?>"/>
                </svg>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900"><?= $value ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Second Row Stats -->
<div class="grid grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm text-center">
        <div class="text-3xl font-bold text-gray-900"><?= $stats['total_lapangan'] ?></div>
        <div class="text-sm text-gray-500 mt-1">Total Lapangan</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm text-center">
        <div class="text-3xl font-bold text-gray-900"><?= $stats['total_customers'] ?></div>
        <div class="text-sm text-gray-500 mt-1">Total Customer</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm text-center">
        <div class="text-3xl font-bold text-yellow-600"><?= $stats['pending_payments'] ?></div>
        <div class="text-sm text-gray-500 mt-1">Pembayaran Pending</div>
        <?php if ($stats['pending_payments'] > 0): ?>
            <a href="<?= BASE_URL ?>/admin/payment" class="text-xs text-primary hover:underline">Verifikasi →</a>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Booking Terbaru</h2>
        <a href="<?= BASE_URL ?>/admin/booking" class="text-sm text-primary hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">#ID</th>
                    <th class="px-6 py-3 text-left">Customer</th>
                    <th class="px-6 py-3 text-left">Lapangan</th>
                    <th class="px-6 py-3 text-left">Tanggal</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($recentBookings as $b): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-gray-500">#<?= $b['id'] ?></td>
                    <td class="px-6 py-3 font-medium text-gray-900"><?= e($b['user_name']) ?></td>
                    <td class="px-6 py-3 text-gray-600"><?= e($b['lapangan_name']) ?></td>
                    <td class="px-6 py-3 text-gray-600"><?= date('d M Y', strtotime($b['booking_date'])) ?></td>
                    <td class="px-6 py-3 font-medium text-primary"><?= formatRupiah((float)$b['total_price']) ?></td>
                    <td class="px-6 py-3"><?= bookingBadge($b['status']) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($recentBookings)): ?>
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada booking</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/admin');
?>
