<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define allowed roles for each operation
$allowedRoles = array(
    'GET' => 'user',
    'POST' => 'user',
    'PUT' => 'admin',
    'DELETE' => 'admin'
);

// Check if user has permission to perform the requested operation
if ($allowedRoles[$_SERVER['REQUEST_METHOD']] !== $_SESSION['user_role']) {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Validate input data
if (empty($input)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Sanitize input data
$input = array_map('trim', $input);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare SQL query to select all students
        $stmt = $pdo->prepare('SELECT * FROM طلاب');
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return students in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($students);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Prepare SQL query to insert new student
        $stmt = $pdo->prepare('INSERT INTO طلاب (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->execute();
        
        // Return inserted student in JSON format
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Student created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        // Prepare SQL query to update student
        $stmt = $pdo->prepare('UPDATE طلاب SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->execute();
        
        // Return updated student in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Student updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        // Prepare SQL query to delete student
        $stmt = $pdo->prepare('DELETE FROM طلاب WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        
        // Return deleted student in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Student deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}
?>