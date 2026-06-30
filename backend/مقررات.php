<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/mqrats' => array(
        'GET' => 'get_mqrats',
        'POST' => 'create_mqrat',
    ),
    '/mqrats/:id' => array(
        'GET' => 'get_mqrat',
        'PUT' => 'update_mqrat',
        'DELETE' => 'delete_mqrat',
    ),
);

// Route the request
$match = null;
foreach ($routes as $route => $methods) {
    if (strpos($route, '/') === 0) {
        $route = ltrim($route, '/');
    }
    if (strpos($_SERVER['REQUEST_URI'], $route) === 0) {
        $match = $route;
        break;
    }
}

if ($match) {
    $parts = explode('/', $match);
    $method = $_SERVER['REQUEST_METHOD'];
    $id = isset($parts[1]) ? $parts[1] : null;
    $action = $routes[$match][$method];
    $action($id);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
}

function get_mqrats() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM mqrats");
    $stmt->execute();
    $mqrats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($mqrats);
}

function get_mqrat($id) {
    global $conn;
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        return;
    }
    $stmt = $conn->prepare("SELECT * FROM mqrats WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $mqrat = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$mqrat) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
        return;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($mqrat);
}

function create_mqrat() {
    global $conn;
    $data = $_POST;
    // Validation
    if (!isset($data['name']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid data'));
        return;
    }
    // Sanitize input
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    // Insert data
    $stmt = $conn->prepare("INSERT INTO mqrats (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    $id = $conn->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
}

function update_mqrat($id) {
    global $conn;
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        return;
    }
    $data = $_POST;
    // Validation
    if (!isset($data['name']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid data'));
        return;
    }
    // Sanitize input
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
    // Update data
    $stmt = $conn->prepare("UPDATE mqrats SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete_mqrat($id) {
    global $conn;
    if (!is_numeric($id)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        return;
    }
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        return;
    }
    // Delete data
    $stmt = $conn->prepare("DELETE FROM mqrats WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}
?>