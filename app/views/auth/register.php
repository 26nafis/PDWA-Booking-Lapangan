<?php $pageTitle = 'Daftar'; ?>
<?php ob_start(); ?>

<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h2>
            <p class="text-gray-500 mt-1 text-sm">Gratis dan mudah, booking dalam hitungan detik</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <?php require_once view('components/alert'); ?>

            <form method="POST" action="<?= BASE_URL ?>/auth/registerPost">
                <?php csrfField(); ?>
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" required value="<?= isset($_POST['name']) ? e($_POST['name']) : '' ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                               placeholder="Nama lengkap kamu">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" required value="<?= isset($_POST['email']) ? e($_POST['email']) : '' ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                               placeholder="nama@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">No. HP</label>
                        <input type="tel" name="phone" value="<?= isset($_POST['phone']) ? e($_POST['phone']) : '' ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                               placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <input type="password" name="password" required minlength="6"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                               placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirm" required minlength="6"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                               placeholder="Ulangi password">
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-lg transition text-sm">
                        Buat Akun
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login" class="text-primary font-medium hover:underline">Masuk</a>
            </p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
