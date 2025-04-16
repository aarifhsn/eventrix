<?php

function generate_csrf_token()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function uploadImage(array $file, string $targetDir = 'uploads', array $allowedExts = ['jpg', 'jpeg', 'png', 'webp'], int $maxSize = 2097152, string $oldFile = ''): string
{
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Image upload failed.");
    }

    $fileTmp = $file['tmp_name'];
    $fileName = $file['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $mimeType = mime_content_type($fileTmp);
    $fileSize = $file['size'];

    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp'
    ];

    if (!in_array($fileExt, $allowedExts) || !in_array($mimeType, $allowedMimes)) {
        throw new Exception("Invalid image format.");
    }

    if ($fileSize > $maxSize) {
        throw new Exception("Image size must be under " . ($maxSize / 1024 / 1024) . "MB.");
    }

    // Ensure directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Delete old file if exists
    if ($oldFile && file_exists($targetDir . '/' . $oldFile)) {
        unlink($targetDir . '/' . $oldFile);
    }

    $newFileName = uniqid('img_', true) . '.' . $fileExt;
    $targetPath = rtrim($targetDir, '/') . '/' . $newFileName;

    if (!move_uploaded_file($fileTmp, $targetPath)) {
        throw new Exception("Failed to move uploaded file.");
    }

    return $newFileName;
}

