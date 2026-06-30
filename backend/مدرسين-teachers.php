<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON body
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request to retrieve all teachers
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM teachers");
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to create a new teacher
    try {
        // Validate and sanitize input data
        if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($inputData['phone'], FILTER_SANITIZE_NUMBER_INT);

        // Check if user is admin to create a new teacher
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // Insert new teacher into database
        $stmt = $pdo->prepare("INSERT INTO teachers (name, email, phone) VALUES (:name, :email, :phone)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update a teacher
    try {
        // Validate and sanitize input data
        if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($inputData['phone'], FILTER_SANITIZE_NUMBER_INT);

        // Check if user is admin to update a teacher
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // Update teacher in database
        $stmt = $pdo->prepare("UPDATE teachers SET name = :name, email = :email, phone = :phone WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to delete a teacher
    try {
        // Validate and sanitize input data
        if (!isset($inputData['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

        // Check if user is admin to delete a teacher
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // Delete teacher from database
        $stmt = $pdo->prepare("DELETE FROM teachers WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}