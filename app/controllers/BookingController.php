<?php
// ============================================================
//  app/controllers/BookingController.php
// ============================================================

class BookingController {

    public function form(?string $lapanganId = null): void {
        requireLogin();
        if (!$lapanganId) { redirect('/lapangan'); }

        $lapanganModel = new Lapangan();
        $lapangan = $lapanganModel->findById((int)$lapanganId);
        if (!$lapangan) {
            flashMessage('error', 'Lapangan tidak ditemukan.');
            redirect('/lapangan');
        }

        $date = sanitize($_GET['date'] ?? date('Y-m-d'));
        $bookedSlots = $lapanganModel->getBookedSlots((int)$lapanganId, $date);

        require_once view('booking/form');
    }

    public function store(): void {
        requireLogin();
        verifyCSRF();

        $lapanganId = sanitizeInt($_POST['lapangan_id'] ?? 0);
        $date       = sanitize($_POST['booking_date'] ?? '');
        $startTime  = sanitize($_POST['start_time'] ?? '');
        $endTime    = sanitize($_POST['end_time'] ?? '');
        $notes      = sanitize($_POST['notes'] ?? '');

        // Validasi
        if (!$lapanganId || !$date || !$startTime || !$endTime) {
            flashMessage('error', 'Semua field wajib diisi.');
            redirect("/booking/form/$lapanganId");
        }

        // Hitung durasi
        $duration = (strtotime($endTime) - strtotime($startTime)) / 3600;
        if ($duration <= 0) {
            flashMessage('error', 'Jam selesai harus lebih dari jam mulai.');
            redirect("/booking/form/$lapanganId");
        }

        // Cek apakah tanggal valid (tidak boleh kemarin)
        if ($date < date('Y-m-d')) {
            flashMessage('error', 'Tanggal booking tidak boleh di masa lalu.');
            redirect("/booking/form/$lapanganId");
        }

        $lapanganModel = new Lapangan();
        $lapangan = $lapanganModel->findById($lapanganId);

        $bookingModel = new Booking();

        // Cek slot bentrok
        if ($bookingModel->isSlotTaken($lapanganId, $date, $startTime, $endTime)) {
            flashMessage('error', 'Slot waktu tersebut sudah dibooking. Pilih waktu lain.');
            redirect("/booking/form/$lapanganId?date=$date");
        }

        $totalPrice = $duration * $lapangan['price_per_hour'];

        $bookingId = $bookingModel->create([
            'user_id'        => $_SESSION['user_id'],
            'lapangan_id'    => $lapanganId,
            'booking_date'   => $date,
            'start_time'     => $startTime,
            'end_time'       => $endTime,
            'duration_hours' => $duration,
            'total_price'    => $totalPrice,
            'notes'          => $notes,
        ]);

        if ($bookingId) {
            flashMessage('success', 'Booking berhasil dibuat! Silakan upload bukti pembayaran.');
            redirect("/payment/upload/$bookingId");
        } else {
            flashMessage('error', 'Gagal membuat booking. Coba lagi.');
            redirect("/booking/form/$lapanganId");
        }
    }

    public function history(): void {
        requireLogin();
        $bookingModel = new Booking();
        $bookings = $bookingModel->getByUser((int)$_SESSION['user_id']);
        require_once view('booking/history');
    }

    public function detail(?string $id = null): void {
        requireLogin();
        if (!$id) { redirect('/booking/history'); }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById((int)$id);

        if (!$booking || $booking['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
            flashMessage('error', 'Booking tidak ditemukan.');
            redirect('/booking/history');
        }

        $hasReviewed = $bookingModel->hasReviewed((int)$id);
        require_once view('booking/detail');
    }

    public function cancel(?string $id = null): void {
        requireLogin();
        verifyCSRF();

        $bookingModel = new Booking();
        $ok = $bookingModel->cancel((int)$id, (int)$_SESSION['user_id']);

        if ($ok) {
            flashMessage('success', 'Booking berhasil dibatalkan.');
        } else {
            flashMessage('error', 'Booking tidak dapat dibatalkan.');
        }
        redirect('/booking/history');
    }
}
