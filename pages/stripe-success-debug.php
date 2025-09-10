<?php
// Minimal debug file - save as stripe-success-debug.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "PHP is working<br>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Session ID from URL: " . ($_GET['session_id'] ?? 'NOT PROVIDED') . "<br>";

// Check if files exist
$possible_paths = [
    __DIR__ . '/../config/config.php',
    __DIR__ . '/config/config.php',
    __DIR__ . '/../config.php',
    __DIR__ . '/config.php'
];

echo "<br>Checking config file paths:<br>";
foreach ($possible_paths as $i => $path) {
    echo "Path " . ($i + 1) . ": " . $path . " - " . (file_exists($path) ? "EXISTS" : "NOT FOUND") . "<br>";
}

echo "<br>Directory structure:<br>";
echo "Current dir contents:<br>";
print_r(scandir(__DIR__));

if (is_dir(__DIR__ . '/config')) {
    echo "<br>Config directory contents:<br>";
    print_r(scandir(__DIR__ . '/config'));
}

if (is_dir(__DIR__ . '/../config')) {
    echo "<br>Parent config directory contents:<br>";
    print_r(scandir(__DIR__ . '/../config'));
}

echo "<br>Basic test completed successfully!";
?>