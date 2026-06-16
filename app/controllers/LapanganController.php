<?php
// ============================================================
//  app/controllers/LapanganController.php
// ============================================================

class LapanganController {

    public function index(): void {
        $search     = sanitize($_GET['search'] ?? '');
        $categoryId = sanitizeInt($_GET['category'] ?? 0);

        $lapanganModel = new Lapangan();
        $lapangan      = $lapanganModel->getAll($search, $categoryId);
        $categories    = $lapanganModel->getCategories();

        require_once view('lapangan/index');
    }

    public function detail(?string $id = null): void {
        if (!$id) { redirect('/lapangan'); }

        $lapanganModel = new Lapangan();
        $lapangan      = $lapanganModel->findById((int)$id);

        if (!$lapangan) {
            flashMessage('error', 'Lapangan tidak ditemukan.');
            redirect('/lapangan');
        }

        $date       = sanitize($_GET['date'] ?? date('Y-m-d'));
        $bookedSlots = $lapanganModel->getBookedSlots((int)$id, $date);
        $reviews     = $lapanganModel->getReviews((int)$id);

        require_once view('lapangan/detail');
    }
}
