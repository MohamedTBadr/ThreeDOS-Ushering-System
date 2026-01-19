<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "connection.php";

// Helper functions
function sendResponse($status, $message, $data = null, $code = 200)
{
    http_response_code($code);
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit();
}

function getJsonInput()
{
    return json_decode(file_get_contents('php://input'), true);
}

// Authentication Check Helper
function authenticate($conn)
{
    $headers = getallheaders();
    $token = null;
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
    }

    if (!$token)
        sendResponse('error', 'Unauthorized. No token provided.', null, 401);

    $stmt = $conn->prepare("
        SELECT u.*, c.name as council_name 
        FROM sessions s 
        JOIN users u ON s.user_id = u.id 
        JOIN councils c ON u.council_id = c.id
        WHERE s.token = ? AND s.expires_at > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0)
        sendResponse('error', 'Session expired or invalid token.', null, 401);

    return $result->fetch_assoc();
}

// 1. POST - Public Registration
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = getJsonInput();
    $required = ['name', 'email', 'phone', 'college', 'level', 'preferences','council_id'];
    foreach ($required as $f)
        if (empty($input[$f]))
            sendResponse('error', "Field $f is required", null, 400);

    // We might need to assign a council based on preferences or a default one
    // For now, let's assume it maps to ID 1 or is set manually later
    $stmt = $connection->prepare("INSERT INTO registration (name, email, phone, college, level, council)
     VALUES (?, ?, ?, ?, ?, ?,?)");
    $stmt->bind_param("ssssss", $input['name'], $input['email'], $input['phone'], 
    $input['college'], $input['level'], $input['council']);

    if ($stmt->execute())
        sendResponse('success', 'Registration submitted!', ['id' => $stmt->insert_id], 201);
    else
        sendResponse('error', 'Registration failed.', null, 500);
}

// --- ALL OTHER METHODS REQUIRE AUTHENTICATION ---
$user = authenticate($connection);

// 2. GET - Fetch Applicants (Filtered by Council)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $council_id = $user['council_id'];
    $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
    $limit = 50;
    $offset = ($page - 1) * $limit;

    $queryStr = "SELECT * FROM registration WHERE (council_id = ? OR council_id IS NULL)";
    $params = [$council_id];
    $types = "i";

    if (isset($_GET["search"]) && !empty($_GET["search"])) {
        $search = "%" . $_GET["search"] . "%";
        $queryStr .= " AND (name LIKE ? OR email LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $types .= "ss";
    }

    $queryStr .= " ORDER BY id DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $connection->prepare($queryStr);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    sendResponse('success', 'Data retrieved', ['applicants' => $data, 'user' => $user]);
}

// 3. PATCH - Update Applicant (Role Based)
if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    $input = getJsonInput();
    if (empty($input['id']))
        sendResponse('error', 'ID required', null, 400);

    // Check if applicant belongs to user's council
    $check = $connection->prepare("SELECT council_id FROM registration WHERE id = ?");
    $check->bind_param("i", $input['id']);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();
    if ($res['council_id'] != $user['council_id'] && $res['council_id'] !== null) {
        sendResponse('error', 'Unauthorized to edit this applicant', null, 403);
    }

    $fields = [];
    $params = [];
    $types = "";

    if ($user['role'] === 'Instructor') {
        // Least Privilege: Only allow rating and notes
        $allowed = ['rating', 'notes'];
        foreach ($allowed as $f) {
            if (isset($input[$f])) {
                $fields[] = "$f = ?";
                $params[] = $input[$f];
                $types .= "s";
            }
        }
    } else {
        // VP/Head: Full access
        $allowed = ['name', 'email', 'phone', 'college', 'level', 'preferences', 'rating', 'notes', 'council_id'];
        foreach ($allowed as $f) {
            if (isset($input[$f])) {
                $fields[] = "$f = ?";
                $params[] = $input[$f];
                $types .= "s";
            }
        }
    }

    if (empty($fields))
        sendResponse('error', 'No valid fields provided', null, 400);

    $params[] = $input['id'];
    $types .= "i";
    $updateQuery = "UPDATE registration SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $connection->prepare($updateQuery);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute())
        sendResponse('success', 'Updated successfully');
    else
        sendResponse('error', 'Update failed', null, 500);
}

// 4. DELETE - VP/Head only
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    if ($user['role'] === 'Instructor')
        sendResponse('error', 'Permission denied', null, 403);

    $input = getJsonInput();
    $stmt = $connection->prepare("DELETE FROM registration WHERE id = ? AND (council_id = ? OR council_id IS NULL)");
    $stmt->bind_param("ii", $input['id'], $user['council_id']);
    $stmt->execute();

    sendResponse('success', 'Deleted successfully');
}
