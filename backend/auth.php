<?php

// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Define a function to handle user registration
function registerUser($username, $email, $password) {
    // Prepare a statement to insert a new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    // Hash the password using password_hash() for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Bind the input parameters to the prepared statement
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    // Execute the prepared statement
    $stmt->execute();
    
    // Close the prepared statement
    $stmt->close();
}

// Define a function to handle user login
function loginUser($username, $password) {
    // Prepare a statement to select a user from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    
    // Bind the input parameter to the prepared statement
    $stmt->bind_param("s", $username);
    
    // Execute the prepared statement
    $stmt->execute();
    
    // Store the result in a variable
    $result = $stmt->get_result();
    
    // Fetch the user data from the result
    $user = $result->fetch_assoc();
    
    // Close the prepared statement
    $stmt->close();
    
    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Start a new session or update the existing one
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        // Return true to indicate a successful login
        return true;
    } else {
        // Return false to indicate an unsuccessful login
        return false;
    }
}

// Define a function to handle user logout
function logoutUser() {
    // Destroy the session to log out the user
    session_destroy();
}

// Define a function to check the current session status
function checkSession() {
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        // Return true to indicate a logged-in user
        return true;
    } else {
        // Return false to indicate an anonymous user
        return false;
    }
}

// Handle AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    // Check the request method
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Check the session status
        $sessionStatus = checkSession();
        
        // Return the session status as a JSON response
        echo json_encode(['session_status' => $sessionStatus]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the request data
        $requestData = json_decode(file_get_contents('php://input'), true);
        
        // Check the action type
        if ($requestData['action'] === 'login') {
            // Get the username and password from the request data
            $username = $requestData['username'];
            $password = $requestData['password'];
            
            // Check if the username and password are set
            if (isset($username) && isset($password)) {
                // Check the input fields securely
                if (strlen($username) >= 3 && strlen($username) <= 32 && strlen($password) >= 8 && strlen($password) <= 128) {
                    // Login the user
                    $loginResult = loginUser($username, $password);
                    
                    // Return the login result as a JSON response
                    echo json_encode(['login_result' => $loginResult]);
                } else {
                    // Return an error message as a JSON response
                    echo json_encode(['error' => 'Invalid username or password']);
                }
            } else {
                // Return an error message as a JSON response
                echo json_encode(['error' => 'Missing username or password']);
            }
        } elseif ($requestData['action'] === 'register') {
            // Get the username, email, and password from the request data
            $username = $requestData['username'];
            $email = $requestData['email'];
            $password = $requestData['password'];
            
            // Check if the username, email, and password are set
            if (isset($username) && isset($email) && isset($password)) {
                // Check the input fields securely
                if (strlen($username) >= 3 && strlen($username) <= 32 && strlen($email) >= 3 && strlen($email) <= 64 && strlen($password) >= 8 && strlen($password) <= 128) {
                    // Register the user
                    registerUser($username, $email, $password);
                    
                    // Return a success message as a JSON response
                    echo json_encode(['success' => 'User registered successfully']);
                } else {
                    // Return an error message as a JSON response
                    echo json_encode(['error' => 'Invalid username, email, or password']);
                }
            } else {
                // Return an error message as a JSON response
                echo json_encode(['error' => 'Missing username, email, or password']);
            }
        }
    }
}

// Handle logout requests
if (isset($_GET['logout'])) {
    // Logout the user
    logoutUser();
    
    // Redirect the user to the login page
    header('Location: login.php');
    exit;
}