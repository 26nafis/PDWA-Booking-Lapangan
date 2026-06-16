<?php
// ============================================================
//  app/helpers/sanitize_helper.php — Input Sanitization
// ============================================================

function sanitize(string $value): string {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

function sanitizeInt(mixed $value): int {
    return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}

function sanitizeFloat(mixed $value): float {
    return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
