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
 * Upload image
 */

// function uploadImage(array $file, string $targetDir = 'uploads', array $allowedExts = ['jpg', 'jpeg', 'png', 'webp'], int $maxSize = 2097152, string $oldFile = ''): string
// {
//     if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
//         throw new Exception("Image upload failed.");
//     }

//     $fileTmp = $file['tmp_name'];
//     $fileName = $file['name'];
//     $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
//     $mimeType = mime_content_type($fileTmp);
//     $fileSize = $file['size'];

//     $allowedMimes = [
//         'jpg' => 'image/jpeg',
//         'jpeg' => 'image/jpeg',
//         'png' => 'image/png',
//         'webp' => 'image/webp'
//     ];

//     if (!in_array($fileExt, $allowedExts) || !in_array($mimeType, $allowedMimes)) {
//         throw new Exception("Invalid image format.");
//     }

//     if ($fileSize > $maxSize) {
//         throw new Exception("Image size must be under " . ($maxSize / 1024 / 1024) . "MB.");
//     }

//     // Ensure directory exists
//     if (!is_dir($targetDir)) {
//         mkdir($targetDir, 0755, true);
//     }

//     // Delete old file if exists
//     if ($oldFile && file_exists($targetDir . '/' . $oldFile)) {
//         unlink($targetDir . '/' . $oldFile);
//     }

//     $newFileName = uniqid('img_', true) . '.' . $fileExt;
//     $targetPath = rtrim($targetDir, '/') . '/' . $newFileName;

//     if (!move_uploaded_file($fileTmp, $targetPath)) {
//         throw new Exception("Failed to move uploaded file.");
//     }

//     return $newFileName;
// }


// function old($key, $model = null, $default = '')
// {
//     // First check POST data (most recent form submission)
//     if (isset($_POST[$key])) {
//         return htmlspecialchars(trim($_POST[$key]), ENT_QUOTES, 'UTF-8');
//     }

//     // Then check model data if provided (e.g., $edit_speaker)
//     if ($model !== null && isset($model[$key])) {
//         return htmlspecialchars($model[$key], ENT_QUOTES, 'UTF-8');
//     }

//     // Fall back to default
//     return $default;
// }

/**
 * Database Helpers
 */

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
 * Execute a SELECT query and fetch one row
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array|false Single result row or false if not found
 */
function fetchOne($sql, $params = [])
{
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Query failed: " . $e->getMessage());
    }
}

/**
 * Execute an INSERT query
 * 
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @return int Last insert ID
 */
function insert($table, $data)
{
    try {
        $pdo = getDbConnection();

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));

        return $pdo->lastInsertId();
    } catch (Exception $e) {
        throw new Exception("Insert failed: " . $e->getMessage());
    }
}

/**
 * Execute an UPDATE query
 * 
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @param string $where WHERE clause
 * @param array $whereParams Parameters for WHERE clause
 * @return int Number of affected rows
 */
function update($table, $data, $where, $whereParams = [])
{
    try {
        $pdo = getDbConnection();

        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = ?";
        }
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge(array_values($data), $whereParams));

        return $stmt->rowCount();
    } catch (Exception $e) {
        throw new Exception("Update failed: " . $e->getMessage());
    }
}

/**
 * Execute a DELETE query
 * 
 * @param string $table Table name
 * @param string $where WHERE clause
 * @param array $params Parameters for WHERE clause
 * @return int Number of affected rows
 */
function delete($table, $where, $params = [])
{
    try {
        $pdo = getDbConnection();

        $sql = "DELETE FROM {$table} WHERE {$where}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    } catch (Exception $e) {
        throw new Exception("Delete failed: " . $e->getMessage());
    }
}

/**
 * CRUD Module Helper Functions
 */

/**
 * Get item by ID from a table
 * 
 * @param string $table Table name
 * @param int $id Item ID
 * @return array|false Item data or false if not found
 */
function getItemById($table, $id)
{
    try {
        return fetchOne("SELECT * FROM {$table} WHERE id = :id", [':id' => $id]);
    } catch (Exception $e) {
        throw new Exception("Error loading {$table} data: " . $e->getMessage());
    }
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

/**
 * Require login or redirect
 * 
 * @param string $redirect Redirect URL if not logged in
 */
function requireLogin($redirect = 'login.php')
{
    if (!isLoggedIn()) {
        header("Location: {$redirect}");
        exit;
    }
}

/**
 * Generate CSRF token
 * 
 * @param string $key Session key to store token
 * @return string CSRF token
 */
function generateCsrfToken($key = 'csrf_token')
{
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = bin2hex(random_bytes(32));
    }
    return $_SESSION[$key];
}

/**
 * Validate CSRF token
 * 
 * @param string $token Token from form
 * @param string $key Session key where token is stored
 * @return bool True if valid
 */
function validateCsrfToken($token, $key = 'csrf_token')
{
    return isset($_SESSION[$key]) && $token === $_SESSION[$key];
}

/**
 * Refresh CSRF token
 * 
 * @param string $key Session key where token is stored
 * @return string New CSRF token
 */
function refreshCsrfToken($key = 'csrf_token')
{
    $_SESSION[$key] = bin2hex(random_bytes(32));
    return $_SESSION[$key];
}

/**
 * Form & Request Helpers
 */

/**
 * Get old input value
 * 
 * @param string $key Input field name
 * @param array|null $item Item data for editing
 * @return string Input value
 */
function old($key, $item = null)
{
    if (isset($_SESSION['form_submitted_successfully']) && $_SESSION['form_submitted_successfully'] === true) {
        // Clear the value after successful submission
        return '';
    }
    if (isset($_POST[$key])) {
        return htmlspecialchars($_POST[$key]);
    } elseif ($item && isset($item[$key])) {
        return htmlspecialchars($item[$key]);
    }
    return '';
}

