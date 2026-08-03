**edit_teachers.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get teacher ID from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$teacher = json_decode(file_get_contents('../backend/teachers.php?id=' . $id), true);

// Set page title and mod slug
$page_title = 'Edit Teacher';
$mod_slug = 'teachers';

// Include header and navigation
include 'header.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-teacher-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $teacher['name'] ?>">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $teacher['email'] ?>">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
            <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $teacher['phone'] ?>">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Teacher</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/teachers.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-teacher-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(this);

        // Send AJAX PUT request
        fetch('../backend/teachers.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_' + '<?= $mod_slug ?>' + '.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/teachers.php**

<?php
// Check if teacher ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get teacher ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get teacher details
$sql = "SELECT * FROM teachers WHERE id = '$id'";
$result = $conn->query($sql);

// Check if teacher exists
if ($result->num_rows > 0) {
    // Get teacher details
    $teacher = $result->fetch_assoc();
    echo json_encode($teacher);
} else {
    http_response_code(404);
    exit;
}

// Close database connection
$conn->close();
?>


**backend/teachers_update.php**

<?php
// Check if teacher ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get teacher ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Update teacher details
$sql = "UPDATE teachers SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
$conn->query($sql);

// Close database connection
$conn->close();

// Redirect to list page
header('Location: list_' . 'teachers' . '.php');
exit;
?>