<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['id'])) {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Get teacher by ID
    $stmt = $pdo->prepare('SELECT * FROM مدرسين WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $teacher = $stmt->fetch();

    if ($teacher) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teacher);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Teacher not found'));
    }
} elseif (isset($_GET['all'])) {
    // Get all teachers
    $stmt = $pdo->prepare('SELECT * FROM مدرسين');
    $stmt->execute();
    $teachers = $stmt->fetchAll();

    if ($teachers) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'No teachers found'));
    }
} elseif (isset($_GET['search'])) {
    // Search teachers by name or email
    $stmt = $pdo->prepare('SELECT * FROM مدرسين WHERE name LIKE :name OR email LIKE :email');
    $stmt->bindParam(':name', '%' . $_GET['search'] . '%');
    $stmt->bindParam(':email', '%' . $_GET['search'] . '%');
    $stmt->execute();
    $teachers = $stmt->fetchAll();

    if ($teachers) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'No teachers found'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}

// Handle POST request
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    // Validate input data
    if (!preg_match('/^[a-zA-Z ]+$/', $_POST['name'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid name'));
        exit;
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid email'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Insert new teacher
    $stmt = $pdo->prepare('INSERT INTO مدرسين (name, email, password) VALUES (:name, :email, :password)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher created successfully'));
}

// Handle PUT request
if (isset($_PUT['id']) && isset($_PUT['name']) && isset($_PUT['email'])) {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Validate input data
    if (!preg_match('/^[a-zA-Z ]+$/', $_PUT['name'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid name'));
        exit;
    }

    if (!filter_var($_PUT['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid email'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($_PUT['name']);
    $email = htmlspecialchars($_PUT['email']);

    // Update teacher
    $stmt = $pdo->prepare('UPDATE مدرسين SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $_PUT['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher updated successfully'));
}

// Handle DELETE request
if (isset($_DELETE['id'])) {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Delete teacher
    $stmt = $pdo->prepare('DELETE FROM مدرسين WHERE id = :id');
    $stmt->bindParam(':id', $_DELETE['id']);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher deleted successfully'));
}