<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Define module slug
$mod_slug = 'courses';

// Define page title
$page_title = 'Create Course';

// Include header
require_once 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl text-blue-500 font-bold mb-4"><?= $page_title ?></h1>
    <form id="create-course-form">
        <div class="mb-4">
            <label for="course_name" class="block text-sm text-blue-500 font-bold mb-2">Course Name</label>
            <input type="text" id="course_name" name="course_name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-300 focus:border-orange-300" required>
        </div>
        <div class="mb-4">
            <label for="course_code" class="block text-sm text-blue-500 font-bold mb-2">Course Code</label>
            <input type="text" id="course_code" name="course_code" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-300 focus:border-orange-300" required>
        </div>
        <div class="mb-4">
            <label for="course_description" class="block text-sm text-blue-500 font-bold mb-2">Course Description</label>
            <textarea id="course_description" name="course_description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-300 focus:border-orange-300" required></textarea>
        </div>
        <div class="mb-4">
            <label for="course_duration" class="block text-sm text-blue-500 font-bold mb-2">Course Duration</label>
            <input type="number" id="course_duration" name="course_duration" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-300 focus:border-orange-300" required>
        </div>
        <div class="mb-4">
            <label for="course_fee" class="block text-sm text-blue-500 font-bold mb-2">Course Fee</label>
            <input type="number" id="course_fee" name="course_fee" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-orange-300 focus:border-orange-300" required>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Course</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-course-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/courses.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>