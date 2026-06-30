**edit_طلاب-students.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get student ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$studentData = json_decode(file_get_contents('../backend/طلاب-students.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Student</h2>
        <form id="edit-student-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $studentData['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $studentData['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $studentData['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Student</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-student-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/طلاب-students.php',
                    data: formData,
                    success: function(data) {
                        window.location.href = 'list_طلاب.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/طلاب-students.php**

<?php
// Check if student ID is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Get student data
    $id = $_GET['id'];
    $query = "SELECT * FROM students WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    // Fetch student data
    $studentData = mysqli_fetch_assoc($result);
    
    // Close connection
    mysqli_close($conn);
    
    // Output student data as JSON
    echo json_encode($studentData);
} else {
    // Output error message
    echo 'Error: Student ID not set.';
}
?>