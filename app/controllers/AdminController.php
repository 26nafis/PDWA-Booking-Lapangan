<?php
// ============================================================
//  app/controllers/AdminController.php
// ============================================================

class AdminController {

    // ---- DASHBOARD ------------------------------------------
    public function dashboardIndex(): void {
        requireAdmin();
        $bookingModel  = new Booking();
        $lapanganModel = new Lapangan();
        $userModel     = new User();
        $paymentModel  = new Payment();

        $stats = [
            'total_bookings'  => $bookingModel->countAll(),
            'today_bookings'  => $bookingModel->countToday(),
            'pending_bookings'=> $bookingModel->countPending(),
            'total_revenue'   => $bookingModel->totalRevenue(),
            'total_lapangan'  => $lapanganModel->countAll(),
            'total_customers' => $userModel->countAll(),
            'pending_payments'=> $paymentModel->countPending(),
        ];
        $recentBookings = $bookingModel->getAll();
        $recentBookings = array_slice($recentBookings, 0, 8);

        require_once view('admin/dashboard');
    }

    // ---- LAPANGAN CRUD --------------------------------------
    public function lapanganIndex(): void {
        requireAdmin();
        $lapanganModel = new Lapangan();
        $lapangan = $lapanganModel->getAllAdmin();
        require_once view('admin/lapangan/index');
    }

    public function lapanganCreate(): void {
        requireAdmin();
        $lapanganModel = new Lapangan();
        $categories = $lapanganModel->getCategories();
        require_once view('admin/lapangan/create');
    }

    public function lapanganStore(): void {
        requireAdmin();
        verifyCSRF();

        $data = [
            'category_id'    => sanitizeInt($_POST['category_id'] ?? 0),
            'name'           => sanitize($_POST['name'] ?? ''),
            'description'    => sanitize($_POST['description'] ?? ''),
            'price_per_hour' => sanitizeFloat($_POST['price_per_hour'] ?? 0),
            'location'       => sanitize($_POST['location'] ?? ''),
            'is_available'   => isset($_POST['is_available']) ? 1 : 0,
        ];

        if (!empty($_FILES['image']['name'])) {
            $data['image'] = uploadImage($_FILES['image'], 'lapangan');
        }

        $lapanganModel = new Lapangan();
        if ($lapanganModel->create($data)) {
            flashMessage('success', 'Lapangan berhasil ditambahkan.');
        } else {
            flashMessage('error', 'Gagal menambahkan lapangan.');
        }
        redirect('/admin/lapangan');
    }

    public function lapanganEdit(?string $id = null): void {
        requireAdmin();
        $lapanganModel = new Lapangan();
        $lapangan = $lapanganModel->findById((int)$id);
        if (!$lapangan) { flashMessage('error', 'Lapangan tidak ditemukan.'); redirect('/admin/lapangan'); }
        $categories = $lapanganModel->getCategories();
        require_once view('admin/lapangan/edit');
    }

    public function lapanganUpdate(?string $id = null): void {
        requireAdmin();
        verifyCSRF();

        $lapanganModel = new Lapangan();
        $old = $lapanganModel->findById((int)$id);
        if (!$old) { redirect('/admin/lapangan'); }

        $data = [
            'category_id'    => sanitizeInt($_POST['category_id'] ?? 0),
            'name'           => sanitize($_POST['name'] ?? ''),
            'description'    => sanitize($_POST['description'] ?? ''),
            'price_per_hour' => sanitizeFloat($_POST['price_per_hour'] ?? 0),
            'location'       => sanitize($_POST['location'] ?? ''),
            'is_available'   => isset($_POST['is_available']) ? 1 : 0,
        ];

        if (!empty($_FILES['image']['name'])) {
            $newImage = uploadImage($_FILES['image'], 'lapangan');
            if ($newImage) {
                if ($old['image']) deleteImage($old['image']);
                $data['image'] = $newImage;
            }
        }

        if ($lapanganModel->update((int)$id, $data)) {
            flashMessage('success', 'Lapangan berhasil diperbarui.');
        } else {
            flashMessage('error', 'Gagal memperbarui lapangan.');
        }
        redirect('/admin/lapangan');
    }

    public function lapanganDelete(?string $id = null): void {
        requireAdmin();
        verifyCSRF();
        $lapanganModel = new Lapangan();
        $lap = $lapanganModel->findById((int)$id);
        if ($lap && $lap['image']) deleteImage($lap['image']);
        $lapanganModel->delete((int)$id);
        flashMessage('success', 'Lapangan berhasil dihapus.');
        redirect('/admin/lapangan');
    }

    // ---- BOOKING MANAGEMENT ---------------------------------
    public function bookingIndex(): void {
        requireAdmin();
        $status = sanitize($_GET['status'] ?? '');
        $bookingModel = new Booking();
        $bookings = $bookingModel->getAll($status);
        require_once view('admin/booking/index');
    }

    public function bookingDetail(?string $id = null): void {
        requireAdmin();
        $bookingModel = new Booking();
        $booking = $bookingModel->findById((int)$id);
        if (!$booking) { flashMessage('error', 'Booking tidak ditemukan.'); redirect('/admin/booking'); }
        require_once view('admin/booking/detail');
    }

    public function bookingUpdate(?string $id = null): void {
        requireAdmin();
        verifyCSRF();
        $status = sanitize($_POST['status'] ?? '');
        $allowed = ['pending','confirmed','in_progress','completed','cancelled'];
        if (!in_array($status, $allowed)) {
            flashMessage('error', 'Status tidak valid.'); redirect('/admin/booking'); return;
        }
        $bookingModel = new Booking();
        $bookingModel->updateStatus((int)$id, $status);
        flashMessage('success', 'Status booking diperbarui.');
        redirect("/admin/booking/detail/$id");
    }

    // ---- PAYMENT VERIFICATION --------------------------------
    public function paymentIndex(): void {
        requireAdmin();
        $paymentModel = new Payment();
        $payments = $paymentModel->getAll();
        require_once view('admin/payment/index');
    }

    public function paymentVerify(?string $id = null): void {
        requireAdmin();
        verifyCSRF();

        $status  = sanitize($_POST['status'] ?? '');
        $notes   = sanitize($_POST['notes'] ?? '');

        if (!in_array($status, ['verified', 'rejected'])) {
            flashMessage('error', 'Status tidak valid.');
            redirect('/admin/payment');
        }

        $paymentModel = new Payment();
        $payment = $paymentModel->findById((int)$id);

        $ok = $paymentModel->verify((int)$id, (int)$_SESSION['user_id'], $status, $notes);

        if ($ok) {
            // Update status booking
            if ($status === 'verified') {
                $bookingModel = new Booking();
                $bookingModel->updateStatus($payment['booking_id'], 'confirmed');
            }
            flashMessage('success', 'Pembayaran berhasil ' . ($status === 'verified' ? 'diverifikasi' : 'ditolak') . '.');
        } else {
            flashMessage('error', 'Gagal memverifikasi pembayaran.');
        }

        redirect('/admin/payment');
    }
}
