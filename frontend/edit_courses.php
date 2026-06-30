<?php
// edit_courses.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_courses.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 font-bold mb-4">Edit Course</h2>
        <form id="edit-course-form">
            <div class="mb-4">
                <label for="course_name" class="block text-blue-500 text-sm font-bold mb-2">Course Name</label>
                <input type="text" id="course_name" name="course_name" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <div class="mb-4">
                <label for="course_description" class="block text-blue-500 text-sm font-bold mb-2">Course Description</label>
                <textarea id="course_description" name="course_description" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Update Course</button>
        </form>
    </div>

    <script>
        // Fetch existing record details
        fetch('../backend/courses.php?id=<?= $id ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('course_name').value = data.course_name;
                document.getElementById('course_description').value = data.course_description;
            });

        // Submit form using AJAX
        document.getElementById('edit-course-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('../backend/courses.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: <?= $id ?>,
                    course_name: formData.get('course_name'),
                    course_description: formData.get('course_description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_courses.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>