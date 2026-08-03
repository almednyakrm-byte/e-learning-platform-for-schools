<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all students
    $stmt = $pdo->prepare('SELECT * FROM students');
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return students
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($students);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Get request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Validate request data
    if (!isset($requestData['name']) || !isset($requestData['email']) || !isset($requestData['grade'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Sanitize request data
    $name = filter_var($requestData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($requestData['email'], FILTER_SANITIZE_EMAIL);
    $grade = filter_var($requestData['grade'], FILTER_SANITIZE_NUMBER_INT);

    // Insert student
    $stmt = $pdo->prepare('INSERT INTO students (name, email, grade) VALUES (:name, :email, :grade)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':grade', $grade);
    $stmt->execute();

    // Return student ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Validate request data
    if (!isset($requestData['id']) || !isset($requestData['name']) || !isset($requestData['email']) || !isset($requestData['grade'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Sanitize request data
    $id = filter_var($requestData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($requestData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($requestData['email'], FILTER_SANITIZE_EMAIL);
    $grade = filter_var($requestData['grade'], FILTER_SANITIZE_NUMBER_INT);

    // Update student
    $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email, grade = :grade WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':grade', $grade);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student updated successfully'));
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Validate request data
    if (!isset($requestData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Sanitize request data
    $id = filter_var($requestData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete student
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student deleted successfully'));
    exit;
}