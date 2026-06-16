<?php
// ============================================================
//  app/controllers/ReviewController.php
// ============================================================

class ReviewController {

    public function form(?string $bookingId = null): void {
        requireLogin();
        if (!$bookingId) { redirect('/booking/history'); }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById((int)$bookingId);

        if (!$booking || $booking['user_id'] != $_SESSION['user_id'] || $booking['status'] !== 'completed') {
            flashMessage('error', 'Review hanya bisa diberikan pada booking yang sudah selesai.');
            redirect('/booking/history');
        }

        $reviewModel = new Review();
        if ($reviewModel->existsByBooking((int)$bookingId)) {
            flashMessage('info', 'Kamu sudah memberikan review untuk booking ini.');
            redirect('/booking/history');
        }

        require_once view('review/form');
    }

    public function store(): void {
        requireLogin();
        verifyCSRF();

        $bookingId  = sanitizeInt($_POST['booking_id'] ?? 0);
        $rating     = sanitizeInt($_POST['rating'] ?? 0);
        $comment    = sanitize($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            flashMessage('error', 'Rating tidak valid.');
            redirect("/review/form/$bookingId");
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['user_id'] != $_SESSION['user_id'] || $booking['status'] !== 'completed') {
            flashMessage('error', 'Akses ditolak.');
            redirect('/booking/history');
        }

        $reviewModel = new Review();
        if ($reviewModel->existsByBooking($bookingId)) {
            flashMessage('info', 'Kamu sudah memberikan review.');
            redirect('/booking/history');
        }

        $ok = $reviewModel->create([
            'booking_id'  => $bookingId,
            'user_id'     => $_SESSION['user_id'],
            'lapangan_id' => $booking['lapangan_id'],
            'rating'      => $rating,
            'comment'     => $comment,
        ]);

        if ($ok) {
            flashMessage('success', 'Review berhasil dikirim. Terima kasih!');
        } else {
            flashMessage('error', 'Gagal mengirim review.');
        }
        redirect('/booking/history');
    }
}
