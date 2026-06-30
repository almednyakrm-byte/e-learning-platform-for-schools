<?php
// edit_students.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_students.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 mb-4">Edit Student</h2>
        <form id="edit-student-form">
            <div class="mb-4">
                <label for="name" class="block text-blue-500 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-blue-500 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-blue-500 text-sm font-bold mb-2">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded-lg">Update Student</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/students.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                }
            });

            $('#edit-student-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/students.php',
                    data: formData,
                    success: function(data) {
                        window.location.href = 'list_students.php';
                    }
                });
            });
        });
    </script>
</body>
</html>