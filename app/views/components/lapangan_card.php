<?php
// Expects: $lap (array from Lapangan model)
$imageSrc = !empty($lap['image'])
    ? UPLOAD_URL . '/' . $lap['image']
    : 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=600&q=80';
$avgRating = round((float)($lap['avg_rating'] ?? 0), 1);
$stars = str_repeat('★', (int)$avgRating) . str_repeat('☆', 5 - (int)$avgRating);
?>
<div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 transition group">
    <!-- Gambar -->
    <a href="<?= BASE_URL ?>/lapangan/detail/<?= $lap['id'] ?>">
        <div class="aspect-video overflow-hidden bg-gray-100">
            <img src="<?= $imageSrc ?>" alt="<?= e($lap['name']) ?>"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        </div>
    </a>

    <!-- Body -->
    <div class="p-5">
        <div class="flex items-start justify-between gap-2 mb-2">
            <a href="<?= BASE_URL ?>/lapangan/detail/<?= $lap['id'] ?>">
                <h3 class="font-semibold text-gray-900 hover:text-primary transition leading-snug"><?= e($lap['name']) ?></h3>
            </a>
            <span class="flex-shrink-0 text-xs bg-green-100 text-green-700 font-medium px-2 py-0.5 rounded-full">
                <?= e($lap['category_name'] ?? '') ?>
            </span>
        </div>

        <div class="flex items-center gap-1 text-sm text-gray-500 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            <?= e($lap['location'] ?? '-') ?>
        </div>

        <!-- Rating -->
        <div class="flex items-center gap-2 mb-4">
            <span class="text-yellow-400 text-sm tracking-tight"><?= $stars ?></span>
            <span class="text-xs text-gray-400"><?= $avgRating > 0 ? $avgRating . ' (' . (int)$lap['review_count'] . ')' : 'Belum ada review' ?></span>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div>
                <span class="text-primary font-bold text-lg"><?= formatRupiah((float)$lap['price_per_hour']) ?></span>
                <span class="text-gray-400 text-xs">/jam</span>
            </div>
            <a href="<?= BASE_URL ?>/lapangan/detail/<?= $lap['id'] ?>"
               class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                Booking
            </a>
        </div>
    </div>
</div>
