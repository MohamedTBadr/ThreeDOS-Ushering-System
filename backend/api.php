<?php
require_once "bootstrap.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://threedos.infinityfree.me/frontend/');
header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "connection.php";
include "council_questions.php";

// ================================
// CACHING HELPERS
// ================================
define('CACHE_DIR', __DIR__ . '/cache/');
define('CACHE_TTL_DEFAULT', 30); // default TTL for heavy queries

if (!file_exists(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);

function getCache($key, $ttl = CACHE_TTL_DEFAULT) {
    $file = CACHE_DIR .$key. '.json';
    if (file_exists($file) && (time() - filemtime($file)) < $ttl) {
        return json_decode(file_get_contents($file), true);
    }
    return null;
}

function setCache($key, $data) {
    $file = CACHE_DIR .$key. '.json';
    file_put_contents($file, json_encode($data));
}

function invalidateCache($pattern = '*') {
    foreach (glob(CACHE_DIR . '*.json') as $file) {
        if ($pattern === '*' || str_contains($file, $pattern)) {
            @unlink($file);
        }
    }
}

// ================================
// HELPERS
// ================================
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
    $token = $_SERVER['HTTP_X_TOKEN'] ?? null;
    if (!$token) sendResponse('error', 'Unauthorized. No token provided.', null, 401);

    $stmt = $conn->prepare("
        SELECT u.*, s.expires_at, s.token
        FROM sessions s
        JOIN users u ON s.user_id = u.id
        WHERE s.token = ? AND s.expires_at > NOW()
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) sendResponse('error', 'Session expired or invalid token.', null, 401);

    return $result->fetch_assoc();
}

// ================================
// 1. POST - Registration
// ================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = getJsonInput();
	
    try {
        $stmt = $connection->prepare("
            INSERT INTO registration (name, email, phone, college, level, council,ushered_by)
            VALUES (?, ?, ?, ?, ?, ?,?)
        ");

        $stmt->bind_param(
            "sssssss",
            $input['name'],
            $input['email'],
            $input['phone'],
            $input['college'],
            $input['level'],
            $input['council'],
            $input['ushered_by']
            
        );

        $stmt->execute();
        invalidateCache();

        sendResponse('success', 'Registration submitted!', 201);

    } catch (Exception $e) {
        writeLog('error', 'Registration failed', [
            'user_input' => $input,
            'db_error' => $e->getMessage()
        ]);
        throw $e; // handled by global handler
    }
}

// ================================
// Authentication for other endpoints
// ================================
$user = authenticate($connection);

// ================================
// 2. GET HANDLER (All GET Requests)
// ================================
handleGetRequests($connection, $user);

