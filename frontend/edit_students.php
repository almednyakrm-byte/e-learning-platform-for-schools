**edit_students.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get student ID from URL
$id = $_GET['id'];

// Fetch existing record details
$students = file_get_contents('../backend/students.php?id=' . $id);
$student = json_decode($students, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+DwXbhQ9FTdAATqIo9HedYUzRQp37V" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Student</h2>
        <form id="edit-student-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= $student['name'] ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= $student['email'] ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?= $student['phone'] ?>">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address"><?= $student['address'] ?></textarea>
            </div>
            <button type="submit" class="btn">Update Student</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch existing record details via GET
            $.ajax({
                type: 'GET',
                url: '../backend/students.php?id=' + <?= $id ?>,
                success: function(data) {
                    var student = JSON.parse(data);
                    $('#name').val(student.name);
                    $('#email').val(student.email);
                    $('#phone').val(student.phone);
                    $('#address').val(student.address);
                }
            });

            // Submit form via AJAX PUT request
            $('#edit-student-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/students.php',
                    data: formData,
                    success: function() {
                        Swal.fire({
                            title: 'Student updated successfully!',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = 'list_students.php';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error updating student!',
                            icon: 'error',
                            text: error,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

**students.php (backend)**

<?php
// Check if student ID is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get student details
    $id = $_GET['id'];
    $sql = "SELECT * FROM students WHERE id = '$id'";
    $result = $conn->query($sql);

    // Check if student exists
    if ($result->num_rows > 0) {
        // Fetch student details
        $student = $result->fetch_assoc();
        echo json_encode($student);
    } else {
        echo json_encode(array('error' => 'Student not found'));
    }

    // Close database connection
    $conn->close();
} else {
    // Return error if student ID is not set
    echo json_encode(array('error' => 'Student ID not set'));
}
?>

**update_student.php (backend)**

<?php
// Check if student ID is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get student details
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update student details
    $sql = "UPDATE students SET name = '$name', email = '$email', phone = '$phone', address = '$address' WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('success' => 'Student updated successfully'));
    } else {
        echo json_encode(array('error' => 'Error updating student'));
    }

    // Close database connection
    $conn->close();
} else {
    // Return error if student ID is not set
    echo json_encode(array('error' => 'Student ID not set'));
}
?>