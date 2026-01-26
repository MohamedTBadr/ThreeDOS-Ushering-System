<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

function authenticate($conn)
{
    // InfinityFree strips 'Authorization', so we use a custom header 'X-Token'
    $token = $_SERVER['HTTP_X_TOKEN'] ?? null;

    if (!$token) {
        sendResponse('error', 'Unauthorized. No token provided.', null, 401);
    }

    // Verify token in database
    $stmt = $conn->prepare("
        SELECT u.*, s.expires_at, s.token
        FROM sessions s
        JOIN users u ON s.user_id = u.id
        WHERE s.token = ? AND s.expires_at > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse('error', 'Session expired or invalid token.', null, 401);
    }

    // Return authenticated user data
    return $result->fetch_assoc();
}

// 1. POST - Public Registration
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = getJsonInput();
    $required = ['name', 'email', 'phone', 'college', 'level', 'council'];
    foreach ($required as $f)
        if (empty($input[$f]))
            sendResponse('error', "Field $f is required", null, 400);

    // We might need to assign a council based on preferences or a default one
    // For now, let's assume it maps to ID 1 or is set manually later
    $stmt = $connection->prepare("INSERT INTO registration (name, email, phone, college, level, council)
     VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $input['name'], $input['email'], $input['phone'], 
    $input['college'], $input['level'], $input['council']);

    if ($stmt->execute())
        sendResponse('success', 'Registration submitted!', ['id' => $stmt->insert_id], 201);
    else
        sendResponse('error', 'Registration failed.', null, 500);
}

// --- ALL OTHER METHODS REQUIRE AUTHENTICATION ---
$user = authenticate($connection);


