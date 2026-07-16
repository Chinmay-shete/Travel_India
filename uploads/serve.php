<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Guard: Ensure user is logged in
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    exit("Access Denied");
}

$file = $_GET['file'] ?? '';

// Prevent directory traversal
$file = basename($file);

if (empty($file)) {
    http_response_code(404);
    exit("File not specified");
}

$filePath = __DIR__ . '/' . $file;

if (!file_exists($filePath)) {
    http_response_code(404);
    exit("File not found");
}

// Detect MIME type safely
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filePath);
finfo_close($finfo);

// Whitelist allowed MIME types for display
$allowedMimeTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp'
];

if (!array_key_exists($mimeType, $allowedMimeTypes)) {
    http_response_code(400);
    exit("Unsupported file type");
}

// Output headers and file
header("Content-Type: " . $mimeType);
header("Content-Length: " . filesize($filePath));
readfile($filePath);
exit;
?>
