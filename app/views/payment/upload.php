<?php $pageTitle = 'Upload Bukti Pembayaran'; ?>
<?php ob_start(); ?>

<div class="max-w-2xl mx-auto px-4 py-10">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="<?= BASE_URL ?>/booking/history" class="hover:text-primary">Booking Saya</a> /
        <span class="text-gray-800">Upload Pembayaran</span>
    </nav>

    <h1 class="text-2xl font-bold text-gray-900 mb-1">Upload Bukti Pembayaran</h1>
    <p class="text-gray-500 text-sm mb-8">Upload bukti transfer agar pesanan kamu diproses oleh admin</p>

    <!-- Info Booking -->
    <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-6">
        <h2 class="font-semibold text-green-800 mb-3">Ringkasan Booking</h2>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between text-green-700">
                <span>Lapangan</span>
                <span class="font-medium"><?= e($booking['lapangan_name']) ?></span>
            </div>
            <div class="flex justify-between text-green-700">
                <span>Tanggal</span>
                <span><?= formatTanggal($booking['booking_date']) ?></span>
            </div>
            <div class="flex justify-between text-green-700">
                <span>Waktu</span>
                <span><?= formatJam($booking['start_time']) ?> – <?= formatJam($booking['end_time']) ?></span>
            </div>
            <div class="flex justify-between font-bold text-green-800 border-t border-green-200 pt-2 mt-2">
                <span>Total</span>
                <span><?= formatRupiah((float)$booking['total_price']) ?></span>
            </div>
        </div>
    </div>

    <!-- Rekening Tujuan (muncul saat non-QRIS) -->
    <div id="box-transfer" class="bg-white border border-gray-200 rounded-2xl p-5 mb-6">
        <h2 class="font-semibold text-gray-800 mb-3">Rekening Tujuan Transfer</h2>
        <div class="space-y-2 text-sm text-gray-700">
            <div class="flex justify-between"><span class="text-gray-500">Bank</span><span class="font-medium">BCA</span></div>
            <div class="flex justify-between"><span class="text-gray-500">No. Rekening</span><span class="font-bold text-lg font-mono">1234567890</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Atas Nama</span><span><?= APP_NAME ?></span></div>
            <div class="flex justify-between pt-2 border-t border-gray-100">
                <span class="text-gray-500">Jumlah Transfer</span>
                <span class="font-bold text-primary text-lg"><?= formatRupiah((float)$booking['total_price']) ?></span>
            </div>
        </div>
    </div>

    <!-- QRIS Box (muncul saat pilih QRIS/GoPay/OVO) -->
    <div id="box-qris" style="display:none" class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 text-center">
        <h2 class="font-semibold text-gray-800 mb-1">Scan QR Code</h2>
        <p class="text-xs text-gray-400 mb-4">Scan pakai aplikasi mobile banking / e-wallet kamu</p>
        <!-- QR Code di-generate oleh JS -->
        <div id="qris-canvas" class="flex justify-center mb-3"></div>
        <div class="inline-block bg-gray-50 rounded-xl px-5 py-2 text-sm text-gray-600 mb-2">
            Nominal: <span class="font-bold text-primary"><?= formatRupiah((float)$booking['total_price']) ?></span>
        </div>
        <p class="text-xs text-gray-400">Setelah bayar, screenshot bukti pembayaran lalu upload di bawah</p>
    </div>

    <!-- Form Upload -->
    <?php if ($existing && $existing['status'] !== 'rejected'): ?>
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-center">
            <p class="text-blue-700 font-medium">Bukti pembayaran sudah diupload.</p>
            <p class="text-blue-500 text-sm mt-1">Menunggu verifikasi dari admin.</p>
        </div>
    <?php else: ?>
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <?php if ($existing && $existing['status'] === 'rejected'): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-5 text-sm text-red-700">
                <strong>Ditolak:</strong> <?= e($existing['notes'] ?? 'Bukti pembayaran tidak valid') ?>. Silakan upload ulang.
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/payment/uploadPost" enctype="multipart/form-data">
            <?php csrfField(); ?>
            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                        onchange="handleMethodChange(this.value)">
                    <option value="">-- Pilih Metode --</option>
                    <option>Transfer Bank BCA</option>
                    <option>Transfer Bank BNI</option>
                    <option>Transfer Bank Mandiri</option>
                    <option value="QRIS">QRIS</option>
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="Dana">Dana</option>
                </select>
            </div>

            <!-- Upload Area -->
            <div class="mb-6">
                <p class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran / Screenshot</p>

                <input type="file" name="proof_image" accept="image/*" required
                       id="file-input" style="display:none"
                       onchange="previewImage(this)">

                <label for="file-input"
                       id="drop-zone"
                       style="display:block; border:2px dashed #d1d5db; border-radius:12px; padding:2rem; text-align:center; cursor:pointer; transition:border-color 0.2s;"
                       onmouseover="this.style.borderColor='#16a34a'"
                       onmouseout="this.style.borderColor='#d1d5db'">
                    <div id="upload-placeholder">
                        <svg style="width:40px;height:40px;color:#d1d5db;margin:0 auto 8px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p id="upload-hint-text" style="font-size:0.875rem;color:#6b7280;font-weight:500">Klik di sini untuk pilih foto bukti transfer</p>
                        <p style="font-size:0.75rem;color:#9ca3af;margin-top:4px">JPG, PNG, WebP · Maksimal 5MB</p>
                    </div>
                    <img id="preview-img" style="display:none;max-height:200px;margin:0 auto;border-radius:8px" alt="Preview">
                </label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 rounded-xl transition text-sm">
                Upload Bukti Pembayaran
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>

<!-- QR Code library dari CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
// isi ini dengan QRIS string asli kamu nanti
// format: string panjang yang dimulai dari "00020101..."
// untuk demo pakai URL bisnis kamu atau nomor HP
var QRIS_STRING = 'https://sportfield.com/pay?amount=<?= (int)$booking['total_price'] ?>&ref=<?= $booking['id'] ?>';
var MERCHANT_NAME = '<?= APP_NAME ?>';

var qrGenerated = false;

function handleMethodChange(val) {
    var isQris = ['QRIS', 'GoPay', 'OVO', 'Dana'].includes(val);

    // Toggle box rekening vs box QRIS
    document.getElementById('box-transfer').style.display = isQris ? 'none' : 'block';
    document.getElementById('box-qris').style.display     = isQris ? 'block' : 'none';

    // Update hint text upload
    var hintEl = document.getElementById('upload-hint-text');
    if (isQris) {
        hintEl.textContent = 'Upload screenshot bukti pembayaran dari aplikasi kamu';
    } else {
        hintEl.textContent = 'Klik di sini untuk pilih foto bukti transfer';
    }

    // Generate QR sekali saja
    if (isQris && !qrGenerated) {
        var container = document.getElementById('qris-canvas');
        container.innerHTML = '';
        new QRCode(container, {
            text: QRIS_STRING,
            width: 200,
            height: 200,
            colorDark: '#111827',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
        qrGenerated = true;
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('upload-placeholder').style.display = 'none';
            var preview = document.getElementById('preview-img');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag & drop
var dropZone = document.getElementById('drop-zone');
if (dropZone) {
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#16a34a';
        this.style.backgroundColor = '#f0fdf4';
    });
    dropZone.addEventListener('dragleave', function() {
        this.style.borderColor = '#d1d5db';
        this.style.backgroundColor = '';
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#d1d5db';
        this.style.backgroundColor = '';
        var file = e.dataTransfer.files[0];
        if (file) {
            var input = document.getElementById('file-input');
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            previewImage(input);
        }
    });
}
</script>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
