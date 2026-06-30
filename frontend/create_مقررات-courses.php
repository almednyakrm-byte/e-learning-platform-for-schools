**create_مقررات-courses.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $credits = filter_var($_POST['credits'], FILTER_SANITIZE_NUMBER_INT);
    $semester = filter_var($_POST['semester'], FILTER_SANITIZE_NUMBER_INT);

    // Insert data into database
    $query = "INSERT INTO مقررات (name, description, credits, semester) VALUES ('$name', '$description', '$credits', '$semester')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect to list page
        header('Location: list_مقررات.php');
        exit;
    } else {
        echo 'Error inserting data';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 font-bold text-lg mb-4">Create New Course</h2>
    <form id="create-course-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm mb-2">Course Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm mb-2">Course Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="credits" class="block text-slate-900 text-sm mb-2">Credits:</label>
            <input type="number" id="credits" name="credits" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="semester" class="block text-slate-900 text-sm mb-2">Semester:</label>
            <input type="number" id="semester" name="semester" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" id="submit-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Course</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-course-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مقررات-courses.php',
                data: formData,
                success: function(response) {
                    if (response === 'true') {
                        window.location.href = 'list_مقررات.php';
                    } else {
                        alert('Error creating course');
                    }
                }
            });
        });
    });
</script>

**Note:** This code assumes you have a database connection established in `../config/db.php` and a `mysqli` connection object named `$conn`. You'll need to modify the database query to match your actual database schema. Additionally, this code uses the `filter_var` function to sanitize user input, but you may want to consider using a more robust validation library to ensure data security.