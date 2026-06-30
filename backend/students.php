<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'You must be logged in to perform this action']);
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize the database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize the input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if the user is an admin
    if (isset($id) && $_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to perform this action']);
        exit;
    }
    
    // SQL query structure: Select all students or a specific student by ID
    if (isset($id)) {
        $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM students');
    }
    
    // Execute the query
    $stmt->execute();
    
    // Process the output
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($students);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to perform this action']);
        exit;
    }
    
    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize the input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);
    
    // Check if the input is valid
    if (!$name || !$email) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    
    // SQL query structure: Insert a new student
    $stmt = $pdo->prepare('INSERT INTO students (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    
    // Execute the query
    $stmt->execute();
    
    // Process the output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Student created successfully']);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to perform this action']);
        exit;
    }
    
    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize the input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);
    
    // Check if the input is valid
    if (!$id || !$name || !$email) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    
    // SQL query structure: Update a student
    $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    
    // Execute the query
    $stmt->execute();
    
    // Process the output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Student updated successfully']);
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'You do not have permission to perform this action']);
        exit;
    }
    
    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize the input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if the input is valid
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    
    // SQL query structure: Delete a student
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $stmt->bindParam(':id', $id);
    
    // Execute the query
    $stmt->execute();
    
    // Process the output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Student deleted successfully']);
}

// Handle other requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}