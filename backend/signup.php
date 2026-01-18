<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = getJsonInput();

    // Validate required fields
    $requiredFields = ['username', 'email', 'password', 'role', 'council_id'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            sendResponse('error', "Field '$field' is required", null, 400);
        }
    }

    $username = $input['username'];
    $email = $input['email'];
    $password = password_hash($input['password'], PASSWORD_BCRYPT);
    $role = $input['role'];
    $council_id = (int) $input['council_id'];

    // Validate role
    $allowedRoles = ['VP', 'Head', 'Instructor'];
    if (!in_array($role, $allowedRoles)) {
        sendResponse('error', 'Invalid role', null, 400);
    }

    // Check if username or email already exists
    $checkStmt = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        sendResponse('error', 'Username or Email already registered', null, 409);
    }

    // Check if council exists
    $councilStmt = $connection->prepare("SELECT id FROM councils WHERE id = ?");
    $councilStmt->bind_param("i", $council_id);
    $councilStmt->execute();
    $councilResult = $councilStmt->get_result();

    if ($councilResult->num_rows === 0) {
        sendResponse('error', 'Invalid council ID', null, 400);
    }

    // Insert new user
    $stmt = $connection->prepare("INSERT INTO users (username, email, password, role, council_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $username, $email, $password, $role, $council_id);

    if ($stmt->execute()) {
        $newId = $stmt->insert_id;
        sendResponse('success', 'User registered successfully', ['id' => $newId], 201);
    } else {
        sendResponse('error', 'Failed to register user: ' . $stmt->error, null, 500);
    }
}

$connection->close();
