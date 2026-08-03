**create_courses.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include_once 'header.php';

// Include navigation
include_once 'navigation.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New Course</h2>
        <form id="create-course-form">
            <div class="mb-4">
                <label for="course_name" class="text-slate-900 font-bold text-sm mb-2">Course Name:</label>
                <input type="text" id="course_name" name="course_name" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Enter Course Name">
            </div>
            <div class="mb-4">
                <label for="course_code" class="text-slate-900 font-bold text-sm mb-2">Course Code:</label>
                <input type="text" id="course_code" name="course_code" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Enter Course Code">
            </div>
            <div class="mb-4">
                <label for="course_description" class="text-slate-900 font-bold text-sm mb-2">Course Description:</label>
                <textarea id="course_description" name="course_description" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 h-20" placeholder="Enter Course Description"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Course</button>
        </form>
    </div>
</div>

<?php
// Include footer
include_once 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-course-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/courses.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_courses.php';
                    } else {
                        alert('Error creating course');
                    }
                }
            });
        });
    });
</script>


**courses.php (backend)**

<?php
// Include database connection
include_once 'db_connection.php';

// Check if form data is submitted
if (isset($_POST['course_name']) && isset($_POST['course_code']) && isset($_POST['course_description'])) {
    // Prepare SQL query
    $query = "INSERT INTO courses (course_name, course_code, course_description) VALUES (:course_name, :course_code, :course_description)";
    
    // Prepare statement
    $stmt = $pdo->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':course_name', $_POST['course_name']);
    $stmt->bindParam(':course_code', $_POST['course_code']);
    $stmt->bindParam(':course_description', $_POST['course_description']);
    
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error creating course';
    }
}
?>


**Note:** This code assumes you have a `db_connection.php` file that establishes a connection to your database using PDO. You'll need to modify the code to match your database schema and connection settings. Additionally, this code does not include any validation or error handling beyond what's provided by the `ajax` function. You may want to add additional checks and balances to ensure the data being submitted is valid and secure.