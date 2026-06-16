<?php
// ============================================================
//  app/helpers/auth_helper.php — Auth & Session
// ============================================================

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        flashMessage('error', 'Silakan login terlebih dahulu.');
        redirect('/auth/login');
    }
}

function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        flashMessage('error', 'Akses ditolak. Halaman ini hanya untuk admin.');
        redirect('/home');
    }
}

function setUserSession(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['email']   = $user['email'];
    $_SESSION['role']    = $user['role'];
}

function destroySession(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
