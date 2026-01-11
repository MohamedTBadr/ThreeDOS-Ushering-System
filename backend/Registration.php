<?php
header('Content-Type: application/json');
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

$name = $_POST["name"];
$email = $_POST["email"];
$college = $_POST["college"];
$level = $_POST["level"];
$preferences = $_POST["preferences"] ?? null;

$query = $connection->prepare(
    "INSERT INTO registration (name, email, college, level, preferences)
     VALUES (?, ?, ?, ?, ?)"
);

$query->bind_param("sssss", $name, $email, $college, $level, $preferences);

if ($query->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}

$query->close();       // close statement
$connection->close();  // close connection
