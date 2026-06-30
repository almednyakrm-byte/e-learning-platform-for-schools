**list_مدرسين.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدرسين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مدرسين</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مدرسين.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المدرس</th>
                    <th>البريد الإلكتروني</th>
                    <th>الجوال</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $response = file_get_contents('../backend/مدرسين.php');
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['اسم المدرس'] . '</td>';
                    echo '<td>' . $record['البريد الإلكتروني'] . '</td>';
                    echo '<td>' . $record['الجوال'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_مدرسين.php?id=' . $record['id'] . '" class="text-indigo-500">تعديل</a>';
                    echo '<button class="text-red-500" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/مدرسين.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record['اسم المدرس']}</td>
                            <td>${record['البريد الإلكتروني']}</td>
                            <td>${record['الجوال']}</td>
                            <td>
                                <a href="edit_مدرسين.php?id=${record['id']}" class="text-indigo-500">تعديل</a>
                                <button class="text-red-500" onclick="deleteRecord(${record['id']})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المدرس؟')) {
                fetch('../backend/مدرسين.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المدرس بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المدرس');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/مدرسين.php**

<?php
// Fetch records from database
$records = array(
    array(
        'id' => 1,
        'اسم المدرس' => 'مدرس 1',
        'البريد الإلكتروني' => 'm1@example.com',
        'الجوال' => '0123456789'
    ),
    array(
        'id' => 2,
        'اسم المدرس' => 'مدرس 2',
        'البريد الإلكتروني' => 'm2@example.com',
        'الجوال' => '0987654321'
    )
);

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['اسم المدرس'], $search) !== false || strpos($record['البريد الإلكتروني'], $search) !== false || strpos($record['الجوال'], $search) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    $records = array_filter($records, function($record) use ($id) {
        return $record['id'] !== $id;
    });
}

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($records);