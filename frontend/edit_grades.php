**edit_grades.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/grades.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-slate-900">Edit Grades</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="grade" class="block text-sm font-medium text-slate-900">Grade:</label>
                <input type="number" id="grade" name="grade" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $existingRecord['grade'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/grades.php',
                    data: {
                        id: <?= $id ?>,
                        name: $('#name').val(),
                        grade: $('#grade').val()
                    },
                    success: function(response) {
                        window.location.href = 'list_{mod_slug}.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>

**grades.php (backend)**

<?php
// Check if id is set
if (isset($_GET['id'])) {
    // Fetch existing record details from database
    $id = $_GET['id'];
    // Replace with your database query
    $existingRecord = array(
        'id' => $id,
        'name' => 'John Doe',
        'grade' => 85
    );
    echo json_encode($existingRecord);
} else {
    http_response_code(400);
    echo 'Invalid request';
}

**Note:** Replace `list_{mod_slug}.php` with the actual URL of the page you want to redirect to after updating the grades. Also, replace the database query in `grades.php` with your actual database query to fetch the existing record details.