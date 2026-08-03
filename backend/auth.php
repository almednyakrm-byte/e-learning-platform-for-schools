<?php

// Start the session to handle user authentication
session_start();

// Import the database connection script
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with the user's details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array('status' => 'logged_in', 'user_id' => $user_id, 'username' => $username);
    echo json_encode($response);
    exit;
}

// Check if the user is attempting to register
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the form has been submitted with the correct fields
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Check if the username and email are valid
        if (empty($username) || empty($email) || empty($password)) {
            $response = array('status' => 'error', 'message' => 'Please fill in all fields');
            echo json_encode($response);
            exit;
        }

        // Check if the username and email are unique
        $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $response = array('status' => 'error', 'message' => 'Username or email already taken');
            echo json_encode($response);
            exit;
        }

        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database using a prepared statement
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashed_password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Return a JSON response with the user's details
        $user_id = mysqli_insert_id($conn);
        $username = $_POST['username'];
        $response = array('status' => 'registered', 'user_id' => $user_id, 'username' => $username);
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Invalid form submission');
        echo json_encode($response);
        exit;
    }
}

// Check if the user is attempting to login
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the form has been submitted with the correct fields
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Check if the username and password are valid
        if (empty($username) || empty($password)) {
            $response = array('status' => 'error', 'message' => 'Please fill in all fields');
            echo json_encode($response);
            exit;
        }

        // Query the database to check if the username and password are correct
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);

        // Check if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Log the user in by setting their session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            // Return a JSON response with the user's details
            $response = array('status' => 'logged_in', 'user_id' => $user['id'], 'username' => $username);
            echo json_encode($response);
            exit;
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid username or password');
            echo json_encode($response);
            exit;
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Invalid form submission');
        echo json_encode($response);
        exit;
    }
}

// Check if the user is attempting to logout
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Log the user out by destroying their session
    session_destroy();
    $response = array('status' => 'logged_out');
    echo json_encode($response);
    exit;
}

// If none of the above conditions are met, return a JSON response indicating that the user is not logged in
$response = array('status' => 'not_logged_in');
echo json_encode($response);
exit;

?>