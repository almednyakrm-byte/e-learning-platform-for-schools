**create_teachers.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_teacher_form.php';

// Include footer
include 'footer.php';


**create_teacher_form.php**

<!-- Create Teacher Form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Create Teacher</h2>
    <form id="create-teacher-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="John Doe">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="john.doe@example.com">
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="+1 123 456 7890">
        </div>
        <div>
            <label for="subject" class="block text-sm font-medium text-slate-900">Subject</label>
            <input type="text" id="subject" name="subject" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Mathematics">
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-lg hover:bg-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">Create Teacher</button>
    </form>
</div>

<!-- AJAX Script -->
<script>
    $(document).ready(function() {
        $('#create-teacher-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/teachers.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_teachers.php';
                    } else {
                        alert('Error creating teacher');
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Teacher</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <?php include 'navigation.php'; ?>
    <div class="container mx-auto p-4">
        <?php include 'create_teachers.php'; ?>
    </div>
</body>
</html>


**navigation.php**

<nav class="bg-slate-900 py-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Home</a>
        <ul class="flex items-center space-x-4">
            <li><a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Teachers</a></li>
            <li><a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Students</a></li>
            <li><a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Logout</a></li>
        </ul>
    </div>
</nav>


**footer.php**

<footer class="bg-slate-900 py-4">
    <div class="container mx-auto text-center text-sm text-white">
        &copy; 2023 All rights reserved.
    </div>
</footer>


Note: This code assumes that you have jQuery and Tailwind CSS installed in your project. You may need to adjust the code to fit your specific project structure and requirements.