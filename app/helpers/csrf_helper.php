<?php
// ============================================================
//  app/helpers/csrf_helper.php — CSRF Token
// ============================================================

function generateCSRF(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): void {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCSRF()) . '">';
}

function verifyCSRF(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('<h1>403 - CSRF Token Invalid</h1>');
    }
}
