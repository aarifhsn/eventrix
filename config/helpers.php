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

/**
 * Get a PDO database connection
 * 
 * @return PDO Database connection
 */
function getDbConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        $config = require(__DIR__ . '/config.php');
        $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}

/**
 * Execute a SELECT query and fetch all results
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array Results
 */
function fetchAll(PDO $pdo, string $table, string $orderBy = 'id ASC')
{
    $stmt = $pdo->prepare("SELECT * FROM $table ORDER BY $orderBy");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Authentication & Session Helpers
 */

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['admin']) &&
        is_array($_SESSION['admin']) &&
        isset($_SESSION['admin']['id']);
}

function checkAdminAuth()
{
    if (!isset($_SESSION['admin']) || !is_array($_SESSION['admin']) || !isset($_SESSION['admin']['id'])) {
        header('Location: login.php');
        exit;
    }
}

function initMessages()
{
    // Initialize message variables
    global $success_message, $error_message;
    $success_message = '';
    $error_message = '';

    // Check for messages in session
    if (isset($_SESSION['success_message'])) {
        $success_message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        $error_message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    }
}
/**
 * Display error message
 * 
 * @param string $message Error message
 * @return string HTML for error alert
 */
function displayError($message)
{
    if (empty($message))
        return '';

    return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        ' . htmlspecialchars($message) . '
        <button type="button" class="close  btn-close btn-close-white" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
}

/**
 * Display success message
 * 
 * @param string $message Success message
 * @return string HTML for success alert
 */
function displaySuccess($message)
{
    if (empty($message))
        return '';

    return '<div class="alert alert-success alert-dismissible fade show" role="alert">
        ' . htmlspecialchars($message) . '
        <button type="button" class="close btn-close btn-close-white" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
}

function uploadImage(
    array $file,
    string $targetDir = 'uploads',
    array $allowedExts = ['jpg', 'jpeg', 'png', 'webp'],
    int $maxSize = 2 * 1024 * 1024, // 2MB default
    string $oldFile = ''
): string {
    // Check for upload errors
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Image upload failed with error code: " . $file['error']);
    }

    $fileTmp = $file['tmp_name'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file extension
    if (!in_array($fileExt, $allowedExts)) {
        throw new Exception("Invalid file extension. Allowed: " . implode(', ', $allowedExts));
    }

    // Validate file MIME type
    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp'
    ];

    $mimeType = mime_content_type($fileTmp);
    if (!isset($allowedMimes[$fileExt]) || $mimeType !== $allowedMimes[$fileExt]) {
        throw new Exception("Invalid image MIME type.");
    }

    // Validate file size
    if ($fileSize > $maxSize) {
        throw new Exception("Image size exceeds the maximum allowed size of " . round($maxSize / 1024 / 1024, 2) . "MB.");
    }

    // Use absolute path (optional: store outside public directory for more security)
    $uploadPath = rtrim(__DIR__ . '/../' . $targetDir, '/');

    // Ensure directory exists
    if (!is_dir($uploadPath)) {
        if (!mkdir($uploadPath, 0755, true)) {
            throw new Exception("Failed to create upload directory.");
        }
    }

    // Delete old file if it exists
    if (!empty($oldFile)) {
        $oldFilePath = $uploadPath . '/' . basename($oldFile);
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    // Generate unique filename
    $newFileName = uniqid('img_', true) . '.' . $fileExt;
    $targetFile = $uploadPath . '/' . $newFileName;

    // Move the uploaded file
    if (!move_uploaded_file($fileTmp, $targetFile)) {
        throw new Exception("Failed to move uploaded file.");
    }

    // Return the filename (relative path can be added if needed)
    return $newFileName;
}
