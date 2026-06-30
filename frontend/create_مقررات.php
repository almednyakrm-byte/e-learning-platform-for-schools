**create_مقررات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة مقررات جديدة</h2>
        <form id="create-maqarat-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold">اسم المقرر</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="text-slate-900 font-bold">وصف المقرر</label>
                    <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-maqarat-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مقررات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مقررات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/مقررات.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    
    // Prepare and execute query
    $stmt = $db->prepare('INSERT INTO maqarat (name, description) VALUES (:name, :description)');
    $stmt->execute([
        'name' => $_POST['name'],
        'description' => $_POST['description']
    ]);
    
    // Check if query was successful
    if ($stmt->rowCount() > 0) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->errorInfo()[2];
    }
    
    // Close database connection
    $db = null;
} else {
    echo 'Error: Invalid request method';
}
?>

Note: Replace `database_name`, `username`, and `password` with your actual database credentials. Also, make sure to adjust the table and column names in the SQL query to match your actual database schema.