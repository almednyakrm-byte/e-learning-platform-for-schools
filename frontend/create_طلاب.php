**create_طلاب.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة طالب جديد</h2>
        <form id="create-student-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 font-bold">اسم الطالب</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div>
                <label for="email" class="text-slate-900 font-bold">بريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div>
                <label for="phone" class="text-slate-900 font-bold">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div>
                <label for="address" class="text-slate-900 font-bold">العنوان</label>
                <textarea id="address" name="address" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-student-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/طلاب.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_طلاب.php';
                    } else {
                        alert('Error adding student');
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


**طلاب.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Insert data into database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $query = "INSERT INTO طلاب (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error adding student';
    }
}
?>


Note: This code assumes you have a database connection established in `db.php` and a table named `طلاب` with columns `name`, `email`, `phone`, and `address`. You should modify the code to fit your specific database schema and requirements.