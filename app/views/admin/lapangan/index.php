<?php $pageTitle = 'Manajemen Lapangan'; ?>
<?php ob_start(); ?>

<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm"><?= count($lapangan) ?> lapangan terdaftar</p>
    <a href="<?= BASE_URL ?>/admin/lapangan/create"
       class="bg-primary hover:bg-primary-dark text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Lapangan
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
            <tr>
                <th class="px-6 py-3 text-left">Lapangan</th>
                <th class="px-6 py-3 text-left">Kategori</th>
                <th class="px-6 py-3 text-left">Harga/Jam</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if (empty($lapangan)): ?>
            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">Belum ada lapangan</td></tr>
            <?php endif; ?>
            <?php foreach ($lapangan as $lap): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <?php $img = !empty($lap['image']) ? UPLOAD_URL . '/' . $lap['image'] : 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=100&q=80'; ?>
                        <img src="<?= $img ?>" class="w-12 h-12 rounded-lg object-cover flex-shrink-0" alt="">
                        <div>
                            <div class="font-medium text-gray-900"><?= e($lap['name']) ?></div>
                            <div class="text-xs text-gray-400 mt-0.5"><?= e($lap['location'] ?? '-') ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600"><?= e($lap['category_name'] ?? '-') ?></td>
                <td class="px-6 py-4 font-semibold text-primary"><?= formatRupiah((float)$lap['price_per_hour']) ?></td>
                <td class="px-6 py-4">
                    <?php if ($lap['is_available']): ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Tersedia
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Tidak Tersedia
                        </span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="<?= BASE_URL ?>/admin/lapangan/edit/<?= $lap['id'] ?>"
                           class="text-blue-600 hover:text-blue-800 text-xs font-medium border border-blue-200 px-3 py-1.5 rounded-lg hover:bg-blue-50 transition">
                            Edit
                        </a>
                        <form method="POST" action="<?= BASE_URL ?>/admin/lapangan/delete/<?= $lap['id'] ?>"
                              onsubmit="return confirm('Hapus lapangan ini? Semua booking terkait juga akan terhapus.')">
                            <?php csrfField(); ?>
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
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
