**create_معلمين.php**

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
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New معلمين</h2>
        <form id="create-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold">Name</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="email" class="text-slate-900 font-bold">Email</label>
                    <input type="email" id="email" name="email" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="text-slate-900 font-bold">Phone</label>
                    <input type="tel" id="phone" name="phone" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="address" class="text-slate-900 font-bold">Address</label>
                    <input type="text" id="address" name="address" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/معلمين.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_معلمين.php';
                    } else {
                        alert('Error creating record');
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


**backend/معلمين.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to database
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "database_name";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL query
    $sql = "INSERT INTO معلمين (name, email, phone, address) VALUES (?, ?, ?, ?)";

    // Bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);

    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error creating record';
    }

    // Close connection
    $conn->close();
}
?>