// ===============================
// GET Quick Statistics (No Pagination)
// ===============================
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['quickstats'])) {

    $queryStr = "FROM registration WHERE 1=1";
    $params = [];
    $types = "";
$council = $user['council'];
    $role = $user['role'];
        //filter by council
    if(!empty($council)){
            $queryStr .= " AND council = ?";
        $params[] = "$council";
        $types .= "s";
    }
    // Optional filters
    if (!empty($_GET["level"])) {
        $queryStr .= " AND level LIKE ?";
        $params[] = "%" . $_GET["level"] . "%";
        $types .= "s";
    }
    if (!empty($_GET["council"])) {
        $queryStr .= " AND council LIKE ?";
        $params[] = "%" . $_GET["council"] . "%";
        $types .= "s";
    }

    // Use a single query to get counts per rating
    $statsQuery = "SELECT 
        COUNT(*) as total,
        SUM(rating='Acceptance') as accepted,
        SUM(rating='B') as backup,
        SUM(rating='Rejection') as rejected,
        SUM(rating IS NULL OR rating='Pending') as pending
    " . $queryStr;

    $stmt = $connection->prepare($statsQuery);
    if ($stmt === false) {
        sendResponse('error', 'Prepare failed: ' . $connection->error, null, 500);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    sendResponse('success', 'Quick stats retrieved', [
        'total' => (int)$result['total'],
        'accepted' => (int)$result['accepted'],
        'backup' => (int)$result['backup'],
        'rejected' => (int)$result['rejected'],
        'pending' => (int)$result['pending']
    ]);
}

// 2-GET ALL Applicants (Cursor-Based Pagination)
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $council = $user['council'];
    $role = $user['role'];

    // Cursor-based pagination parameters
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;
    $cursor = isset($_GET['cursor']) ? (int) $_GET['cursor'] : null; // ID to start from
    $prev_cursor = isset($_GET['prev_cursor']) ? (int) $_GET['prev_cursor'] : null; // For backward pagination

    $queryStr = "FROM registration WHERE 1=1"; // base query without SELECT *
    $params = [];
    $types = "";
    
    //filter by council
    if(!empty($council)){
        $queryStr .= " AND council = ?";
        $params[] = "$council";
        $types .= "s";
    }
    
    //filter by id
    if (!empty($_GET["id"])) {
        $id = $_GET["id"];
        $queryStr .= " AND id = ?";
        $params[] = "$id";
        $types .= "i";
    }

    // Filter by level
    if (!empty($_GET["level"])) {
        $level = $_GET["level"];
        $queryStr .= " AND level LIKE ?";
        $params[] = "%$level%";
        $types .= "s";
    }

    // Filter by rating
    if (!empty($_GET["rating"])) {
        $rating = $_GET["rating"];
        $queryStr .= " AND rating LIKE ?";
        $params[] = "%$rating%";
        $types .= "s";
    }

    // Search by name or email
    if (!empty($_GET["search"])) {
        $search = "%" . $_GET["search"] . "%";
        $queryStr .= " AND (name LIKE ? OR email LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $types .= "ss";
    }

    // ===============================
    // 1️⃣ Get total count (for reference)
    // ===============================
    $countQuery = "SELECT COUNT(*) as total " . $queryStr;
    $countStmt = $connection->prepare($countQuery);
    if ($countStmt === false) {
        die("Count prepare failed: " . $connection->error);
    }

    if (!empty($params)) {
        $countStmt->bind_param($types, ...$params);
    }

    $countStmt->execute();
    $totalResult = $countStmt->get_result()->fetch_assoc();
    $totalItems = (int) $totalResult['total'];

    // ===============================
    // 2️⃣ Cursor-based data fetching
    // ===============================
    $dataParams = $params; // copy params from filters
    $dataTypes = $types;

    // Add cursor condition for forward pagination
    if ($cursor !== null) {
        $queryStr .= " AND id < ?"; // Get records with ID less than cursor (descending order)
        $dataParams[] = $cursor;
        $dataTypes .= "i";
    }

    // Add cursor condition for backward pagination
    if ($prev_cursor !== null) {
        $queryStr .= " AND id > ?"; // Get records with ID greater than prev_cursor
        $dataParams[] = $prev_cursor;
        $dataTypes .= "i";
    }

    // Build the data query with cursor pagination
    $dataQuery = "SELECT * " . $queryStr . " ORDER BY id DESC LIMIT ?";
    $dataParams[] = $limit + 1; // Fetch one extra to check if there are more results
    $dataTypes .= "i";

    $stmt = $connection->prepare($dataQuery);
    if ($stmt === false) {
        die("Data prepare failed: " . $connection->error);
    }

    if (!empty($dataParams)) {
        $stmt->bind_param($dataTypes, ...$dataParams);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // ===============================
    // 3️⃣ Determine if there are more results
    // ===============================
    $hasMore = count($data) > $limit;
    if ($hasMore) {
        array_pop($data); // Remove the extra record
    }

    // Get cursors for next/previous pages
    $nextCursor = null;
    $prevCursor = null;

    if (!empty($data)) {
        $nextCursor = end($data)['id']; // Last record's ID for next page
        $prevCursor = reset($data)['id']; // First record's ID for previous page
    }

    // ===============================
    // 4️⃣ Return JSON with cursor pagination info
    // ===============================
    sendResponse('success', 'Data retrieved', [
        'applicants' => $data,
        'limit' => $limit,
        'total_items' => $totalItems,
        'has_more' => $hasMore,
        'next_cursor' => $hasMore ? $nextCursor : null,
        'prev_cursor' => $cursor ? $prevCursor : null
    ]);
}

// 3. PATCH - Update Applicant (Role Based)
if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    $input = getJsonInput();
    if (empty($input['id']))
        sendResponse('error', 'ID required', null, 400);

    // Check if applicant exists and belongs to user's council
    $check = $connection->prepare("SELECT council, rating FROM registration WHERE id = ?");
    $check->bind_param("i", $input['id']);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();

    if (!$res) {
        sendResponse('error', 'Applicant not found', null, 404);
    }

    if ($user['role'] !== "VP" && ($res['council'] != $user['council'] && $res['council'] !== null)) {
        sendResponse('error', 'Unauthorized to edit this applicant', null, 403);
    }

    $fields = [];
    $params = [];
    $types = "";

    // Role-based field selection
    if ($user['role'] === 'Instructor') {
        $allowed = ['rating', 'notes', 'interview_time'];
    } elseif ($user['role'] === 'Head' || $user['role'] === "VP") {
        $allowed = ['name', 'email', 'phone', 'college', 'level', 'rating', 'notes', 'council', 'interview_time'];
    }

    $ratingChanged = false; // Flag to detect if rating changes

    foreach ($allowed as $f) {
        if (isset($input[$f])) {
            // Check if rating changed
            if ($f === 'rating' && $input[$f] !== $res['rating']) {
                $ratingChanged = true;
            }

            $fields[] = "$f = ?";
            if ($f === 'interview_time' && empty($input[$f])) {
                $params[] = null;
            } else {
                $params[] = $input[$f];
            }
            $types .= "s";
        }
    }

    // Only update 'interviewed_by' if rating changed
    if ($ratingChanged) {
        $fields[] = "interviewed_by = ?";
        $params[] = $user['username']; // logged-in user's name
        $types .= "s";
    }

    if (empty($fields))
        sendResponse('error', 'No valid fields provided', null, 400);

    // Append ID for WHERE clause
    $params[] = $input['id'];
    $types .= "i";

    $updateQuery = "UPDATE registration SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $connection->prepare($updateQuery);
    if (!$stmt) {
        sendResponse('error', 'Prepare failed: '.$connection->error, null, 500);
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute())
        sendResponse('success', 'Updated successfully');
    else
        sendResponse('error', 'Update failed: '.$stmt->error, null, 500);
}

// 4. DELETE - VP/Head only
//if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
  //  if ($user['role'] === 'Instructor')
    //    sendResponse('error', 'Permission denied', null, 403);

    //$input = getJsonInput();
    //$stmt = $connection->prepare("DELETE FROM registration WHERE id = ? AND (council = ? OR council IS NULL)");
    //$stmt->bind_param("ii", $input['id'], $user['council']);
   // $stmt->execute();

  //sendResponse('success', 'Deleted successfully');
//}
