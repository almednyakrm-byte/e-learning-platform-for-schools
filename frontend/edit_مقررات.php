**edit_مقررات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مقررات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$name = $data['name'];
$description = $data['description'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مقررات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit مقررات</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" value="<?= $name ?>" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $description ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-700 focus:ring-indigo-500">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مقررات.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_مقررات.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**مقررات.php (backend)**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$query = "SELECT * FROM مقررات WHERE id = '$id'";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Close connection
$conn->close();

// Output JSON
echo json_encode($row);
?>