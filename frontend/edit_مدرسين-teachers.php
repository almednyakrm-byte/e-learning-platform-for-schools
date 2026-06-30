**edit_مدرسين-teachers.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/مدرسين-teachers.php?id=' . $id), true);

// Check if record exists
if (empty($data)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مدرس</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل مدرس</h2>
        <form id="edit-teacher-form">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm font-bold mb-2">اسم المدرس</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-gray-900 rounded-md border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-slate-900 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 text-sm text-gray-900 rounded-md border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-slate-900 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 text-sm text-gray-900 rounded-md border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-teacher-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مدرسين-teachers.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $mod_slug ?>.php';
                        } else {
                            alert('Error updating teacher');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>

**backend/مدرسين-teachers.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Invalid id'));
    exit;
}

// Get id
$id = $_GET['id'];

// Fetch existing record details
$data = array();
// Your database query to fetch the record details
// ...

echo json_encode($data);

Note: Replace `<?= $mod_slug ?>` with the actual value of the `$mod_slug` variable. Also, make sure to update the `backend/مدرسين-teachers.php` file to fetch the record details from your database.