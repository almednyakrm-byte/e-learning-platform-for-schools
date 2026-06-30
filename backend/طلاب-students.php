<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input
if (!isset($input['id']) && !isset($input['name']) && !isset($input['email']) && !isset($input['role'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Sanitize input
$input['name'] = trim($input['name'] ?? '');
$input['email'] = trim($input['email'] ?? '');
$input['role'] = trim($input['role'] ?? '');

// Check if user is admin for edits/deletions
if (isset($input['id']) && $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Handle GET request
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $stmt->execute(['id' => $_GET['id']]);
    $student = $stmt->fetch();
    if ($student) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($student);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} elseif (isset($_GET['all'])) {
    $stmt = $pdo->query('SELECT * FROM students');
    $students = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($students);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

// Handle POST request
if (isset($input['name']) && isset($input['email']) && isset($input['role'])) {
    $stmt = $pdo->prepare('INSERT INTO students (name, email, role) VALUES (:name, :email, :role)');
    $stmt->execute(['name' => $input['name'], 'email' => $input['email'], 'role' => $input['role']]);
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Student created successfully']);
}

// Handle PUT request
if (isset($input['id']) && isset($input['name']) && isset($input['email']) && isset($input['role'])) {
    $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email, role = :role WHERE id = :id');
    $stmt->execute(['id' => $input['id'], 'name' => $input['name'], 'email' => $input['email'], 'role' => $input['role']]);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Student updated successfully']);
}

// Handle DELETE request
if (isset($input['id'])) {
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $stmt->execute(['id' => $input['id']]);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Student deleted successfully']);
}

?>