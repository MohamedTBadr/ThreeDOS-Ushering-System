<?php
require_once "bootstrap.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://threedos.infinityfree.me/frontend/');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "connection.php";

function sendResponse($status, $message, $data = null, $code = 200)
{
    http_response_code($code);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

function getJsonInput()
{
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}

// POST - Login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = getJsonInput();

    if (!isset($input['email']) || !isset($input['password'])) {
        sendResponse('error', 'Email and password are required', null, 400);
    }

    $email = $input['email'];
    $password = $input['password'];

    // Get user 
    $stmt = $connection->prepare("
        SELECT *
        FROM users u 
        WHERE u.email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse('error', 'Invalid credentials', null, 401);
    }

    $user = $result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $user['password'])) {
        sendResponse('error', 'Invalid credentials', null, 401);
    }

    // Generate session token
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

    // Store session
    $sessionStmt = $connection->prepare("INSERT INTO sessions (user_id, token, expires_at) VALUES (?, ?, ?)");
    $sessionStmt->bind_param("iss", $user['id'], $token, $expiresAt);
    $sessionStmt->execute();

    // Return user data without password
    unset($user['password']);
    $user['token'] = $token;

    $user['expires_at'] = $expiresAt;

    sendResponse('success', 'Login successful', $user);
}

// GET - Verify token and get user info
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $headers = getallheaders();
    $token = null;

    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
    } elseif (isset($_GET['token'])) {
        $token = $_GET['token'];
    }

    if (!$token) {
        sendResponse('error', 'No token provided', null, 401);
    }

    // Verify token
    $stmt = $connection->prepare("
        SELECT u.*, s.expires_at
        FROM sessions s
        JOIN users u ON s.user_id = u.id
        WHERE s.token = ? AND s.expires_at > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse('error', 'Invalid or expired token', null, 401);
    }

    $user = $result->fetch_assoc();
    unset($user['password']);

    sendResponse('success', 'Token valid', $user);
}



// DELETE or POST can be used; we'll support either for flexibility
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {

    // InfinityFree strips 'Authorization', so we use a custom header 'X-Token'
    $token = $_SERVER['HTTP_X_TOKEN'] ?? null;

    if (!$token) {
        sendResponse('error', 'No token provided', null, 401);
        exit;
    }

    // Delete session from database
    $stmt = $connection->prepare("DELETE FROM sessions WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        sendResponse('success', 'Logout successful');
    } else {
        sendResponse('error', 'Invalid token or already logged out', null, 400);
    }

 
}

$connection->close();
