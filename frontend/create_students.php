**create_students.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require_once '../backend/config.php';
require_once '../backend/functions.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $errors = validateStudentData($_POST);
    if (empty($errors)) {
        // Insert new student record
        $studentData = $_POST;
        $studentData['created_at'] = date('Y-m-d H:i:s');
        $studentData['updated_at'] = date('Y-m-d H:i:s');
        $studentId = insertStudent($studentData);
        if ($studentId) {
            // Redirect back to list page
            header('Location: list_students.php');
            exit;
        } else {
            $errors[] = 'Failed to create student record';
        }
    }
}

// Define color palette
$colorPalette = [
    'slate-900' => '#1A1D23',
    'indigo-500' => '#4B5EDD',
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: <?= $colorPalette['slate-900'] ?>;
            color: #fff;
        }
        .bg-indigo-500 {
            background-color: <?= $colorPalette['indigo-500'] ?>;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Create Student</h1>
        <form id="create-student-form" class="bg-indigo-500 p-4 rounded-md shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-lg font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-600 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-lg font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-600 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-lg font-bold mb-2">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 border border-gray-600 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-lg font-bold mb-2">Address:</label>
                <textarea id="address" name="address" class="block w-full p-2 border border-gray-600 rounded-md" required></textarea>
            </div>
            <button type="submit" id="submit-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Create Student</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-student-form').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/students.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_students.php';
                        } else {
                            alert('Error creating student record');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error creating student record');
                    }
                });
            });
        });
    </script>
</body>
</html>


**Note:** This code assumes that you have a `students.php` file in the `../backend` directory that handles the form submission and inserts the new student record into the database. You will need to modify the code to match your specific database schema and backend logic.