/**
 * Get sanitized POST value
 * 
 * @param string $key POST field name
 * @param mixed $default Default value if not exists
 * @return mixed Sanitized value
 */
function post($key, $default = '')
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

/**
 * Get sanitized GET value
 * 
 * @param string $key GET parameter name
 * @param mixed $default Default value if not exists
 * @return mixed Sanitized value
 */
function get($key, $default = '')
{
    return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
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

/**
 * File Upload Helpers
 */

/**
 * Upload image file
 * 
 * @param array $file $_FILES array element
 * @param string $uploadDir Upload directory
 * @param array $allowedExtensions Allowed file extensions
 * @param int $maxSize Maximum file size in bytes
 * @param string $oldFile Old file to delete if exists
 * @return string New filename
 */
function uploadImage($file, $uploadDir, $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'], $maxSize = 2097152, $oldFile = '')
{
    // Check if file was uploaded
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Upload failed with error code: " . $file['error']);
    }

    // Check file size
    if ($file['size'] > $maxSize) {
        throw new Exception("File size exceeds the limit of " . ($maxSize / 1024 / 1024) . "MB");
    }

    // Check file extension
    $fileInfo = pathinfo($file['name']);
    $extension = strtolower($fileInfo['extension']);

    if (!in_array($extension, $allowedExtensions)) {
        throw new Exception("File type not allowed. Allowed types: " . implode(', ', $allowedExtensions));
    }

    // Create upload directory if it doesn't exist
    $uploadPath = __DIR__ . '/../' . $uploadDir;
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // Generate unique filename
    $newFilename = uniqid() . '.' . $extension;
    $destination = $uploadPath . '/' . $newFilename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Failed to move uploaded file");
    }

    // Delete old file if exists
    if (!empty($oldFile) && file_exists($uploadPath . '/' . $oldFile)) {
        unlink($uploadPath . '/' . $oldFile);
    }

    return $newFilename;
}

/**
 * CRUD Operations Helpers
 */

/**
 * Process form for creating or updating an item
 * 
 * @param string $table Table name
 * @param array $formData Form data
 * @param array $fileData File upload data (optional)
 * @param string $fileField DB field for file (optional)
 * @param array $requiredFields Required fields (optional)
 * @return array Result with success/error message
 */
function processCrudForm($table, $formData, $fileData = null, $fileField = 'image', $requiredFields = [])
{
    try {
        $pdo = getDbConnection();
        $pdo->beginTransaction();

        // Check required fields
        foreach ($requiredFields as $field) {
            if (empty($formData[$field])) {
                throw new Exception("Field '{$field}' is required");
            }
        }

        $id = isset($formData['id']) ? intval($formData['id']) : 0;
        unset($formData['id']); // Remove ID from data array

        // Handle file upload
        $newFileName = null;
        if ($fileData && isset($fileData['error']) && $fileData['error'] === UPLOAD_ERR_OK) {
            $oldFile = '';
            if ($id > 0) {
                $item = getItemById($table, $id);
                $oldFile = $item[$fileField] ?? '';
            }

            $newFileName = uploadImage(
                $fileData,
                'uploads',
                ['jpg', 'jpeg', 'png', 'webp'],
                2 * 1024 * 1024,
                $oldFile
            );
        }

        // Add file to data if uploaded
        if ($newFileName) {
            $formData[$fileField] = $newFileName;
        }

        // Insert or update
        if ($id > 0) {
            update($table, $formData, "id = ?", [$id]);
            $message = ucfirst(rtrim($table, 's')) . " updated successfully!";
        } else {
            $id = insert($table, $formData);
            $message = ucfirst(rtrim($table, 's')) . " added successfully!";
        }

        $pdo->commit();

        return [
            'success' => true,
            'message' => $message,
            'id' => $id
        ];
    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

/**
 * Generate pagination links HTML
 * 
 * @param array $pagination Pagination data
 * @param string $baseUrl Base URL for links
 * @return string HTML for pagination
 */
function paginationLinks($pagination, $baseUrl = '?')
{
    if ($pagination['totalPages'] <= 1) {
        return '';
    }

    $html = '<nav><ul class="pagination">';

    // Previous button
    $prevDisabled = ($pagination['currentPage'] <= 1) ? 'disabled' : '';
    $prevPage = $pagination['currentPage'] - 1;
    $html .= "<li class=\"page-item {$prevDisabled}\">
        <a class=\"page-link\" href=\"{$baseUrl}page={$prevPage}\" aria-label=\"Previous\">
            <span aria-hidden=\"true\">&laquo;</span>
        </a>
    </li>";

    // Page numbers
    for ($i = 1; $i <= $pagination['totalPages']; $i++) {
        $active = ($i == $pagination['currentPage']) ? 'active' : '';
        $html .= "<li class=\"page-item {$active}\">
            <a class=\"page-link\" href=\"{$baseUrl}page={$i}\">{$i}</a>
        </li>";
    }

    // Next button
    $nextDisabled = ($pagination['currentPage'] >= $pagination['totalPages']) ? 'disabled' : '';
    $nextPage = $pagination['currentPage'] + 1;
    $html .= "<li class=\"page-item {$nextDisabled}\">
        <a class=\"page-link\" href=\"{$baseUrl}page={$nextPage}\" aria-label=\"Next\">
            <span aria-hidden=\"true\">&raquo;</span>
        </a>
    </li>";

    $html .= '</ul></nav>';

    return $html;
}

