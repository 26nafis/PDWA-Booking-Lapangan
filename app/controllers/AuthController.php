<?php
// ============================================================
//  app/controllers/AuthController.php
// ============================================================

class AuthController {

    public function login(): void {
        if (isLoggedIn()) { redirect('/home'); }
        require_once view('auth/login');
    }

    public function loginPost(): void {
        verifyCSRF();
        $email    = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flashMessage('error', 'Email dan password wajib diisi.');
            redirect('/auth/login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            flashMessage('error', 'Email atau password salah.');
            redirect('/auth/login');
        }

        setUserSession($user);
        flashMessage('success', 'Selamat datang, ' . $user['name'] . '!');

        redirect($user['role'] === 'admin' ? '/admin/dashboard' : '/home');
    }

    public function register(): void {
        if (isLoggedIn()) { redirect('/home'); }
        require_once view('auth/register');
    }

    public function registerPost(): void {
        verifyCSRF();
        $name     = sanitize($_POST['name'] ?? '');
        $email    = sanitize($_POST['email'] ?? '');
        $phone    = sanitize($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        // Validasi
        if (empty($name) || empty($email) || empty($password)) {
            flashMessage('error', 'Semua field wajib diisi.');
            redirect('/auth/register');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flashMessage('error', 'Format email tidak valid.');
            redirect('/auth/register');
        }
        if (strlen($password) < 6) {
            flashMessage('error', 'Password minimal 6 karakter.');
            redirect('/auth/register');
        }
        if ($password !== $confirm) {
            flashMessage('error', 'Konfirmasi password tidak cocok.');
            redirect('/auth/register');
        }

        $userModel = new User();
        if ($userModel->emailExists($email)) {
            flashMessage('error', 'Email sudah terdaftar.');
            redirect('/auth/register');
        }

        $id = $userModel->create(['name' => $name, 'email' => $email, 'password' => $password, 'phone' => $phone]);
        if ($id) {
            flashMessage('success', 'Registrasi berhasil! Silakan login.');
            redirect('/auth/login');
        } else {
            flashMessage('error', 'Registrasi gagal. Coba lagi.');
            redirect('/auth/register');
        }
    }

    public function logout(): void {
        destroySession();
        flashMessage('success', 'Kamu telah logout.');
        redirect('/auth/login');
    }
}
