<?php
require_once "bootstrap.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://threedos.infinityfree.me/frontend/');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Token');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "connection.php";

// ================================
// HELPERS
// ================================
function sendResponse($status, $message, $data = null, $code = 200)
{
    http_response_code($code);
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit();
}

function authenticate($conn)
{
    $token = $_SERVER['HTTP_X_TOKEN'] ?? null;
    if (!$token) {
        // Fallback to Authorization header if X-Token not set
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
        }
    }

    if (!$token)
        sendResponse('error', 'Unauthorized. No token provided.', null, 401);

    $stmt = $conn->prepare("
        SELECT u.*, s.expires_at, s.token
        FROM sessions s
        JOIN users u ON s.user_id = u.id
        WHERE s.token = ? AND s.expires_at > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0)
        sendResponse('error', 'Session expired or invalid token.', null, 401);

    return $result->fetch_assoc();
}

// Authenticate User
$user = authenticate($connection);

// ================================
// LOGS LOGIC
// ================================

// Authorization Check
$userCouncil = $user['council'] ?? '';
if ($userCouncil !== 'Backend Development') {
    sendResponse('error', 'Unauthorized access to logs. Backend Development council only.', null, 403);
}

$logDir = __DIR__ . '/logs/';

// Action: list
if (isset($_GET['list'])) {
    $files = glob($logDir . '*.log');
    $logFiles = [];
    if ($files) {
        foreach ($files as $file) {
            $logFiles[] = basename($file);
        }
        rsort($logFiles); // Newest first
    }
    sendResponse('success', 'Log files retrieved', array_values($logFiles));
}

// Action: view
if (isset($_GET['view']) && !empty($_GET['file'])) {
    $filename = basename($_GET['file']);

    // Security check to prevent directory traversal
    if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
        sendResponse('error', 'Invalid filename', null, 400);
    }

    $filePath = $logDir . $filename;

    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $lines = explode(PHP_EOL, $content);
        $parsedLogs = [];
        foreach ($lines as $line) {
            if (trim($line) === '')
                continue;
            $decoded = json_decode($line, true);
            if ($decoded) {
                $parsedLogs[] = $decoded;
            } else {
                $parsedLogs[] = ['message' => $line, 'level' => 'UNKNOWN', 'time' => ''];
            }
        }
        // Reverse to show newest entries first
        $parsedLogs = array_reverse($parsedLogs);

        sendResponse('success', 'Log content retrieved', $parsedLogs);
    } else {
        sendResponse('error', 'Log file not found', null, 404);
    }
}

sendResponse('error', 'Invalid parameters. Use ?list=1 or ?view=1&file=...', null, 400);
