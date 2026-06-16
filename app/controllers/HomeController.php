<?php
// ============================================================
//  app/controllers/HomeController.php
// ============================================================

class HomeController {

    public function index(): void {
        $lapanganModel = new Lapangan();
        $lapangan      = $lapanganModel->getAll();
        $categories    = $lapanganModel->getCategories();
        require_once view('home/index');
    }
}
