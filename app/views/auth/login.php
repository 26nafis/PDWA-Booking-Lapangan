<?php $pageTitle = 'Login'; ?>
<?php ob_start(); ?>

<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?= BASE_URL ?>/home" class="inline-flex items-center gap-2">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                </div>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Masuk ke <?= APP_NAME ?></h2>
            <p class="text-gray-500 mt-1 text-sm">Booking lapangan olahraga favoritmu</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <?php require_once view('components/alert'); ?>

            <form method="POST" action="<?= BASE_URL ?>/auth/loginPost">
                <?php csrfField(); ?>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                        <input type="email" name="email" required autocomplete="email"
                               value="<?= isset($_POST['email']) ? e($_POST['email']) : '' ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                               placeholder="nama@email.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <input type="password" name="password" required autocomplete="current-password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                               placeholder="••••••••">
                    </div>

                    <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-lg transition text-sm">
                        Masuk
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="<?= BASE_URL ?>/auth/register" class="text-primary font-medium hover:underline">Daftar sekarang</a>
            </p>
        </div>

        <!-- Demo accounts -->
        <div class="mt-4 bg-white/70 rounded-xl p-4 text-xs text-gray-500 text-center">
            <strong>Demo:</strong> admin@sportfield.com / password &nbsp;|&nbsp; budi@email.com / password
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once view('layouts/main');
?>
