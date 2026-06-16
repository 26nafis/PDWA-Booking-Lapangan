<?php
// ============================================================
//  app/controllers/PaymentController.php
// ============================================================

class PaymentController {

    public function upload(?string $bookingId = null): void {
        requireLogin();
        if (!$bookingId) { redirect('/booking/history'); }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById((int)$bookingId);

        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            flashMessage('error', 'Booking tidak ditemukan.');
            redirect('/booking/history');
        }

        $paymentModel = new Payment();
        $existing = $paymentModel->findByBooking((int)$bookingId);

        require_once view('payment/upload');
    }

    public function uploadPost(): void {
        requireLogin();
        verifyCSRF();

        $bookingId     = sanitizeInt($_POST['booking_id'] ?? 0);
        $paymentMethod = sanitize($_POST['payment_method'] ?? '');

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            flashMessage('error', 'Akses ditolak.');
            redirect('/booking/history');
        }

        // Upload gambar
        if (empty($_FILES['proof_image']['name'])) {
            flashMessage('error', 'Bukti pembayaran wajib diupload.');
            redirect("/payment/upload/$bookingId");
        }

        $imagePath = uploadImage($_FILES['proof_image'], 'payments');
        if (!$imagePath) {
            redirect("/payment/upload/$bookingId");
        }

        $paymentModel = new Payment();

        // Cek jika sudah ada payment, update saja
        $existing = $paymentModel->findByBooking($bookingId);
        if ($existing) {
            flashMessage('info', 'Bukti pembayaran sudah pernah diupload. Menunggu verifikasi admin.');
            redirect('/booking/history');
        }

        $ok = $paymentModel->create([
            'booking_id'     => $bookingId,
            'proof_image'    => $imagePath,
            'payment_method' => $paymentMethod,
        ]);

        if ($ok) {
            flashMessage('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
        } else {
            flashMessage('error', 'Gagal mengupload bukti pembayaran.');
        }
        redirect('/booking/history');
    }
}
