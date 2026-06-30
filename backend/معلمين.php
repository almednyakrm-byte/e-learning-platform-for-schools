<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle CRUD operations
if (isset($input['action'])) {
    switch ($input['action']) {
        case 'get':
            // Get all معلمين
            $stmt = $pdo->prepare("SELECT * FROM معلمين");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($rows);
            break;
        case 'create':
            // Validate input data
            if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid request'));
                exit;
            }

            // Sanitize input data
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

            // Insert new معلم
            $stmt = $pdo->prepare("INSERT INTO معلمين (name, email, phone) VALUES (:name, :email, :phone)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            http_response_code(201);
            echo json_encode(array('message' => 'معلم created successfully'));
            break;
        case 'update':
            // Validate input data
            if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid request'));
                exit;
            }

            // Sanitize input data
            $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

            // Update existing معلم
            $stmt = $pdo->prepare("UPDATE معلمين SET name = :name, email = :email, phone = :phone WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('message' => 'معلم updated successfully'));
            break;
        case 'delete':
            // Validate input data
            if (!isset($input['id'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid request'));
                exit;
            }

            // Sanitize input data
            $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

            // Delete existing معلم
            $stmt = $pdo->prepare("DELETE FROM معلمين WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('message' => 'معلم deleted successfully'));
            break;
        default:
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            break;
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}