function handleGetRequests($connection, $user) {
    $council = $user['council'] ?? '';
    $role = $user['role'] ?? '';
	if($council !== ''){
        $cachePrefix2=$council;
    }elseif($council ==''){
        $cachePrefix2=$role;
    }
    // ------------------------
    // Cleanup expired cache on every GET
    // ------------------------
    foreach (glob(CACHE_DIR . '*.json') as $file) {
        if (!is_file($file)) continue;
        $lastModified = @filemtime($file);
        if ($lastModified === false) continue;
        if (time() - $lastModified > CACHE_TTL_DEFAULT) @unlink($file);
    }

    // ------------------------
    //2.1 Quick Stats Endpoint
    // ------------------------
    if (isset($_GET['quickstats'])) {
        $cacheKey = 'quickstats_' .$cachePrefix2. '_' . md5(json_encode($_GET));
        $cached = getCache($cacheKey, 15);
        if ($cached) sendResponse('success', 'Quick stats retrieved (cached)', $cached);

        $queryStr = "FROM registration WHERE 1=1";
        $params = [];
        $types = "";

        if (!empty($council)) { $queryStr .= " AND council=?"; $params[]=$council; $types.="s"; }
        if (!empty($_GET['level'])) { $queryStr .= " AND level LIKE ?"; $params[]="%".$_GET['level']."%"; $types.="s"; }
        if (!empty($_GET['council'])) { $queryStr .= " AND council LIKE ?"; $params[]="%".$_GET['council']."%"; $types.="s"; }

        $statsQuery = "SELECT COUNT(*) as total,
                       SUM(rating='Acceptance') as accepted,
                       SUM(rating='B') as backup,
                       SUM(rating='Rejection') as rejected,
                       SUM(rating IS NULL OR rating='Pending') as pending
                       ".$queryStr;

        $stmt = $connection->prepare($statsQuery);
        if(!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = fetchAssocSafe($stmt);
        $stmt->close();

        $data = [
            'total' => (int)$result['total'],
            'accepted' => (int)$result['accepted'],
            'backup' => (int)$result['backup'],
            'rejected' => (int)$result['rejected'],
            'pending' => (int)$result['pending']
        ];

        setCache($cacheKey, $data);
        sendResponse('success','Quick stats retrieved',$data);
    }

    // ------------------------
    // 2.2 Interviews Endpoint
    // ------------------------
    if (isset($_GET['interviews'])) {
        $cacheKey = 'interviews_' . $cachePrefix2 . '_' . md5(json_encode($_GET));
        $cached = getCache($cacheKey, 15);
        if ($cached) sendResponse('success', 'Interviews retrieved (cached)', $cached);

        $queryStr = "SELECT id,name,interview_time,council,rating FROM registration WHERE interview_time IS NOT NULL";
        $params = []; $types = "";

        if ($role !== "VP" && !empty($council)) { $queryStr .= " AND council=?"; $params[]=$council; $types.="s"; }
        $queryStr .= " ORDER BY interview_time ASC";

        $stmt = $connection->prepare($queryStr);
        if(!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $interviews = fetchAllAssocSafe($stmt);
        $stmt->close();

        setCache($cacheKey, ['interviews'=>$interviews]);
        sendResponse('success','Interviews retrieved',['interviews'=>$interviews]);
    }

    // ------------------------
    //2.3 Applicants Listing
    // ------------------------
    if(isset($_GET['quickstats']) || isset($_GET['interviews'])) return;

    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $cursor = isset($_GET['cursor']) ? (int)$_GET['cursor'] : null;
    $prev_cursor = isset($_GET['prev_cursor']) ? (int)$_GET['prev_cursor'] : null;

    $filterHash = md5(json_encode($_GET));
    $cacheKey = "applicants_{$cachePrefix2}_cursor{$cursor}_limit{$limit}_{$filterHash}";
    $cached = getCache($cacheKey, 30);
    if($cached) sendResponse('success','Applicants retrieved (cached)', $cached);

    $queryStr = "FROM registration WHERE 1=1";
    $params = []; $types = "";

    if(!empty($council)) { $queryStr .= " AND council=?"; $params[]=$council; $types.="s"; }
    if(!empty($_GET['id'])) { $queryStr .= " AND id=?"; $params[]=(int)$_GET['id']; $types.="i"; }
    if(!empty($_GET['level'])) { $queryStr .= " AND level LIKE ?"; $params[]="%".$_GET['level']."%"; $types.="s"; }
    if(!empty($_GET['rating'])) { $queryStr .= " AND rating LIKE ?"; $params[]="%".$_GET['rating']."%"; $types.="s"; }
    if(!empty($_GET['search'])) { 
        $search="%".$_GET['search']."%"; 
        $queryStr.=" AND (name LIKE ? OR email LIKE ?)"; 
        $params[]=$search; $params[]=$search; $types.="ss"; 
    }
    if($cursor!==null){ $queryStr.=" AND id<?"; $params[]=$cursor; $types.="i"; }
    if($prev_cursor!==null){ $queryStr.=" AND id>?"; $params[]=$prev_cursor; $types.="i"; }

    $countQuery="SELECT COUNT(*) as total ".$queryStr;
    $countStmt=$connection->prepare($countQuery);
    if(!empty($params)) $countStmt->bind_param($types,...$params);
    $countStmt->execute();
    $totalItems=(int)fetchAssocSafe($countStmt)['total'];
    $countStmt->close();

    $dataQuery="SELECT * ".$queryStr." ORDER BY id DESC LIMIT ?";
    $params[]=$limit+1; $types.="i";
    $stmt=$connection->prepare($dataQuery);
    $stmt->bind_param($types,...$params);
    $stmt->execute();
    $data=fetchAllAssocSafe($stmt);
    $stmt->close();

    $hasMore = count($data) > $limit; if($hasMore) array_pop($data);
    $nextCursor = $prevCursor = null;
    if(!empty($data)) { $nextCursor = end($data)['id']; $prevCursor = reset($data)['id']; }

    foreach($data as &$applicant){
        if(!empty($applicant['interview_questions'])) 
            $applicant['interview_questions'] = json_decode($applicant['interview_questions'], true);
        else 
            $applicant['interview_questions'] = getAllQuestionsForCouncil($applicant['council'] ?? 'Academic Council');
    } unset($applicant);

    $resp = [
        'applicants'=>$data,
        'limit'=>$limit,
        'total_items'=>$totalItems,
        'has_more'=>$hasMore,
        'next_cursor'=>$hasMore?$nextCursor:null,
        'prev_cursor'=>$cursor?$prevCursor:null
    ];

    setCache($cacheKey,$resp);
    sendResponse('success','Applicants retrieved',$resp);
}

// ================================
// Safe fetch helpers for InfinityFree
// ================================
function fetchAssocSafe($stmt){
    $meta = $stmt->result_metadata();
    $row = [];
    $bind = [];
    while($field = $meta->fetch_field()){
        $row[$field->name] = null;
        $bind[] = &$row[$field->name];
    }
    call_user_func_array([$stmt, 'bind_result'], $bind);
    $stmt->fetch();
    return $row;
}

function fetchAllAssocSafe($stmt){
    $meta = $stmt->result_metadata();
    $row = [];
    $bind = [];
    while($field = $meta->fetch_field()){
        $row[$field->name] = null;
        $bind[] = &$row[$field->name];
    }
    call_user_func_array([$stmt, 'bind_result'], $bind);
    $results = [];
    while($stmt->fetch()){
        $r = [];
        foreach($row as $k=>$v) $r[$k]=$v;
        $results[]=$r;
    }
    return $results;
}

// ================================
// 5. PATCH - Update Applicant
// ================================
if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    $input = getJsonInput();
    if (empty($input['id'])) sendResponse('error', 'ID required', null, 400);

    $check = $connection->prepare("SELECT council, rating FROM registration WHERE id = ?");
    $check->bind_param("i", $input['id']);
    $check->execute();
    $res = fetchAssocSafe($check);
    $check->close();

    if (!$res) sendResponse('error', 'Applicant not found', null, 404);
    if ($user['role']!=="VP" && ($res['council']!=$user['council'] && $res['council']!==null)) sendResponse('error', 'Unauthorized', null, 403);

    $fields=[]; $params=[]; $types="";
    $allowed=[];
    if($user['role']==='Instructor') $allowed=['rating','notes','interview_time','interview_questions'];
    elseif($user['role']==='Head'||$user['role']==="VP") $allowed=['name','email','phone','college','level','rating','notes','council','interview_time','interview_questions'];
    elseif($user['role']==="OR") $allowed=['name','phone','interview_time'];

    $ratingChanged=false;
    foreach($allowed as $f){
        if(isset($input[$f])){
            if($f==='rating' && $input[$f]!=$res['rating']) $ratingChanged=true;
            $fields[]="$f = ?";
            if($f==='interview_time' && empty($input[$f])){$params[]=null;$types.="s";}
            elseif($f==='interview_questions' && is_array($input[$f])){$params[]=json_encode($input[$f],JSON_UNESCAPED_UNICODE);$types.="s";}
            else{$params[]=$input[$f];$types.="s";}
        }
    }
    if($ratingChanged){$fields[]="interviewed_by = ?"; $params[]=$user['username']; $types.="s";}
    if(empty($fields)) sendResponse('error','No valid fields provided',null,400);
    $params[]=$input['id']; $types.="i";

    $updateQuery="UPDATE registration SET ".implode(", ",$fields)." WHERE id = ?";
    $stmt=$connection->prepare($updateQuery);
    $stmt->bind_param($types,...$params);
    if($stmt->execute()){
        invalidateCache(); // clear cache after update
        sendResponse('success','Updated successfully');
    } else sendResponse('error','Update failed: '.$stmt->error,null,500);
}
