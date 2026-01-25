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
    $requiredFields = ['username', 'email', 'password', 'role'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            sendResponse('error', "Field '$field' is required", null, 400);
        }
    }

    $username = $input['username'];
    $email = $input['email'];
    $password = password_hash($input['password'], PASSWORD_BCRYPT);
    $role = $input['role'];
    $council = $input['council'] ?? null; // default null if not sent

    // Validate role
    $allowedRoles = ['VP', 'Head', 'Instructor', 'OR', 'President'];
    if (!in_array($role, $allowedRoles)) {
        sendResponse('error', 'Invalid role', null, 400);
    }

    // Validate council based on role
    $rolesWithoutCouncil = ['VP', 'President', 'OR'];
    $rolesWithCouncil = ['Head', 'Instructor'];

    if (in_array($role, $rolesWithoutCouncil)) {
        $council = null; // force null for roles that shouldn't have council
    } elseif (in_array($role, $rolesWithCouncil)) {
        if (empty($council)) {
            sendResponse('error', "Council is required for role $role", null, 400);
        }
    }

    // Check if username or email already exists
    $checkStmt = $connection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        sendResponse('error', 'Username or Email already registered', null, 409);
    }

    // Insert new user
    $stmt = $connection->prepare("INSERT INTO users (username, email, password, role, council) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $password, $role, $council);

    if ($stmt->execute()) {
        $newId = $stmt->insert_id;
        sendResponse('success', 'User registered successfully', ['id' => $newId], 201);
    } else {
        sendResponse('error', 'Failed to register user: ' . $stmt->error, null, 500);
    }
}

$connection->close();
