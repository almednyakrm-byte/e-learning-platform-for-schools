<?php
require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if the user is an admin
if ($method === 'PUT' || $method === 'DELETE') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Get the request body
$body = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($method === 'GET') {
    try {
        // Prepare the SQL query
        $stmt = $pdo->prepare('SELECT * FROM teachers');
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Output the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle POST request
if ($method === 'POST') {
    try {
        // Validate the input
        if (!isset($body['name']) || !isset($body['email']) || !isset($body['subject'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
        
        // Sanitize the input
        $name = filter_var($body['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($body['email'], FILTER_SANITIZE_EMAIL);
        $subject = filter_var($body['subject'], FILTER_SANITIZE_STRING);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('INSERT INTO teachers (name, email, subject) VALUES (:name, :email, :subject)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->execute();
        
        // Output the result
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Teacher created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT request
if ($method === 'PUT') {
    try {
        // Validate the input
        if (!isset($body['id']) || !isset($body['name']) || !isset($body['email']) || !isset($body['subject'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
        
        // Sanitize the input
        $id = filter_var($body['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($body['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($body['email'], FILTER_SANITIZE_EMAIL);
        $subject = filter_var($body['subject'], FILTER_SANITIZE_STRING);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('UPDATE teachers SET name = :name, email = :email, subject = :subject WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->execute();
        
        // Output the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Teacher updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE request
if ($method === 'DELETE') {
    try {
        // Validate the input
        if (!isset($body['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
        
        // Sanitize the input
        $id = filter_var($body['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('DELETE FROM teachers WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Output the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Teacher deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}