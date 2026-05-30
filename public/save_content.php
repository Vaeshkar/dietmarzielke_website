<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// Handle CORS preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

// Read raw POST input
$inputRaw = file_get_contents("php://input");
$input = json_decode($inputRaw, true);

if (!$input) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid JSON payload"]);
    exit;
}

$password = isset($input['password']) ? $input['password'] : '';
$contentData = isset($input['content']) ? $input['content'] : null;

// Default password: 'dietmar123'
// To change the password:
// 1. Run: php -r "echo password_hash('YOUR_NEW_PASSWORD', PASSWORD_DEFAULT);"
// 2. Replace the hash string below.
$admin_hash = '$2y$10$lXj/mrr4U0JJjrP9FraPL.2KLDcxk8tthb.D8kMX3vAL/w.EXmpAG';

if (!password_verify($password, $admin_hash)) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Falsches Passwort"]);
    exit;
}

if (!$contentData || !is_array($contentData)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Fehlende Inhaltsdaten"]);
    exit;
}

// Path to content.json relative to this script
$filePath = __DIR__ . '/content.json';

// Backup old content before writing new one in case of failure
if (file_exists($filePath)) {
    copy($filePath, $filePath . '.bak');
}

// Save the content prettified to keep it human-readable
$jsonString = json_encode($contentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (file_put_contents($filePath, $jsonString) !== false) {
    echo json_encode(["success" => true, "message" => "Inhalt erfolgreich gespeichert!"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Fehler beim Schreiben der Datei auf dem Server."]);
}
?>
