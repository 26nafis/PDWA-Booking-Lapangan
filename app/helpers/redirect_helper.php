<?php
// ============================================================
//  app/helpers/redirect_helper.php — Redirect & Flash
// ============================================================

function redirect(string $path): void {
    header('Location: ' . BASE_URL . $path);
    exit;
}

function flashMessage(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
