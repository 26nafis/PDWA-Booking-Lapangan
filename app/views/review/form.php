<?php $pageTitle = 'Beri Rating'; ?>
<?php ob_start(); ?>

<div class="max-w-xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Beri Ulasan</h1>
    <p class="text-gray-500 text-sm mb-8">Bagikan pengalamanmu bermain di <?= e($booking['lapangan_name']) ?></p>

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <form method="POST" action="<?= BASE_URL ?>/review/store">
            <?php csrfField(); ?>
            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">

            <!-- Star Rating -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Penilaian</label>
                <div class="flex gap-2" id="star-container">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="<?= $i ?>" class="hidden" <?= $i === 5 ? 'checked' : '' ?>>
                        <svg class="w-10 h-10 star-icon text-gray-300 hover:text-yellow-400 transition" data-val="<?= $i ?>"
                             fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </label>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Komentar -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Komentar (opsional)</label>
                <textarea name="comment" rows="4"
                          placeholder="Ceritakan pengalaman kamu bermain di lapangan ini..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition resize-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-xl transition text-sm">
                Kirim Ulasan
            </button>
        </form>
    </div>
</div>

<script>
const stars = document.querySelectorAll('.star-icon');
function updateStars(val) {
    stars.forEach(s => {
        s.classList.toggle('text-yellow-400', parseInt(s.dataset.val) <= val);
        s.classList.toggle('text-gray-300', parseInt(s.dataset.val) > val);
    });
}
updateStars(5);
stars.forEach(s => {
    s.addEventListener('click', () => {
        const val = parseInt(s.dataset.val);
        s.closest('label').querySelector('input').checked = true;
        updateStars(val);
    });
    s.addEventListener('mouseover', () => updateStars(parseInt(s.dataset.val)));
    s.addEventListener('mouseout', () => {
        const checked = document.querySelector('input[name="rating"]:checked');
        updateStars(checked ? parseInt(checked.value) : 5);
    });
});
</script>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
