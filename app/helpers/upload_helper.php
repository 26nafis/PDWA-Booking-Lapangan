<?php
// ============================================================
//  app/helpers/upload_helper.php — File Upload Handler
// ============================================================

function uploadImage(array $file, string $folder): string|false {
    $allowedMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    $allowedExt  = ['jpg', 'jpeg', 'png', 'webp'];
    $maxSize     = 5 * 1024 * 1024; // 5MB

    // Cek error upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Cek ukuran
    if ($file['size'] > $maxSize) {
        flashMessage('error', 'Ukuran file terlalu besar. Maksimal 5MB.');
        return false;
    }

    // Validasi MIME type
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    if (!in_array($mimeType, $allowedMime)) {
        flashMessage('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau WebP.');
        return false;
    }

    // Validasi ekstensi
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        flashMessage('error', 'Ekstensi file tidak valid.');
        return false;
    }

    // Generate nama file unik
    $newName  = uniqid('img_', true) . '.' . $ext;
    $destDir  = UPLOAD_PATH . '/' . $folder;
    $destPath = $destDir . '/' . $newName;

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return false;
    }

    return $folder . '/' . $newName;
}

function deleteImage(string $path): void {
    $fullPath = UPLOAD_PATH . '/' . $path;
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}
