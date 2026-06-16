<?php $pageTitle = 'Katalog Lapangan'; ?>
<?php ob_start(); ?>

<div class="max-w-7xl mx-auto px-4 py-10">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Semua Lapangan</h1>
        <p class="text-gray-500 mt-1"><?= count($lapangan) ?> lapangan tersedia</p>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="<?= BASE_URL ?>/lapangan" class="flex flex-col md:flex-row gap-3 mb-8">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="<?= isset($search) ? e($search) : '' ?>"
                   placeholder="Cari nama lapangan..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
        </div>
        <select name="category" class="border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="0">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($categoryId) && $categoryId == $cat['id']) ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
            Filter
        </button>
        <?php if (!empty($search) || !empty($categoryId)): ?>
            <a href="<?= BASE_URL ?>/lapangan" class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-medium px-6 py-2.5 rounded-lg transition text-sm text-center">
                Reset
            </a>
        <?php endif; ?>
    </form>

    <!-- Grid -->
    <?php if (empty($lapangan)): ?>
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-600">Lapangan tidak ditemukan</h3>
            <p class="text-gray-400 text-sm mt-1">Coba kata kunci lain atau reset filter</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($lapangan as $lap): ?>
                <?php require view('components/lapangan_card'); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
