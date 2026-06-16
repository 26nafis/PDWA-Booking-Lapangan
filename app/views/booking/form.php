<?php $pageTitle = 'Booking Lapangan'; ?>
<?php ob_start(); ?>

<div class="max-w-3xl mx-auto px-4 py-10">
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="<?= BASE_URL ?>/lapangan" class="hover:text-primary">Lapangan</a>
        <span>/</span>
        <a href="<?= BASE_URL ?>/lapangan/detail/<?= $lapangan['id'] ?>" class="hover:text-primary"><?= e($lapangan['name']) ?></a>
        <span>/</span>
        <span class="text-gray-800">Booking</span>
    </nav>

    <h1 class="text-2xl font-bold text-gray-900 mb-1">Form Booking</h1>
    <p class="text-gray-500 text-sm mb-8">Pilih tanggal dan jam yang kamu inginkan</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Lapangan Info -->
        <div class="bg-gray-50 border-b border-gray-100 p-5 flex items-center gap-4">
            <?php $imageSrc = !empty($lapangan['image']) ? UPLOAD_URL . '/' . $lapangan['image'] : 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=200&q=80'; ?>
            <img src="<?= $imageSrc ?>" class="w-16 h-16 rounded-xl object-cover flex-shrink-0" alt="">
            <div>
                <div class="font-semibold text-gray-900"><?= e($lapangan['name']) ?></div>
                <div class="text-sm text-gray-500"><?= e($lapangan['category_name']) ?> · <?= e($lapangan['location'] ?? '') ?></div>
                <div class="text-primary font-bold mt-1"><?= formatRupiah((float)$lapangan['price_per_hour']) ?>/jam</div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="<?= BASE_URL ?>/booking/store" class="p-6 space-y-6">
            <?php csrfField(); ?>
            <input type="hidden" name="lapangan_id" value="<?= $lapangan['id'] ?>">

            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Booking</label>
                <input type="date" name="booking_date" id="booking_date" required
                       min="<?= date('Y-m-d') ?>" value="<?= e($date) ?>"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
            </div>

            <!-- Jam -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                    <select name="start_time" id="start_time" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
                        <option value="">-- Pilih Jam --</option>
                        <?php for ($h = OPEN_HOUR; $h < CLOSE_HOUR; $h++): ?>
                            <?php $val = sprintf('%02d:00:00', $h); ?>
                            <?php $isBooked = false;
                            foreach ($bookedSlots as $s) {
                                if ($val >= $s['start_time'] && $val < $s['end_time']) { $isBooked = true; break; }
                            } ?>
                            <option value="<?= $val ?>" <?= $isBooked ? 'disabled' : '' ?>>
                                <?= sprintf('%02d:00', $h) ?> <?= $isBooked ? '(Terisi)' : '' ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
                    <select name="end_time" id="end_time" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition">
                        <option value="">-- Pilih Jam --</option>
                        <?php for ($h = OPEN_HOUR + 1; $h <= CLOSE_HOUR; $h++): ?>
                            <option value="<?= sprintf('%02d:00:00', $h) ?>"><?= sprintf('%02d:00', $h) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <!-- Kalkulasi otomatis -->
            <div id="calc-box" class="hidden bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Durasi</span>
                    <span id="calc-durasi">-</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Harga/jam</span>
                    <span><?= formatRupiah((float)$lapangan['price_per_hour']) ?></span>
                </div>
                <div class="flex justify-between font-bold text-primary border-t border-green-200 pt-2 mt-2">
                    <span>Total</span>
                    <span id="calc-total">-</span>
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                <textarea name="notes" rows="3" placeholder="Jumlah pemain, kebutuhan khusus, dll..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition resize-none"></textarea>
            </div>

            <button type="submit" id="submit-btn"
                    class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-xl transition text-sm">
                Buat Booking
            </button>
        </form>
    </div>
</div>

<script>
const pricePerHour = <?= (float)$lapangan['price_per_hour'] ?>;
const startSel = document.getElementById('start_time');
const endSel   = document.getElementById('end_time');
const calcBox  = document.getElementById('calc-box');

function updateCalc() {
    const s = startSel.value, e = endSel.value;
    if (!s || !e) { calcBox.classList.add('hidden'); return; }
    const diff = (new Date('2000-01-01T' + e) - new Date('2000-01-01T' + s)) / 3600000;
    if (diff <= 0) { calcBox.classList.add('hidden'); return; }
    const total = diff * pricePerHour;
    document.getElementById('calc-durasi').textContent = diff + ' jam';
    document.getElementById('calc-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    calcBox.classList.remove('hidden');
}

startSel.addEventListener('change', () => {
    // Reset end options agar tidak kurang dari start
    const startH = parseInt(startSel.value);
    Array.from(endSel.options).forEach(opt => {
        const h = parseInt(opt.value);
        opt.disabled = h <= startH;
    });
    if (parseInt(endSel.value) <= startH) endSel.value = '';
    updateCalc();
});
endSel.addEventListener('change', updateCalc);

// Change date → reload halaman
document.getElementById('booking_date').addEventListener('change', function() {
    const lapId = <?= $lapangan['id'] ?>;
    window.location.href = '<?= BASE_URL ?>/booking/form/' + lapId + '?date=' + this.value;
});
</script>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
