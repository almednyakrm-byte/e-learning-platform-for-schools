**create_مدرسين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO مدرسين (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_مدرسين.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Create New مدرسين</h2>
    <form action="" method="post" class="space-y-6">
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-200 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter name">
        </div>
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="block w-full px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-200 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter email">
        </div>
        <div class="space-y-2">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-200 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter phone">
        </div>
        <div class="space-y-2">
            <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
            <textarea id="address" name="address" class="block w-full px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-200 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter address"></textarea>
        </div>
        <button type="submit" name="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_مدرسين.js**
javascript
// Get form elements
const form = document.querySelector('form');
const submitButton = document.querySelector('button[type="submit"]');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../backend/مدرسين.php', true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            // Redirect back to list_{mod_slug}.php
            window.location.href = 'list_مدرسين.php';
        } else {
            console.error(xhr.responseText);
        }
    };
    xhr.send(formData);
});


**مدرسين.php (backend)**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Insert data into database
    $query = "INSERT INTO مدرسين (name, email, phone, address) VALUES ('".$_POST['name']."', '".$_POST['email']."', '".$_POST['phone']."', '".$_POST['address']."')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Return success message
        echo 'Data inserted successfully';
    } else {
        // Return error message
        echo 'Error inserting data';
    }
}
?>