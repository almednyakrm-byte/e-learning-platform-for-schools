<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Function to validate user role
function validateUserRole($role) {
    // For demonstration purposes, assume a logged-in user with admin role
    // In a real application, you would retrieve the user's role from a session or database
    $loggedInUserRole = 'admin'; // Replace with actual user role
    if ($role === 'admin' && $loggedInUserRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: Admin access required']);
        exit;
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    validateUserRole('user');

    // Retrieve all courses
    $stmt = $pdo->prepare('SELECT * FROM courses');
    $stmt->execute();
    $courses = $stmt->fetchAll();

    // Output courses in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($courses);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['course_name']) || !isset($inputData['course_description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request: Missing course name or description']);
        exit;
    }

    // Sanitize input data
    $courseName = filter_var($inputData['course_name'], FILTER_SANITIZE_STRING);
    $courseDescription = filter_var($inputData['course_description'], FILTER_SANITIZE_STRING);

    // Insert new course
    $stmt = $pdo->prepare('INSERT INTO courses (course_name, course_description) VALUES (:course_name, :course_description)');
    $stmt->bindParam(':course_name', $courseName);
    $stmt->bindParam(':course_description', $courseDescription);
    $stmt->execute();

    // Output created course in JSON format
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Course created successfully']);
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['course_id']) || !isset($inputData['course_name']) || !isset($inputData['course_description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request: Missing course ID, name, or description']);
        exit;
    }

    // Sanitize input data
    $courseId = filter_var($inputData['course_id'], FILTER_SANITIZE_NUMBER_INT);
    $courseName = filter_var($inputData['course_name'], FILTER_SANITIZE_STRING);
    $courseDescription = filter_var($inputData['course_description'], FILTER_SANITIZE_STRING);

    // Update existing course
    $stmt = $pdo->prepare('UPDATE courses SET course_name = :course_name, course_description = :course_description WHERE course_id = :course_id');
    $stmt->bindParam(':course_id', $courseId);
    $stmt->bindParam(':course_name', $courseName);
    $stmt->bindParam(':course_description', $courseDescription);
    $stmt->execute();

    // Output updated course in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Course updated successfully']);
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['course_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request: Missing course ID']);
        exit;
    }

    // Sanitize input data
    $courseId = filter_var($inputData['course_id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete existing course
    $stmt = $pdo->prepare('DELETE FROM courses WHERE course_id = :course_id');
    $stmt->bindParam(':course_id', $courseId);
    $stmt->execute();

    // Output deleted course in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Course deleted successfully']);
}

// Handle invalid requests
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}