<?php $pageTitle = 'Edit Lapangan'; ?>
<?php ob_start(); ?>

<div class="max-w-2xl">
    <a href="<?= BASE_URL ?>/admin/lapangan" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-5">
        ← Kembali ke Daftar Lapangan
    </a>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="<?= BASE_URL ?>/admin/lapangan/update/<?= $lapangan['id'] ?>" enctype="multipart/form-data" class="space-y-5">
            <?php csrfField(); ?>

            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lapangan</label>
                    <input type="text" name="name" required value="<?= e($lapangan['name']) ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                    <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $lapangan['category_id'] ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga per Jam (Rp)</label>
                    <input type="number" name="price_per_hour" required min="1000" step="1000"
                           value="<?= (float)$lapangan['price_per_hour'] ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi</label>
                    <input type="text" name="location" value="<?= e($lapangan['location'] ?? '') ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition resize-none"><?= e($lapangan['description'] ?? '') ?></textarea>
                </div>

                <!-- Foto -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto Lapangan</label>
                    <?php if (!empty($lapangan['image'])): ?>
                    <div class="mb-3">
                        <img src="<?= UPLOAD_URL . '/' . $lapangan['image'] ?>" class="h-32 rounded-lg object-cover" alt="Foto saat ini">
                        <p class="text-xs text-gray-400 mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                    </div>
                    <?php endif; ?>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-5 text-center cursor-pointer hover:border-primary transition relative">
                        <input type="file" name="image" accept="image/*"
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                               onchange="previewImg(this)">
                        <div id="upload-hint">
                            <p class="text-sm text-gray-500">Upload foto baru (opsional)</p>
                        </div>
                        <img id="preview-img" class="hidden max-h-32 mx-auto rounded-lg" alt="">
                    </div>
                </div>

                <div class="col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_available" id="is_available" value="1"
                           <?= $lapangan['is_available'] ? 'checked' : '' ?>
                           class="w-4 h-4 rounded text-primary border-gray-300">
                    <label for="is_available" class="text-sm text-gray-700">Lapangan tersedia untuk dibooking</label>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-2.5 rounded-lg transition text-sm">
                    Perbarui Lapangan
                </button>
                <a href="<?= BASE_URL ?>/admin/lapangan" class="border border-gray-300 text-gray-600 px-8 py-2.5 rounded-lg hover:bg-gray-50 transition text-sm text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('upload-hint').style.display = 'none';
            const prev = document.getElementById('preview-img');
            prev.src = e.target.result;
            prev.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php
$content = ob_get_clean();
require_once view('layouts/admin');
?>
