<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
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
    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    // Check if the user is an admin for specific teacher ID
    if ($id && $_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Select all teachers or a specific teacher by ID
    $sql = 'SELECT * FROM teachers';
    $params = [];

    if ($id) {
        $sql .= ' WHERE id = :id';
        $params[':id'] = $id;
    }

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Process the output
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($teachers);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $name = isset($data['name']) ? trim($data['name']) : null;
    $email = isset($data['email']) ? trim($data['email']) : null;

    if (!$name || !$email) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Insert a new teacher
    $sql = 'INSERT INTO teachers (name, email) VALUES (:name, :email)';
    $params = [
        ':name' => $name,
        ':email' => $email
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Process the output
    $teacherId = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $teacherId]);
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $id = isset($data['id']) ? (int) $data['id'] : null;
    $name = isset($data['name']) ? trim($data['name']) : null;
    $email = isset($data['email']) ? trim($data['email']) : null;

    if (!$id || !$name || !$email) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Update a teacher
    $sql = 'UPDATE teachers SET name = :name, email = :email WHERE id = :id';
    $params = [
        ':id' => $id,
        ':name' => $name,
        ':email' => $email
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Process the output
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Teacher updated successfully']);
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Teacher not found']);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if the user is an admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize the input
    $id = isset($data['id']) ? (int) $data['id'] : null;

    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Delete a teacher
    $sql = 'DELETE FROM teachers WHERE id = :id';
    $params = [
        ':id' => $id
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Process the output
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Teacher deleted successfully']);
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Teacher not found']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}