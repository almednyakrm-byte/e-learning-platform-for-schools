<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all grades
    $stmt = $pdo->prepare('SELECT * FROM grades');
    $stmt->execute();
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return grades
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($grades);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Validate input data
    if (!isset($input['student_id']) || !isset($input['course_id']) || !isset($input['grade'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $student_id = (int) $input['student_id'];
    $course_id = (int) $input['course_id'];
    $grade = (float) $input['grade'];

    // Insert new grade
    $stmt = $pdo->prepare('INSERT INTO grades (student_id, course_id, grade) VALUES (:student_id, :course_id, :grade)');
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':grade', $grade);
    $stmt->execute();

    // Return new grade
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Grade created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['student_id']) || !isset($input['course_id']) || !isset($input['grade'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = (int) $input['id'];
    $student_id = (int) $input['student_id'];
    $course_id = (int) $input['course_id'];
    $grade = (float) $input['grade'];

    // Update existing grade
    $stmt = $pdo->prepare('UPDATE grades SET student_id = :student_id, course_id = :course_id, grade = :grade WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':grade', $grade);
    $stmt->execute();

    // Return updated grade
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Grade updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = (int) $input['id'];

    // Delete existing grade
    $stmt = $pdo->prepare('DELETE FROM grades WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted grade
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Grade deleted successfully'));
    exit;
}

http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;