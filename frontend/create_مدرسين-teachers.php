<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Define module slug
$mod_slug = 'مدرسين-teachers';

// Define page title
$page_title = 'Add New Teacher';

// Include header
require_once 'header.php';
?>

<!-- Premium Tailwind UI form -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>
    <form id="teacher-form" class="space-y-6">
        <div class="flex flex-col">
            <label for="name" class="text-lg font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border border-slate-300 py-2 pl-3 pr-10 text-base text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <div class="flex flex-col">
            <label for="email" class="text-lg font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border border-slate-300 py-2 pl-3 pr-10 text-base text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <div class="flex flex-col">
            <label for="phone" class="text-lg font-medium text-slate-900">Phone</label>
            <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border border-slate-300 py-2 pl-3 pr-10 text-base text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
        </div>
        <div class="flex flex-col">
            <label for="subject" class="text-lg font-medium text-slate-900">Subject</label>
            <select id="subject" name="subject" class="mt-1 block w-full rounded-md border border-slate-300 py-2 pl-3 pr-10 text-base text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                <option value="">Select Subject</option>
                <option value="Math">Math</option>
                <option value="Science">Science</option>
                <option value="English">English</option>
            </select>
        </div>
        <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-500 py-2 px-4 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">Add Teacher</button>
    </form>
</div>

<!-- AJAX JavaScript to POST form data -->
<script>
    $(document).ready(function() {
        $('#teacher-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function(response) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>