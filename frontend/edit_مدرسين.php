**edit_مدرسين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مدرسين.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is retrieved successfully
if ($data) {
    // Set data in session
    $_SESSION['edit_data'] = $data;
} else {
    // Redirect to error page
    header('Location: error.php');
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
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 block text-sm font-bold mb-2">اسم المدرس</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-gray-700 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $_SESSION['edit_data']['name']; ?>">
            </div>
            <div>
                <label for="email" class="text-slate-900 block text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 text-sm text-gray-700 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $_SESSION['edit_data']['email']; ?>">
            </div>
            <div>
                <label for="phone" class="text-slate-900 block text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 text-sm text-gray-700 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $_SESSION['edit_data']['phone']; ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">تعديل</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const data = new FormData(form);
            fetch('../backend/مدرسين.php', {
                method: 'PUT',
                body: data,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = 'list_مدرسين.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch((error) => console.error(error));
        });
    </script>
</body>
</html>


**مدرسين.php (backend)**

<?php
// Check if id is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Get record details
    $id = $_GET['id'];
    $query = "SELECT * FROM مدرسين WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'Record not found'));
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // Redirect to error page
    header('Location: error.php');
    exit;
}
?>


**مدرسين.php (backend) - Update record**

<?php
// Check if id and data are set
if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Get id and data
    $id = $_GET['id'];
    $data = $_POST;

    // Update record
    $query = "UPDATE مدرسين SET name = '$data[name]', email = '$data[email]', phone = '$data[phone]' WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('error' => 'Update failed'));
    }

    // Close database connection
    mysqli_close($conn);
} else {
    // Redirect to error page
    header('Location: error.php');
    exit;
}
?>