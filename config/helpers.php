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
 * Execute a SELECT query and fetch a single result 
 */
function fetchById($pdo, $table, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
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

function uploadImage($inputName = 'photo', $uploadDir = 'uploads', $maxSizeMB = 2, $allowedExts = ['jpg', 'jpeg', 'png', 'webp'])
{
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return null; // No file uploaded or error in upload
    }

    $fileTmp = $_FILES[$inputName]['tmp_name'];
    $fileName = $_FILES[$inputName]['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $mimeType = mime_content_type($fileTmp);
    $fileSize = $_FILES[$inputName]['size'];

    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
    ];

    // Validate file extension
    if (!in_array($fileExt, $allowedExts)) {
        throw new Exception("Unsupported file extension.");
    }

    // Validate MIME type
    if (!in_array($mimeType, $allowedMimes)) {
        throw new Exception("Invalid MIME type.");
    }

    // Validate file size
    $maxSize = $maxSizeMB * 1024 * 1024;
    if ($fileSize > $maxSize) {
        throw new Exception("Image must be under {$maxSizeMB}MB.");
    }

    // Create upload directory if not exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $newFileName = uniqid($inputName . '_', true) . '.' . $fileExt;
    $uploadPath = rtrim($uploadDir, '/') . '/' . $newFileName;

    // Move uploaded file
    if (!move_uploaded_file($fileTmp, $uploadPath)) {
        throw new Exception("Failed to move uploaded file.");
    }

    return $newFileName;
}


function old($key, $default = null)
{
    if (isset($_SESSION[$key])) {
        $_SESSION[$key];
    }
}

// function generateSlug($title)
// {
//     $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
//     return preg_replace('/-+/', '-', $slug);
// }

function generateSlug($string)
{
    // Convert to lowercase
    $slug = strtolower($string);

    // Replace accented characters (e.g., é -> e)
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);

    // Replace anything that’s not a letter or number with a hyphen
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);

    // Trim hyphens from both ends
    $slug = trim($slug, '-');

    // Remove duplicate hyphens
    $slug = preg_replace('/-+/', '-', $slug);

    return $slug;
}

// check if slug exists in the posts table
function slugExists($slug, $pdo)
{
    $statement = $pdo->prepare("SELECT * FROM posts WHERE slug = :slug");
    $statement->execute(['slug' => $slug]);
    return $statement->rowCount() > 0;
}

function generateUniqueSlug($title, $pdo)
{
    $slug = generateSlug($title);
    $baseSlug = $slug;
    $i = 1;

    while (slugExists($slug, $pdo)) {
        $slug = $baseSlug . '-' . $i;
        $i++;
    }

    return $slug;
}
