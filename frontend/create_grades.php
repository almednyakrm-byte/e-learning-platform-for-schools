**create_grades.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';

// Include form script
require_once 'form_script.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Add New Grade</h2>
        <form id="create-grade-form">
            <div class="mb-4">
                <label for="student_id" class="text-slate-900 font-bold">Student ID:</label>
                <input type="text" id="student_id" name="student_id" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="subject_id" class="text-slate-900 font-bold">Subject ID:</label>
                <input type="text" id="subject_id" name="subject_id" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="grade" class="text-slate-900 font-bold">Grade:</label>
                <input type="number" id="grade" name="grade" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Grade</button>
        </form>
    </div>
</div>

<?php
// Include footer
require_once 'footer.php';
?>


**form_script.php**

<script>
    $(document).ready(function() {
        $('#create-grade-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/grades.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_grades.php';
                    } else {
                        alert('Error creating grade');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        });
    });
</script>


**grades.php (backend)**

<?php
// Include database connection
require_once 'db_connection.php';

// Check if form data is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $grade = $_POST['grade'];

    // Insert data into database
    $query = "INSERT INTO grades (student_id, subject_id, grade) VALUES ('$student_id', '$subject_id', '$grade')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating grade';
    }
}

// Close database connection
mysqli_close($conn);
?>